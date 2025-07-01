<?php

namespace App\Http\Controllers;

use App\Models\JenisTagihan;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisTagihan::query()->withCount(['tagihans as lunas_count' => fn($q) => $q->where('status_pembayaran', 'Lunas'), 'tagihans as total_santri']);
        if ($s = $request->search) $query->where('nama_tagihan', 'like', "%{$s}%");
        if ($b = $request->bulan) $query->where('bulan', $b);
        if ($t = $request->tahun) $query->where('tahun', $t);
        $jenisTagihans = $query->latest()->paginate(10);
        return view('tagihan.index', compact('jenisTagihans'));
    }

    public function create()
    {
        return view('tagihan.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_tagihan' => 'required', 'nominal' => 'required|numeric', 'bulan' => 'required', 'tahun' => 'required']);
        JenisTagihan::create($request->only(['nama_tagihan', 'nominal', 'bulan', 'tahun', 'deskripsi']));
        return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil dibuat.');
    }

    public function show(JenisTagihan $jenisTagihan)
    {
        $tagihans = $jenisTagihan->tagihans()->with('santri')->latest('created_at')->paginate(15);
        return view('tagihan.show', compact('jenisTagihan', 'tagihans'));
    }

    public function edit(JenisTagihan $jenisTagihan)
    {
        return view('tagihan.edit', compact('jenisTagihan'));
    }

    public function update(Request $request, JenisTagihan $jenisTagihan)
    {
        $request->validate(['nama_tagihan' => 'required', 'nominal' => 'required|numeric', 'bulan' => 'required', 'tahun' => 'required']);
        $jenisTagihan->update($request->only(['nama_tagihan', 'nominal', 'bulan', 'tahun', 'deskripsi']));
        return redirect()->route('tagihan.show', $jenisTagihan->id_jenis_tagihan)->with('success', 'Jenis tagihan berhasil diperbarui.');
    }

    public function destroy(JenisTagihan $jenisTagihan)
    {
        $jenisTagihan->delete();
        return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil dihapus.');
    }

    public function assign(Request $request, JenisTagihan $jenisTagihan)
    {
        $query = Santri::query();
        if ($p = $request->pendidikan) $query->where('pendidikan', $p);
        if ($k = $request->kelas) $query->where('kelas', $k);
        $santris = $query->get();
        $existingSantriIds = Tagihan::where('id_jenis_tagihan', $jenisTagihan->id_jenis_tagihan)->pluck('id_santri')->toArray();
        return view('tagihan.assign', compact('jenisTagihan', 'santris', 'existingSantriIds'));
    }

    public function storeAssignment(Request $request, JenisTagihan $jenisTagihan)
    {
        $santriIds = $request->input('santri_ids', []);
        foreach ($santriIds as $santriId) {
            Tagihan::updateOrCreate(
                ['id_jenis_tagihan' => $jenisTagihan->id_jenis_tagihan, 'id_santri' => $santriId],
                ['status_pembayaran' => 'Belum Lunas', 'tanggal_pembayaran' => null]
            );
        }
        return redirect()->route('tagihan.show', $jenisTagihan->id_jenis_tagihan)->with('success', 'Tagihan berhasil diterapkan.');
    }

    public function showSantriBill(Tagihan $tagihan)
    {
        $tagihan->load(['santri', 'jenisTagihan']);
        return view('tagihan.show_santri_bill', compact('tagihan'));
    }

    public function editSantriBill(Tagihan $tagihan)
    {
        return view('tagihan.edit_santri_bill', compact('tagihan'));
    }

    public function updateSantriBill(Request $request, Tagihan $tagihan)
    {
        $request->validate(['status_pembayaran' => 'required|in:Lunas,Belum Lunas']);
        $tagihan->update([
            'status_pembayaran' => $request->status_pembayaran,
            'tanggal_pembayaran' => $request->status_pembayaran == 'Lunas' ? ($request->tanggal_pembayaran ?: now()) : null,
        ]);
        return redirect()->route('tagihan.show_santri_bill', $tagihan->id_tagihan)->with('success', 'Status pembayaran berhasil diubah.');
    }

    public function destroySantriBill(Tagihan $tagihan)
    {
        $idJenisTagihan = $tagihan->id_jenis_tagihan;
        $tagihan->delete();
        return redirect()->route('tagihan.show', $idJenisTagihan)->with('success', 'Tagihan untuk santri telah dihapus.');
    }

    public function pdfReceipt(Tagihan $tagihan)
    {
        $pdf = Pdf::loadView('tagihan.receipt_pdf', compact('tagihan'));
        return $pdf->download('kwitansi-'.$tagihan->id_tagihan.'.pdf');
    }

    public function printReceipt(Tagihan $tagihan)
    {
        return view('tagihan.receipt_print', compact('tagihan'));
    }
}