<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PerizinanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perizinan::with('santri')->latest();

        // Terapkan filter berdasarkan input dari request
        if ($request->filled('nama_santri')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->nama_santri . '%');
            });
        }
        
        // Logika untuk filter 'status' dihapus

        // Filter berdasarkan bulan dan tahun
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_izin', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_izin', $request->tahun);
        }

        $perizinans = $query->paginate(10)->withQueryString();

        // Variabel untuk opsi filter yang tidak perlu sudah dihapus
        return view('perizinan.index', compact('perizinans'));
    }

    public function create()
    {
        return view('perizinan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kepentingan_izin' => 'required|string|max:255',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_izin',
            'keterangan_tambahan' => 'nullable|string',
        ]);

        $today = now()->format('Ymd');
        $lastPerizinan = Perizinan::where('id_izin', 'like', "IZN-{$today}-%")
                                  ->orderBy('id_izin', 'desc')
                                  ->first();

        $sequence = 1;
        if ($lastPerizinan) {
            $lastSequence = (int) substr($lastPerizinan->id_izin, -4);
            $sequence = $lastSequence + 1;
        }
        
        $validated['id_izin'] = "IZN-{$today}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'Izin';

        Perizinan::create($validated);

        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil dibuat.');
    }

    public function show(Perizinan $perizinan)
    {
        return view('perizinan.show', compact('perizinan'));
    }

    public function edit(Perizinan $perizinan)
    {
        return view('perizinan.edit', compact('perizinan'));
    }

    public function update(Request $request, Perizinan $perizinan)
    {
        $validated = $request->validate([
            'kepentingan_izin' => 'required|string|max:255',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_izin',
            'keterangan_tambahan' => 'nullable|string',
        ]);
        
        if($perizinan->tanggal_kembali != $request->tanggal_kembali && now()->greaterThan($request->tanggal_kembali)){
             $validated['status'] = 'Terlambat';
        } else if ($request->filled('tanggal_kembali')) {
            $validated['status'] = 'Kembali';
        }

        $perizinan->update($validated);

        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil diperbarui.');
    }

    public function destroy(Perizinan $perizinan)
    {
        $perizinan->delete();
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil dihapus.');
    }

    public function searchSantri(Request $request)
    {
        $term = $request->get('term');
        $santris = Santri::where('nama_lengkap', 'like', '%' . $term . '%')
                        ->orWhere('Id_santri', 'like', '%' . $term . '%')
                        ->where('status_santri', 'Aktif')
                        ->limit(10)
                        ->get(['id', 'Id_santri', 'nama_lengkap', 'kamar', 'pendidikan', 'kelas', 'foto']);

        $santris->transform(function ($santri) {
            $santri->foto_url = $santri->foto
                                ? asset('storage/fotos/' . $santri->foto)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($santri->nama_lengkap) . '&color=7F9CF5&background=EBF4FF';
            return $santri;
        });

        return response()->json($santris);
    }
    
    public function cetakDetailPdf(Perizinan $perizinan)
    {
        $pdf = Pdf::loadView('perizinan.detail_pdf', compact('perizinan'));
        return $pdf->download('surat-izin-' . $perizinan->santri->nama_lengkap . '.pdf');
    }

    public function cetakBrowser(Perizinan $perizinan)
    {
        return view('perizinan.print', compact('perizinan'));
    }
}