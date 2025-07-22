<?php

namespace App\Http\Controllers;

use App\Models\JenisTagihan;
use App\Models\DaftarTagihan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pendidikan;
use App\Models\Kelas;
use App\Models\Kamar;
use App\Models\Status;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DaftarTagihanController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view-tagihan')->only(['index', 'show', 'pdfReceipt', 'printReceipt']);
        $this->middleware('can:manage-tagihan-full')->except(['index', 'show', 'pdfReceipt', 'printReceipt']);
    }

    public function index(Request $request)
    {
        $query = JenisTagihan::query();

        if ($s = $request->search) {
            $query->where('nama_jenis_tagihan', 'like', "%{$s}%")
                  ->orWhere('id_jenis_tagihan', 'like', "%{$s}%");
        }
        if ($b = $request->bulan) {
            $query->whereRaw('(MONTH(tanggal_tagihan) = ? OR (tanggal_tagihan IS NULL AND MONTH(tanggal_jatuh_tempo) = ?))', [$b, $b]);
        }
        if ($t = $request->tahun) {
            $query->whereRaw('(YEAR(tanggal_tagihan) = ? OR (tanggal_tagihan IS NULL AND YEAR(tanggal_jatuh_tempo) = ?))', [$t, $t]);
        }
        
        $jenisTagihans = $query->latest()->paginate(10)->withQueryString();
        return view('tagihan.index', compact('jenisTagihans'));
    }

    public function create()
    {
        return view('tagihan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis_tagihan' => 'required|string|max:255',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_tagihan',
            'deskripsi' => 'nullable|string',
            'bulan' => 'nullable|integer|between:1,12',
            'tahun' => 'nullable|digits:4|integer',
        ]);

        $id_jenis_tagihan = 'JNS' . now()->format('YmdHis');
        JenisTagihan::create(array_merge($request->all(), ['id_jenis_tagihan' => $id_jenis_tagihan]));
        
        return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil ditambahkan.');
    }

    public function show(Request $request, JenisTagihan $jenisTagihan)
    {
        // [MODIFIKASI] Tetap memuat data kamar, tapi hapus logika filter
        $query = $jenisTagihan->daftarTagihan()
                              ->with(['santri.kelas', 'santri.pendidikan', 'santri.kamar']);
        
        $daftarTagihan = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('tagihan.show', compact('jenisTagihan', 'daftarTagihan'));
    }

    public function edit(JenisTagihan $jenisTagihan)
    {
        if ($jenisTagihan->daftarTagihan()->exists()) {
            return redirect()->route('tagihan.show', $jenisTagihan)
                             ->with('error', 'Jenis tagihan ini tidak dapat diedit karena sudah diterapkan kepada santri.');
        }
        return view('tagihan.edit', compact('jenisTagihan'));
    }

    public function update(Request $request, JenisTagihan $jenisTagihan)
    {
        if ($jenisTagihan->daftarTagihan()->exists()) {
            return redirect()->route('tagihan.show', $jenisTagihan)
                             ->with('error', 'Aksi tidak diizinkan. Jenis tagihan ini sudah memiliki data transaksi.');
        }

        $request->validate([
            'nama_jenis_tagihan' => 'required|string|max:255',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_tagihan',
            'deskripsi' => 'nullable|string',
            'bulan' => 'nullable|integer|between:1,12',
            'tahun' => 'nullable|digits:4|integer',
        ]);
        
        $jenisTagihan->update($request->all());
        return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil diperbarui.');
    }

    public function destroy(JenisTagihan $jenisTagihan)
    {
        try {
            $jenisTagihan->delete();
            return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Gagal menghapus! Jenis tagihan ini masih terhubung dengan data tagihan santri.');
        }
    }

    public function assign(Request $request, JenisTagihan $jenisTagihan)
    {
        $pendidikans = Pendidikan::orderBy('nama_pendidikan')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $statuses = Status::orderBy('nama_status')->get();
        $kamars = Kamar::orderBy('nama_kamar')->get();

        $santris = collect();

        $isFiltered = $request->filled('id_pendidikan') || $request->filled('id_kelas') || $request->filled('jenis_kelamin') || $request->filled('id_kamar');

        if ($isFiltered) {
            $query = Santri::with(['pendidikan', 'kelas', 'status', 'kamar']);
            if ($request->filled('id_pendidikan')) $query->where('id_pendidikan', $request->id_pendidikan);
            if ($request->filled('id_kelas')) $query->where('id_kelas', $request->id_kelas);
            if ($request->filled('jenis_kelamin')) $query->where('jenis_kelamin', $request->jenis_kelamin);
            if ($request->filled('id_kamar')) $query->where('id_kamar', $request->id_kamar);
            $santris = $query->get();
        }

        $existingSantriIds = DaftarTagihan::where('id_jenis_tagihan', $jenisTagihan->id_jenis_tagihan)->pluck('id_santri')->toArray();
            
        return view('tagihan.assign', compact('jenisTagihan', 'santris', 'existingSantriIds', 'pendidikans', 'kelases', 'statuses', 'kamars'));
    }

    public function storeAssignment(Request $request, JenisTagihan $jenisTagihan)
    {
        $request->validate([
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'exists:santris,id_santri',
        ]);
        
        DB::transaction(function () use ($request, $jenisTagihan) {
            foreach ($request->input('santri_ids', []) as $santriId) {
                $tanggal = now()->format('Ymd');
                $latest = DaftarTagihan::where('id_daftar_tagihan', 'like', "TGH-{$tanggal}-%")->latest('id_daftar_tagihan')->first();
                $nomorUrut = $latest ? ((int)substr($latest->id_daftar_tagihan, -4)) + 1 : 1;
                $id_daftar_tagihan = "TGH-{$tanggal}-" . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
                
                DaftarTagihan::updateOrCreate(
                    ['id_jenis_tagihan' => $jenisTagihan->id_jenis_tagihan, 'id_santri' => $santriId],
                    ['id_daftar_tagihan' => $id_daftar_tagihan, 'status_pembayaran' => 'Belum Lunas']
                );
            }
        });
        
        return redirect()->route('tagihan.show', $jenisTagihan->id_jenis_tagihan)->with('success', 'Tagihan berhasil diterapkan ke santri terpilih.');
    }

    public function updateSantriBill(Request $request, DaftarTagihan $tagihan)
    {
        $request->validate(['status_pembayaran' => ['required', Rule::in(['Lunas'])]]);

        if ($tagihan->status_pembayaran === 'Lunas') {
            return back()->with('error', 'Tagihan ini sudah lunas.');
        }

        $tagihan->update([
            'status_pembayaran' => 'Lunas',
            'tanggal_bayar' => now(),
            'user_id_pembayaran' => auth()->id(),
            'keterangan' => 'Pembayaran lunas dikonfirmasi pada ' . now(),
        ]);
        
        return redirect()->route('tagihan.show', $tagihan->id_jenis_tagihan)->with('success', 'Status pembayaran berhasil diubah menjadi Lunas.');
    }
    
    public function destroySantriBill(DaftarTagihan $tagihan)
    {
        $idJenisTagihan = $tagihan->id_jenis_tagihan;
        $tagihan->delete();
        return redirect()->route('tagihan.show', $idJenisTagihan)->with('success', 'Tagihan untuk santri telah dihapus.');
    }

    public function cancelPayment(Request $request, DaftarTagihan $tagihan)
    {
        $request->validate(['alasan_pembatalan' => 'required|string|min:10']);

        if ($tagihan->status_pembayaran !== 'Lunas') {
            return back()->with('error', 'Hanya tagihan yang sudah lunas yang bisa dibatalkan.');
        }

        $userPembatal = auth()->user()->name;
        $alasan = $request->alasan_pembatalan;
        
        Log::info("PEMBATALAN: Tagihan {$tagihan->id_daftar_tagihan} dibatalkan oleh '{$userPembatal}'. Alasan: '{$alasan}'.");

        $tagihan->update([
            'status_pembayaran' => 'Belum Lunas',
            'tanggal_bayar' => null,
            'user_id_pembayaran' => null,
            'keterangan' => "Pembayaran dibatalkan pada " . now() . " oleh {$userPembatal} dengan alasan: {$alasan}",
        ]);

        return redirect()->route('tagihan.show', $tagihan->id_jenis_tagihan)->with('success', 'Pembayaran berhasil dibatalkan.');
    }
    
    public function pdfReceipt(DaftarTagihan $tagihan)
    {
        $pdf = Pdf::loadView('tagihan.receipt_pdf', compact('tagihan'));
        return $pdf->download('kwitansi-'.$tagihan->id_daftar_tagihan.'.pdf');
    }
    
    public function printReceipt(DaftarTagihan $tagihan)
    {
        return view('tagihan.receipt_print', compact('tagihan'));
    }
}