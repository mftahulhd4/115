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

class DaftarTagihanController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisTagihan::query()->withCount(['daftarTagihan as lunas_count' => function ($q) {
            $q->where('status_pembayaran', 'Lunas');
        }, 'daftarTagihan as total_santri']);

        if ($s = $request->search) {
            $query->where('nama_jenis_tagihan', 'like', "%{$s}%");
        }
        if ($b = $request->bulan) {
            $query->where('bulan', $b);
        }
        if ($t = $request->tahun) {
            $query->where('tahun', $t);
        }
        
        $jenisTagihans = $query->latest()->paginate(10);
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
            'deskripsi' => 'nullable|string',
            'bulan' => 'nullable|integer|between:1,12',
            'tahun' => 'nullable|digits:4|integer',
        ]);

        try {
            $id_jenis_tagihan = 'JNS' . now()->format('YmdHis');
            JenisTagihan::create([
                'id_jenis_tagihan' => $id_jenis_tagihan,
                'nama_jenis_tagihan' => $request->nama_jenis_tagihan,
                'deskripsi' => $request->deskripsi,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);
            return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating jenis tagihan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambahkan data.')->withInput();
        }
    }

    public function show(JenisTagihan $jenisTagihan)
    {
        $daftarTagihan = $jenisTagihan->daftarTagihan()->with('santri')->latest('created_at')->paginate(15);
        return view('tagihan.show', compact('jenisTagihan', 'daftarTagihan'));
    }

    public function edit(JenisTagihan $jenisTagihan)
    {
        return view('tagihan.edit', compact('jenisTagihan'));
    }

    public function update(Request $request, JenisTagihan $jenisTagihan)
    {
        $request->validate([
            'nama_jenis_tagihan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'bulan' => 'nullable|integer|between:1,12',
            'tahun' => 'nullable|digits:4|integer',
        ]);
        try {
            $jenisTagihan->update($request->all());
            return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating jenis tagihan: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui jenis tagihan.')->withInput();
        }
    }

    public function destroy(JenisTagihan $jenisTagihan)
    {
        try {
            $jenisTagihan->delete();
            return redirect()->route('tagihan.index')->with('success', 'Jenis tagihan berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return back()->with('error', 'Gagal menghapus! Jenis tagihan ini masih terhubung dengan data tagihan santri.');
            }
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
    
    public function assign(Request $request, JenisTagihan $jenisTagihan)
    {
        $pendidikans = Pendidikan::orderBy('nama_pendidikan')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $query = Santri::with(['pendidikan', 'kelas'])->where('id_status', 1);
        if ($id_pendidikan = $request->input('id_pendidikan')) {
            $query->where('id_pendidikan', $id_pendidikan);
        }
        if ($id_kelas = $request->input('id_kelas')) {
            $query->where('id_kelas', $id_kelas);
        }
        if ($jenis_kelamin = $request->input('jenis_kelamin')) {
            $query->where('jenis_kelamin', $jenis_kelamin);
        }
        $santris = $query->get();
        $existingSantriIds = DaftarTagihan::where('id_jenis_tagihan', $jenisTagihan->id_jenis_tagihan)
            ->pluck('id_santri')
            ->toArray();
        return view('tagihan.assign', compact('jenisTagihan', 'santris', 'existingSantriIds', 'pendidikans', 'kelases'));
    }

    public function storeAssignment(Request $request, JenisTagihan $jenisTagihan)
    {
        $request->validate([
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'exists:santris,id_santri',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_tagihan',
        ]);
        $santriIds = $request->input('santri_ids', []);
        DB::beginTransaction();
        try {
            foreach ($santriIds as $santriId) {
                $tanggal = now()->format('Ymd');
                $latestTagihan = DaftarTagihan::where('id_daftar_tagihan', 'like', "TGH-{$tanggal}-%")->latest('id_daftar_tagihan')->first();
                $nomorUrut = $latestTagihan ? ((int)substr($latestTagihan->id_daftar_tagihan, -4)) + 1 : 1;
                $id_daftar_tagihan = "TGH-{$tanggal}-" . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
                DaftarTagihan::updateOrCreate(
                    ['id_jenis_tagihan' => $jenisTagihan->id_jenis_tagihan, 'id_santri' => $santriId],
                    [
                        'id_daftar_tagihan' => $id_daftar_tagihan,
                        'jumlah_tagihan' => $request->jumlah_tagihan,
                        'tanggal_tagihan' => $request->tanggal_tagihan,
                        'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                        'status_pembayaran' => 'Belum Lunas',
                    ]
                );
            }
            DB::commit();
            return redirect()->route('tagihan.show', $jenisTagihan->id_jenis_tagihan)->with('success', 'Tagihan berhasil diterapkan ke santri terpilih.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in storeAssignment: ' . $e->getMessage());
            return back()->with('error', 'Gagal menerapkan tagihan. Terjadi kesalahan.');
        }
    }

    public function showSantriBill(DaftarTagihan $tagihan)
    {
        $tagihan->load(['santri', 'jenisTagihan']);
        return view('tagihan.show_santri_bill', compact('tagihan'));
    }

    public function editSantriBill(DaftarTagihan $tagihan)
    {
        return view('tagihan.edit_santri_bill', compact('tagihan'));
    }

    public function updateSantriBill(Request $request, DaftarTagihan $tagihan)
    {
        $request->validate(['status_pembayaran' => 'required|in:Lunas,Belum Lunas,Cicil']);
        $tagihan->update(['status_pembayaran' => $request->status_pembayaran]);
        return redirect()->route('tagihan.show', $tagihan->id_jenis_tagihan)->with('success', 'Status pembayaran berhasil diubah.');
    }

    public function destroySantriBill(DaftarTagihan $tagihan)
    {
        $idJenisTagihan = $tagihan->id_jenis_tagihan;
        $tagihan->delete();
        return redirect()->route('tagihan.show', $idJenisTagihan)->with('success', 'Tagihan untuk santri telah dihapus.');
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