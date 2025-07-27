<?php

namespace App\Http\Controllers;

use App\Models\DaftarTagihan;
use App\Models\JenisTagihan;
use App\Models\Santri;
use App\Models\Pendidikan;
use App\Models\Kelas;
use App\Models\Kamar;
use App\Models\Status;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DaftarTagihanController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view-tagihan')->only(['index', 'show']);
        $this->middleware('can:manage-tagihan-full')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = JenisTagihan::latest();

        if ($request->filled('search')) {
            $query->where('nama_jenis_tagihan', 'like', '%' . $request->input('search') . '%');
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->input('bulan'));
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->input('tahun'));
        }

        $jenisTagihans = $query->paginate(10)->appends(request()->query());

        return view('tagihan.index', compact('jenisTagihans'));
    }

    public function create()
    {
        return view('tagihan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jenis_tagihan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bulan' => 'nullable|string',
            'tahun' => 'nullable|digits:4|integer',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_tagihan',
        ]);
        $validated['id_jenis_tagihan'] = 'JNS' . date('Ymd') . mt_rand(1000, 9999);
        JenisTagihan::create($validated);
        return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil ditambahkan.');
    }

    public function show(JenisTagihan $jenisTagihan, Request $request)
    {
        $query = $jenisTagihan->daftarTagihan()->with('santri.kamar');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('santri', function ($q) use ($search) {
                $q->where('nama_santri', 'like', "%{$search}%");
            });
        }
        $daftarTagihan = $query->paginate(15)->appends(request()->query());
        return view('tagihan.show', compact('jenisTagihan', 'daftarTagihan'));
    }

    public function edit(JenisTagihan $jenisTagihan)
    {
        return view('tagihan.edit', compact('jenisTagihan'));
    }

    public function update(Request $request, JenisTagihan $jenisTagihan)
    {
        $validated = $request->validate([
            'nama_jenis_tagihan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bulan' => 'nullable|string',
            'tahun' => 'nullable|digits:4|integer',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_tagihan',
        ]);
        $jenisTagihan->update($validated);
        return redirect()->route('tagihan.show', $jenisTagihan)->with('success', 'Jenis tagihan berhasil diperbarui.');
    }

    public function destroy(JenisTagihan $jenisTagihan)
    {
        $jenisTagihan->delete();
        return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil dihapus.');
    }

    public function assign(JenisTagihan $jenisTagihan, Request $request)
    {
        $query = Santri::with(['pendidikan', 'kelas', 'kamar', 'status']);
        $status_ids = $request->input('status_ids', []);
        if (!empty($status_ids)) {
            $query->whereIn('id_status', $status_ids);
        }
        if ($request->filled('id_pendidikan')) { $query->where('id_pendidikan', $request->input('id_pendidikan')); }
        if ($request->filled('id_kelas')) { $query->where('id_kelas', $request->input('id_kelas')); }
        if ($request->filled('jenis_kelamin')) { $query->where('jenis_kelamin', $request->input('jenis_kelamin')); }
        if ($request->filled('id_kamar')) { $query->where('id_kamar', $request->input('id_kamar')); }
        
        $santris = $query->get()->map(function ($santri) use ($jenisTagihan) {
            $santri->status_tagihan = $santri->daftarTagihan()->where('id_jenis_tagihan', $jenisTagihan->id_jenis_tagihan)->exists() ? 'Tersedia' : 'Belum Tersedia';
            return $santri;
        });

        // Get existing santri IDs that already have this tagihan
        $existingSantriIds = $jenisTagihan->daftarTagihan()->pluck('id_santri')->toArray();

        $statuses = Status::all();
        $pendidikans = Pendidikan::all();
        $kelases = Kelas::all();
        $kamars = Kamar::all();
        return view('tagihan.assign', compact('jenisTagihan', 'santris', 'statuses', 'pendidikans', 'kelases', 'kamars', 'existingSantriIds'));
    }

    public function storeAssignment(Request $request, JenisTagihan $jenisTagihan)
    {
        $validated = $request->validate(['santri_ids' => 'required|array', 'santri_ids.*' => 'exists:santris,id_santri']);
        foreach ($validated['santri_ids'] as $id_santri) {
            DaftarTagihan::updateOrCreate(
                ['id_santri' => $id_santri, 'id_jenis_tagihan' => $jenisTagihan->id_jenis_tagihan],
                ['id_daftar_tagihan' => 'TGH' . date('Ymd') . mt_rand(10000, 99999), 'status_pembayaran' => 'Belum Lunas']
            );
        }
        return redirect()->route('tagihan.show', $jenisTagihan)->with('success', 'Tagihan berhasil diterapkan ke santri terpilih.');
    }

    public function updateSantriBill(Request $request, DaftarTagihan $tagihan)
    {
        if ($tagihan->status_pembayaran === 'Lunas') {
            return back()->withErrors('Tagihan ini sudah lunas.');
        }
        $tagihan->update(['status_pembayaran' => 'Lunas', 'tanggal_bayar' => now(), 'keterangan' => 'Pembayaran Lunas']);
        activity()->log('Melunasi tagihan ' . $tagihan->jenisTagihan->nama_jenis_tagihan . ' untuk santri ' . $tagihan->santri->nama_santri);
        return redirect()->route('tagihan.show', $tagihan->id_jenis_tagihan)->with('success', 'Tagihan berhasil dilunasi.');
    }

    public function destroySantriBill(DaftarTagihan $tagihan)
    {
        $jenisTagihanId = $tagihan->id_jenis_tagihan;
        $tagihan->delete();
        return redirect()->route('tagihan.show', $jenisTagihanId)->with('success', 'Tagihan santri berhasil dihapus.');
    }

    public function cancelPayment(Request $request, DaftarTagihan $tagihan)
    {
        if ($tagihan->status_pembayaran !== 'Lunas') {
            return back()->withErrors('Tagihan ini belum lunas.');
        }
        $request->validate(['alasan_pembatalan' => 'required|string|max:255']);
        $tagihan->update(['status_pembayaran' => 'Belum Lunas', 'tanggal_bayar' => null, 'keterangan' => 'Pembayaran dibatalkan: ' . $request->input('alasan_pembatalan')]);
        activity()->log('Membatalkan pembayaran tagihan #' . $tagihan->id_daftar_tagihan . ' untuk santri ' . $tagihan->santri->nama_santri);
        return back()->with('success', 'Pembayaran berhasil dibatalkan.');
    }

    public function pdfReceipt(DaftarTagihan $tagihan)
    {
        // Load relationships to ensure data is available
        $tagihan->load(['jenisTagihan', 'santri', 'user']);
        $pdf = Pdf::loadView('tagihan.receipt_pdf', compact('tagihan'));
        $namaSantri = $tagihan->santri ? $tagihan->santri->nama_santri : 'santri-tanpa-nama';
        return $pdf->stream('kuitansi-' . $tagihan->id_daftar_tagihan . '-' . $namaSantri . '.pdf');
    }

    public function printReceipt(DaftarTagihan $tagihan)
    {
        // Load relationships to ensure data is available
        $tagihan->load(['jenisTagihan', 'santri', 'user']);
        return view('tagihan.print_receipt', compact('tagihan'));
    }
}