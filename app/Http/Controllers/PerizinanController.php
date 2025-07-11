<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Santri;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class PerizinanController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-perizinan');
    }

    public function index(Request $request)
    {
        $query = Perizinan::with(['santri.kelas', 'santri.pendidikan'])->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('id_izin', 'like', "%{$search}%")
                  ->orWhereHas('santri', function ($q) use ($search) {
                      $q->where('nama_santri', 'like', "%{$search}%");
                  });
        }
        
        if ($request->filled('jenis_kelamin')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('jenis_kelamin', $request->jenis_kelamin);
            });
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('waktu_pergi', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('waktu_pergi', $request->tahun);
        }
        
        $allPerizinans = $query->get();

        if ($request->filled('status')) {
            $statusFilter = $request->status;
            $allPerizinans = $allPerizinans->filter(function ($izin) use ($statusFilter) {
                return $izin->status_efektif === $statusFilter;
            });
        }
        
        $perPage = 15;
        $currentPage = Paginator::resolveCurrentPage('page') ?: 1;
        $currentPageItems = $allPerizinans->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $perizinans = new LengthAwarePaginator($currentPageItems, count($allPerizinans), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view('perizinan.index', compact('perizinans'));
    }

    public function create()
    {
        $statuses = Status::orderBy('nama_status')->get();
        return view('perizinan.create', compact('statuses'));
    }
    
    /**
     * Logika pencegahan izin ganda ada di sini.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_santri' => 'required|string|exists:santris,id_santri',
            'keperluan' => 'required|string',
            'waktu_pergi' => 'required|date',
            'estimasi_kembali' => 'required|date|after_or_equal:waktu_pergi',
        ]);

        // [LOGIKA PENTING] Mengecek apakah santri sudah punya izin aktif
        $izinAktif = Perizinan::where('id_santri', $request->id_santri)
                               ->whereIn('status', ['Pengajuan', 'Diizinkan'])
                               ->exists();
        
        // Jika izin aktif ditemukan, kembalikan dengan pesan error
        if ($izinAktif) {
            return back()
                ->withErrors(['id_santri' => 'Gagal! Santri ini masih memiliki izin yang sedang aktif (status Pengajuan atau Diizinkan) dan belum kembali.'])
                ->withInput();
        }

        // Jika tidak ada izin aktif, lanjutkan proses pembuatan izin baru
        $lastPermitToday = Perizinan::whereDate('created_at', Carbon::today())->orderBy('id_izin', 'desc')->first();
        
        $nextNumber = 1;
        if ($lastPermitToday) {
            $lastNumber = (int) substr($lastPermitToday->id_izin, -4);
            $nextNumber = $lastNumber + 1;
        }
        $id_izin = 'IZN-' . date('Ymd') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        $validated['id_izin'] = $id_izin;
        $validated['status'] = 'Pengajuan';

        Perizinan::create($validated);
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil diajukan.');
    }

    public function show(Perizinan $perizinan)
    {
        $perizinan->load(['santri.pendidikan', 'santri.kelas', 'santri.status']);
        return view('perizinan.show', compact('perizinan'));
    }

    public function edit(Perizinan $perizinan)
    {
        $perizinan->load(['santri.pendidikan', 'santri.kelas', 'santri.status']);
        return view('perizinan.edit', compact('perizinan'));
    }

    public function update(Request $request, Perizinan $perizinan)
    {
        $validated = $request->validate([
            'keperluan' => 'required|string',
            'estimasi_kembali' => 'required|date',
            'status' => ['required', Rule::in(['Pengajuan', 'Diizinkan', 'Ditolak', 'Kembali'])],
            'waktu_kembali_aktual' => 'nullable|date',
        ]);
        
        $newStatus = $request->status;
        $updateData = ['keperluan' => $validated['keperluan'], 'estimasi_kembali' => $validated['estimasi_kembali']];
        if ($newStatus == 'Kembali') {
            $waktuKembali = $request->waktu_kembali_aktual ? Carbon::parse($request->waktu_kembali_aktual) : now();
            $estimasiKembali = Carbon::parse($validated['estimasi_kembali']);
            $updateData['status'] = $waktuKembali->isAfter($estimasiKembali) ? 'Terlambat' : 'Kembali';
            $updateData['waktu_kembali_aktual'] = $waktuKembali;
        } else {
            $updateData['status'] = $newStatus;
            $updateData['waktu_kembali_aktual'] = null;
        }
        $perizinan->update($updateData);
        return redirect()->route('perizinan.show', $perizinan->id_izin)->with('success', 'Data perizinan berhasil diperbarui.');
    }

    public function destroy(Perizinan $perizinan)
    {
        $perizinan->delete();
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil dihapus.');
    }
    
    /**
     * [PERBAIKAN] Filter untuk menyembunyikan santri yang sudah izin Dihapus.
     * Sekarang semua santri akan muncul di pencarian.
     */
    public function searchSantri(Request $request)
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
            'id_status' => 'required|exists:statuses,id_status',
        ]);

        $searchTerm = $validated['q'] ?? null;
        $id_status = $validated['id_status'];

        $query = Santri::with(['pendidikan', 'kelas'])->where('id_status', $id_status);

        if ($searchTerm) {
            $query->where(function($subQuery) use ($searchTerm) {
                $subQuery->where('nama_santri', 'like', '%' . $searchTerm . '%')
                         ->orWhere('id_santri', 'like', '%' . $searchTerm . '%');
            });
        }

        $santris = $query->limit(10)->get();
            
        $santris->transform(function ($santri) {
            $santri->foto_url = $santri->foto ? asset('storage/fotos/' . $santri->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($santri->nama_santri) . '&background=random';
            $santri->nama_pendidikan = optional($santri->pendidikan)->nama_pendidikan ?? '-';
            $santri->nama_kelas = optional($santri->kelas)->nama_kelas ?? '-';
            return $santri;
        });
        
        return response()->json($santris);
    }

    public function detailPdf(Perizinan $perizinan)
    {
        $perizinan->load(['santri.pendidikan', 'santri.kelas']);
        $pdf = Pdf::loadView('perizinan.detail_pdf', compact('perizinan'));
        return $pdf->stream('surat-izin-' . $perizinan->santri->nama_santri . '.pdf');
    }

    public function print(Perizinan $perizinan)
    {
        $perizinan->load(['santri.pendidikan', 'santri.kelas']);
        return view('perizinan.print', compact('perizinan'));
    }
}