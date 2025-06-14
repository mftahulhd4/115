<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Meskipun belum dipakai, kita siapkan
use Illuminate\Support\Str;
use Carbon\Carbon;

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar semua tagihan dengan fitur filter.
     */
    public function index(Request $request)
    {
        $query = Tagihan::with('santri')->latest();

        // Filter 1: Berdasarkan Pencarian Nama Santri
        if ($request->filled('search')) {
            $query->whereHas('santri', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        // Filter 2: Berdasarkan Bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_tagihan', $request->bulan);
        }

        // Filter 3: Berdasarkan Tahun (input manual)
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
            'tanggal_pelunasan' => 'nullable|date|required_if:status,Lunas',
            'keterangan_tambahan' => 'nullable|string',
        ]);

        $data = $request->all();
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
        $santri = $tagihan->santri;
        return view('tagihan.edit', compact('tagihan', 'santri'));
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'jenis_tagihan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_tagihan',
            'status' => 'required|in:Lunas,Belum Lunas,Jatuh Tempo',
            'tanggal_pelunasan' => 'nullable|date|required_if:status,Lunas',
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

    // Fungsi pencarian santri yang sudah diperbaiki
    public function searchSantri(Request $request)
    {
        $search = $request->get('term');
        
        // Menghapus filter status_santri agar semua santri bisa dicari
        $santris = Santri::where('nama_lengkap', 'LIKE', '%' . $search . '%')
                         ->limit(10)
                         ->get([
                             'id', 
                             'nama_lengkap', 
                             'jenis_kelamin',
                             'tempat_lahir',
                             'tanggal_lahir',
                             'alamat',
                             'pendidikan',
                             'foto'
                         ]);

        return response()->json($santris);
    }

    public function cetakDetailPdf(Tagihan $tagihan)
    {
        $pdf = Pdf::loadView('tagihan.detail_pdf', ['tagihan' => $tagihan]);
        $fileName = 'tagihan-' . Str::slug(optional($tagihan->santri)->nama_lengkap) . '-' . $tagihan->id . '.pdf';
        return $pdf->download($fileName);
    }

    public function cetakBrowser(Tagihan $tagihan)
    {
        return view('tagihan.print', compact('tagihan'));
    }
}