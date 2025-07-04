<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Santri;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PerizinanController extends Controller
{
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

        $perizinans = $query->get();

        if ($request->filled('status')) {
            $statusFilter = $request->status;
            $perizinans = $perizinans->filter(function ($izin) use ($statusFilter) {
                return $izin->status_efektif === $statusFilter;
            });
        }

        return view('perizinan.index', compact('perizinans'));
    }

    public function create()
    {
        return view('perizinan.create');
    }
    
    /**
     * PERBAIKAN UTAMA: LOGIKA PEMBUATAN ID BARU
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_santri' => 'required|string|exists:santris,id_santri',
            'keperluan' => 'required|string',
            'waktu_pergi' => 'required|date',
            'estimasi_kembali' => 'required|date|after_or_equal:waktu_pergi',
        ]);

        $izinAktif = Perizinan::where('id_santri', $request->id_santri)
                               ->whereNotIn('status', ['Kembali', 'Ditolak', 'Terlambat'])
                               ->exists();
        
        if ($izinAktif) {
            return back()
                ->withErrors(['id_santri' => 'Santri ini masih memiliki izin yang belum selesai. Tidak dapat membuat izin baru.'])
                ->withInput();
        }

        // --- Logika Baru untuk Nomor Urut Selalu Naik ---
        // 1. Cari izin terakhir yang dibuat hari ini berdasarkan urutan ID
        $lastPermitToday = Perizinan::whereDate('created_at', Carbon::today())->orderBy('id_izin', 'desc')->first();
        
        $nextNumber = 1; // Nomor urut default adalah 1

        // 2. Jika ada izin sebelumnya hari ini
        if ($lastPermitToday) {
            // Ambil 4 digit terakhir dari ID, ubah ke angka, lalu tambah 1
            $lastNumber = (int) substr($lastPermitToday->id_izin, -4);
            $nextNumber = $lastNumber + 1;
        }

        // 3. Buat ID baru yang dijamin unik untuk hari itu
        $id_izin = 'IZN-' . date('Ymd') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        // --- Akhir dari Logika Baru ---
        
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
            'estimasi_kembali' => 'required|date|after_or_equal:waktu_pergi',
            'status' => ['required', Rule::in(['Pengajuan', 'Diizinkan', 'Ditolak', 'Kembali'])],
            'waktu_kembali_aktual' => 'nullable|date',
        ]);

        $newStatus = $request->status;
        
        if ($newStatus == 'Kembali') {
            $waktuKembali = $request->waktu_kembali_aktual ? Carbon::parse($request->waktu_kembali_aktual) : now();
            $estimasiKembali = Carbon::parse($request->estimasi_kembali);
            $finalStatus = $waktuKembali->isAfter($estimasiKembali) ? 'Terlambat' : 'Kembali';

            $perizinan->update([
                'status' => $finalStatus,
                'waktu_kembali_aktual' => $waktuKembali,
                'estimasi_kembali' => $estimasiKembali,
            ]);
        } else {
            $perizinan->update([
                'status' => $newStatus,
                'estimasi_kembali' => $request->estimasi_kembali,
            ]);
        }

        return redirect()->route('perizinan.show', $perizinan->id_izin)->with('success', 'Data perizinan berhasil diperbarui.');
    }

    public function destroy(Perizinan $perizinan)
    {
        $perizinan->delete();
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil dihapus.');
    }
    
    public function searchSantri(Request $request)
    {
        $query = $request->get('q');
        $statusAlumniId = Status::where('nama_status', 'Alumni')->value('id_status');
        $santris = Santri::with(['pendidikan', 'kelas'])
            ->where(function($q) use ($query) {
                $q->where('nama_santri', 'like', '%' . $query . '%') 
                  ->orWhere('id_santri', 'like', '%' . $query . '%');
            })
            ->where('id_status', '!=', $statusAlumniId)
            ->limit(10)
            ->get();
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