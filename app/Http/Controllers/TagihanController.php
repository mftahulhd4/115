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
        $validated = $request->validate([
            'id_santri' => 'required|exists:santris,id_santri',
            'jenis_tagihan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_tagihan',
            'status' => 'required|in:Lunas,Belum Lunas,Jatuh Tempo',
            'keterangan_tambahan' => 'nullable|string',
            'tanggal_pelunasan' => 'nullable|date', // Tambahkan validasi tanggal pelunasan
        ]);
        
        $data = $validated;
        $data['Id_tagihan'] = 'TAG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

        // Logika untuk store()
        if ($data['status'] == 'Lunas' && empty($data['tanggal_pelunasan'])) {
            $data['tanggal_pelunasan'] = now();
        }

        Tagihan::create($data);
        return redirect()->route('tagihan.index')->with('success', 'Tagihan baru berhasil ditambahkan.');
    }

    public function show(Tagihan $tagihan)
    {
        $tagihan->load('santri');
        return view('tagihan.show', compact('tagihan'));
    }

    public function edit(Tagihan $tagihan)
    {
        $tagihan->load('santri');
        return view('tagihan.edit', compact('tagihan'));
    }

    public function update(Request $request, Tagihan $tagihan)
    {
        // =============================================================
        // KODE DIPERBAIKI DI SINI: Seluruh method update diperbaiki
        // =============================================================
        $validated = $request->validate([
            'jenis_tagihan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_tagihan',
            'status' => 'required|in:Lunas,Belum Lunas,Jatuh Tempo',
            'keterangan_tambahan' => 'nullable|string',
            'tanggal_pelunasan' => 'nullable|date', // Tambahkan validasi tanggal pelunasan
        ]);
        
        // Cek jika status diubah menjadi 'Lunas' dan tanggal pelunasan belum diisi
        if ($validated['status'] == 'Lunas' && empty($validated['tanggal_pelunasan'])) {
            // Jika tagihan sebelumnya belum lunas, set tanggal pelunasan ke hari ini
            if ($tagihan->status != 'Lunas') {
                 $validated['tanggal_pelunasan'] = now();
            } else {
                // Jika sebelumnya sudah lunas, pertahankan tanggal pelunasan yang lama
                $validated['tanggal_pelunasan'] = $tagihan->tanggal_pelunasan;
            }
        } 
        // Cek jika status diubah dari 'Lunas' menjadi status lain
        elseif ($validated['status'] != 'Lunas') {
            $validated['tanggal_pelunasan'] = null;
        }

        $tagihan->update($validated);
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
                         ->orWhere('id_santri', 'LIKE', '%' . $search . '%')
                         ->limit(10)
                         ->get(['id', 'id_santri', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'pendidikan', 'kamar', 'foto', 'jenis_kelamin']);

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
    
    public function cetakBrowser(Tagihan $tagihan)
    {
        return view('tagihan.print', compact('tagihan'));
    }
}