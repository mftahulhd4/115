<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PerizinanController extends Controller
{
    // ... (metode index, create, store, destroy, searchSantri, pdf, print tetap sama) ...
    public function index(Request $request)
    {
        $query = Perizinan::with('santri')->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('santri', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('id_santri', 'like', "%{$search}%");
            });
        }
        $perizinans = $query->paginate(10);
        return view('perizinan.index', compact('perizinans'));
    }

    public function create()
    {
        return view('perizinan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_santri' => 'required|string|exists:santris,id_santri',
            'keperluan' => 'required|string',
            'waktu_izin' => 'required|date',
            'estimasi_kembali' => 'required|date|after_or_equal:waktu_izin',
            'keterangan' => 'nullable|string',
        ]);
        $izin_terakhir = Perizinan::whereDate('created_at', Carbon::today())->count();
        $id_izin = 'IZN-' . date('Ymd') . '-' . str_pad($izin_terakhir + 1, 4, '0', STR_PAD_LEFT);
        $validated['id_izin'] = $id_izin;
        $validated['penanggung_jawab'] = Auth::user()->name;
        $validated['status'] = 'Pengajuan';
        Perizinan::create($validated);
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil diajukan.');
    }

    public function show(Perizinan $perizinan)
    {
        return view('perizinan.show', compact('perizinan'));
    }

    public function edit(Perizinan $perizinan)
    {
        return view('perizinan.edit', compact('perizinan'));
    }

    /**
     * PERUBAHAN TOTAL: Metode update sekarang menangani semua logika.
     */
    public function update(Request $request, Perizinan $perizinan)
    {
        $validated = $request->validate([
            'keperluan' => 'required|string',
            'waktu_izin' => 'required|date',
            'estimasi_kembali' => 'required|date|after_or_equal:waktu_izin',
            'keterangan' => 'nullable|string',
            'status' => ['required', Rule::in(['Pengajuan', 'Diizinkan', 'Kembali', 'Terlambat'])],
            'waktu_kembali_aktual' => 'nullable|date',
        ]);

        // Logika otomatis saat status diubah menjadi 'Kembali'
        if ($validated['status'] == 'Kembali' && empty($validated['waktu_kembali_aktual'])) {
            $validated['waktu_kembali_aktual'] = now();
        }

        // Logika otomatis untuk menentukan status 'Terlambat'
        if (!empty($validated['waktu_kembali_aktual'])) {
            $waktuKembali = Carbon::parse($validated['waktu_kembali_aktual']);
            if ($waktuKembali->isAfter($perizinan->estimasi_kembali)) {
                $validated['status'] = 'Terlambat';
            } else {
                $validated['status'] = 'Kembali';
            }
        }

        $perizinan->update($validated);

        return redirect()->route('perizinan.show', $perizinan)->with('success', 'Data perizinan berhasil diperbarui.');
    }

    /**
     * PERUBAHAN: Metode ini tidak lagi diperlukan.
     * public function updateStatus(...) { ... }
     */

    public function destroy(Perizinan $perizinan)
    {
        $perizinan->delete();
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil dihapus.');
    }
    
    public function searchSantri(Request $request)
    {
        $query = $request->get('q');
        $santris = Santri::where('nama_lengkap', 'like', '%' . $query . '%')
            ->orWhere('id_santri', 'like', '%' . $query . '%')
            ->where('status_santri', '!=', 'Alumni')
            ->limit(10)
            ->get(['id_santri', 'nama_lengkap', 'kamar', 'kelas', 'foto', 'tempat_lahir', 'tanggal_lahir', 'pendidikan']);
        $santris->transform(function ($santri) {
            $santri->foto_url = $santri->foto
                ? asset('storage/fotos/' . $santri->foto)
                : 'https://ui-avatars.com/api/?name=' . urlencode($santri->nama_lengkap) . '&background=random';
            return $santri;
        });
        return response()->json($santris);
    }
    
    public function detailPdf(Perizinan $perizinan)
    {
        $pdf = Pdf::loadView('perizinan.detail_pdf', compact('perizinan'));
        return $pdf->stream('surat-izin-' . $perizinan->id_izin . '.pdf');
    }

    public function print(Perizinan $perizinan)
    {
        return view('perizinan.print', compact('perizinan'));
    }
}