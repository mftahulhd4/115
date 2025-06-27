<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class PerizinanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perizinan::with('santri')->latest();

        // Logika untuk filter pencarian
        if ($request->filled('search')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_izin', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_izin', $request->tahun);
        }

        $perizinans = $query->paginate(10)->withQueryString();
        return view('perizinan.index', compact('perizinans'));
    }

    public function create()
    {
        return view('perizinan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kepentingan_izin' => 'required|string|max:255',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_izin',
            'keterangan_tambahan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['id_izin'] = 'IZN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        $data['status'] = 'Izin';

        Perizinan::create($data);
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil ditambahkan.');
    }

    public function show(Perizinan $perizinan)
    {
        $perizinan->load('santri');
        return view('perizinan.show', compact('perizinan'));
    }

    public function edit(Perizinan $perizinan)
    {
        return view('perizinan.edit', compact('perizinan'));
    }

    public function update(Request $request, Perizinan $perizinan)
    {
        $request->validate([
            'kepentingan_izin' => 'required|string|max:255',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_izin',
            'status' => 'required|in:Izin,Kembali,Terlambat',
            'keterangan_tambahan' => 'nullable|string',
        ]);

        $perizinan->update($request->all());
        return redirect()->route('perizinan.show', $perizinan)->with('success', 'Data perizinan berhasil diperbarui.');
    }

    public function destroy(Perizinan $perizinan)
    {
        $perizinan->delete();
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil dihapus.');
    }

    public function searchSantri(Request $request)
    {
        $search = $request->get('term');
        $santris = Santri::where('nama_lengkap', 'LIKE', '%' . $search . '%')
            ->whereIn('status_santri', ['Aktif', 'Pengurus'])
            ->limit(10)
            ->get();

        $santris->transform(function ($santri) {
            $santri->foto_url = $santri->foto ? asset('storage/fotos/' . $santri->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($santri->nama_lengkap) . '&color=7F9CF5&background=EBF4FF';
            return $santri;
        });

        return response()->json($santris);
    }
    
    /**
     * ===============================================
     * FUNGSI YANG HILANG DAN DITAMBAHKAN KEMBALI
     * ===============================================
     */
    public function cetakBrowser(Perizinan $perizinan)
    {
        return view('perizinan.print', ['perizinan' => $perizinan]);
    }

    public function cetakDetailPdf(Perizinan $perizinan)
    {
        $pdf = Pdf::loadView('perizinan.detail_pdf', ['perizinan' => $perizinan]);
        return $pdf->download('surat-izin-' . Str::slug(optional($perizinan->santri)->nama_lengkap) . '.pdf');
    }
}