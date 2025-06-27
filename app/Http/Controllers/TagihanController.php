<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tagihan::with('santri')->latest();

        if ($request->filled('search')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('jenis_tagihan')) {
            $query->where('jenis_tagihan', $request->jenis_tagihan);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_tagihan', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_tagihan', $request->tahun);
        }
        
        $tagihans = $query->paginate(10)->withQueryString();
        return view('tagihan.index', compact('tagihans'));
    }

    public function create()
    {
        return view('tagihan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'jenis_tagihan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_tagihan',
            'status' => 'required|in:Lunas,Belum Lunas,Jatuh Tempo',
            'keterangan_tambahan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['Id_tagihan'] = 'TAG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        if ($request->status == 'Lunas' && !$request->filled('tanggal_pelunasan')) {
            $data['tanggal_pelunasan'] = now();
        }

        Tagihan::create($data);
        return redirect()->route('tagihan.index')->with('success', 'Tagihan baru berhasil ditambahkan.');
    }

    public function show(Tagihan $tagihan)
    {
        return view('tagihan.show', compact('tagihan'));
    }

    public function edit(Tagihan $tagihan)
    {
        return view('tagihan.edit', compact('tagihan'));
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'jenis_tagihan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_tagihan',
            'status' => 'required|in:Lunas,Belum Lunas,Jatuh Tempo',
            'keterangan_tambahan' => 'nullable|string',
        ]);
        
        $data = $request->all();
        if ($data['status'] != 'Lunas') {
            $data['tanggal_pelunasan'] = null;
        } elseif ($data['status'] == 'Lunas' && empty($data['tanggal_pelunasan'])) {
            $data['tanggal_pelunasan'] = now();
        }

        $tagihan->update($data);
        return redirect()->route('tagihan.show', $tagihan)->with('success', 'Data tagihan berhasil diperbarui.');
    }

    public function destroy(Tagihan $tagihan)
    {
        $tagihan->delete();
        return redirect()->route('tagihan.index')->with('success', 'Data tagihan berhasil dihapus.');
    }

    public function searchSantri(Request $request)
    {
        $search = $request->get('term');
        $santris = Santri::where('nama_lengkap', 'LIKE', '%' . $search . '%')
                         ->limit(10)
                         ->get(['id', 'Id_santri', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'pendidikan', 'kamar', 'foto']);

        $santris->transform(function ($santri) {
            $santri->foto_url = $santri->foto 
                                ? asset('storage/fotos/' . $santri->foto) 
                                : 'https://ui-avatars.com/api/?name=' . urlencode($santri->nama_lengkap) . '&color=7F9CF5&background=EBF4FF';
            return $santri;
        });

        return response()->json($santris);
    }

    public function cetakDetailPdf(Tagihan $tagihan)
    {
        $pdf = Pdf::loadView('tagihan.detail_pdf', ['tagihan' => $tagihan]);
        $fileName = 'tagihan-' . Str::slug($tagihan->Id_tagihan) . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * ===============================================
     * FUNGSI YANG HILANG DAN DITAMBAHKAN KEMBALI
     * ===============================================
     */
    public function cetakBrowser(Tagihan $tagihan)
    {
        return view('tagihan.print', compact('tagihan'));
    }
}