<?php

namespace App\Http\Controllers;

use App\Models\JenisTagihan;
use App\Models\Santri;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TagihanController extends Controller
{
    // ... (metode index, create, store, show, edit, update, destroy untuk JenisTagihan tetap sama) ...
    // ... (metode assign, storeAssignment, destroySantriBill juga tetap sama) ...
    public function index(Request $request)
    {
        $query = JenisTagihan::query();
        if ($request->filled('search')) { $query->where('nama_tagihan', 'like', '%' . $request->search . '%'); }
        if ($request->filled('bulan')) { $query->where('bulan', $request->bulan); }
        if ($request->filled('tahun')) { $query->where('tahun', $request->tahun); }
        $jenisTagihans = $query->withCount(['tagihans as total_santri', 'tagihans as lunas_count' => fn ($q) => $q->where('status_pembayaran', 'Lunas')])->latest()->paginate(10);
        return view('tagihan.index', compact('jenisTagihans'));
    }
    public function create(){ return view('tagihan.create'); }
    public function store(Request $request)
    {
        $validated = $request->validate(['nama_tagihan' => 'required|string|max:255', 'deskripsi' => 'nullable|string', 'jumlah' => 'required|numeric|min:0', 'bulan' => 'required|integer|min:1|max:12', 'tahun' => 'required|integer|digits:4']);
        JenisTagihan::create($validated);
        return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan baru berhasil dibuat.');
    }
    public function show(JenisTagihan $jenisTagihan)
    {
        // PERUBAHAN: Memastikan semua data santri diambil dengan with('santri:*')
        $tagihans = $jenisTagihan->tagihans()->with('santri:*')->paginate(20);

        return view('tagihan.show', compact('jenisTagihan', 'tagihans'));
    }
    public function edit(JenisTagihan $jenisTagihan){ return view('tagihan.edit', compact('jenisTagihan')); }
    public function update(Request $request, JenisTagihan $jenisTagihan)
    {
        $validated = $request->validate(['nama_tagihan' => 'required|string|max:255', 'deskripsi' => 'nullable|string', 'jumlah' => 'required|numeric|min:0', 'bulan' => 'required|integer|min:1|max:12', 'tahun' => 'required|integer|digits:4']);
        $jenisTagihan->update($validated);
        return redirect()->route('tagihan.show', $jenisTagihan)->with('success', 'Jenis tagihan berhasil diperbarui.');
    }
    public function destroy(JenisTagihan $jenisTagihan){ $jenisTagihan->delete(); return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan dan semua data terkait berhasil dihapus.'); }
    public function assign(Request $request, JenisTagihan $jenisTagihan)
    {
        $query = Santri::query()->where('status_santri', '!=', 'Alumni');
        if ($request->filled('pendidikan')) { $query->where('pendidikan', $request->pendidikan); }
        if ($request->filled('kelas')) { $query->where('kelas', $request->kelas); }
        $santris = $query->get();
        $existingSantriIds = $jenisTagihan->tagihans()->pluck('id_santri')->toArray();
        return view('tagihan.assign', compact('jenisTagihan', 'santris', 'existingSantriIds'));
    }
    public function storeAssignment(Request $request, JenisTagihan $jenisTagihan)
    {
        $request->validate(['santri_ids' => 'required|array', 'santri_ids.*' => 'string|exists:santris,id_santri']);
        $santriIds = $request->input('santri_ids');
        $newAssignments = 0;
        foreach ($santriIds as $santriId) {
            $exists = Tagihan::where('jenis_tagihan_id', $jenisTagihan->id)->where('id_santri', $santriId)->exists();
            if (!$exists) {
                Tagihan::create(['jenis_tagihan_id' => $jenisTagihan->id, 'id_santri' => $santriId]);
                $newAssignments++;
            }
        }
        return redirect()->route('tagihan.show', $jenisTagihan)->with('success', "$newAssignments tagihan baru berhasil diterapkan kepada santri.");
    }
    public function destroySantriBill(Tagihan $tagihan){ $jenisTagihanId = $tagihan->jenis_tagihan_id; $tagihan->delete(); return redirect()->route('tagihan.show', $jenisTagihanId)->with('success', 'Tagihan untuk santri berhasil dihapus.'); }
    
    // --- METODE BARU & PERUBAHAN LOGIKA ---

    public function showSantriBill(Tagihan $tagihan)
    {
        $tagihan->load(['santri', 'jenisTagihan']);
        return view('tagihan.show_santri_bill', compact('tagihan'));
    }
    
    /**
     * Menampilkan form untuk mengedit tagihan individual.
     */
    public function editSantriBill(Tagihan $tagihan)
    {
        return view('tagihan.edit_santri_bill', compact('tagihan'));
    }

    /**
     * Memproses update untuk tagihan individual (termasuk status pembayaran).
     */
    public function updateSantriBill(Request $request, Tagihan $tagihan)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|in:Lunas,Belum Lunas',
            'tanggal_pembayaran' => 'nullable|date',
        ]);

        // Jika status diubah ke Lunas dan tanggal bayar kosong, isi dengan tanggal sekarang
        if ($validated['status_pembayaran'] == 'Lunas' && empty($validated['tanggal_pembayaran'])) {
            $validated['tanggal_pembayaran'] = now();
        }

        // Jika status diubah kembali ke Belum Lunas, kosongkan tanggal bayar
        if ($validated['status_pembayaran'] == 'Belum Lunas') {
            $validated['tanggal_pembayaran'] = null;
        }

        $tagihan->update($validated);

        return redirect()->route('tagihan.showSantriBill', $tagihan)->with('success', 'Status tagihan berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman print untuk kuitansi.
     */
   

    /**
     * Membuat file PDF untuk kuitansi.
     */
    public function printReceipt(Tagihan $tagihan)
    {
        // Pastikan view yang dipanggil adalah receipt_print
        return view('tagihan.receipt_print', compact('tagihan'));
    }

    /**
     * Membuat file PDF untuk kuitansi.
     */
    public function pdfReceipt(Tagihan $tagihan)
    {
        // Pastikan view yang dipanggil adalah receipt_pdf
        $pdf = Pdf::loadView('tagihan.receipt_pdf', compact('tagihan'));
        return $pdf->stream('kuitansi-' . $tagihan->santri->id_santri . '-' . $tagihan->jenisTagihan->nama_tagihan . '.pdf');
    }
}