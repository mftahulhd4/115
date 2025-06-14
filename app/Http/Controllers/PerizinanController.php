<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PerizinanController extends Controller
{
    public function index(Request $request)
    {
        $query = Perizinan::with('santri')->latest();

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
        $years = Perizinan::selectRaw('YEAR(tanggal_izin) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        
        return view('perizinan.index', compact('perizinans', 'years'));
    }

    public function create()
    {
        return view('perizinan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kepentingan_izin' => 'required|string',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_izin',
            'keterangan_tambahan' => 'nullable|string',
        ]);
        Perizinan::create($request->all() + ['status' => 'diproses']);
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil ditambahkan.');
    }


    public function show(Perizinan $perizinan)
    {
        return view('perizinan.show', compact('perizinan'));
    }

    public function edit(Perizinan $perizinan)
    {
        $santri = $perizinan->santri;
        return view('perizinan.edit', compact('perizinan', 'santri'));
    }

    public function update(Request $request, Perizinan $perizinan)
    {
        $request->validate([
            'kepentingan_izin' => 'required|string',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_izin',
            'keterangan_tambahan' => 'nullable|string',
        ]);
        $perizinan->update($request->except(['_token', '_method', 'santri_id']));
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil diperbarui.');
    }

    public function destroy(Perizinan $perizinan)
    {
        $perizinan->delete();
        return redirect()->route('perizinan.index')->with('success', 'Data perizinan berhasil dihapus.');
    }

    public function cetakPdf()
    {
        $perizinans = Perizinan::with('santri')->get();
        $pdf = Pdf::loadView('perizinan.pdf', ['perizinans' => $perizinans]);
        return $pdf->download('laporan-perizinan.pdf');
    }

    public function cetakBrowser(Perizinan $perizinan)
    {
        return view('perizinan.print', compact('perizinan'));
    }

    public function cetakDetailPdf(Perizinan $perizinan)
    {
        $pdf = Pdf::loadView('perizinan.detail_pdf', ['perizinan' => $perizinan]);
        $fileName = 'detail-izin-' . Str::slug($perizinan->santri->nama_lengkap) . '-' . $perizinan->id . '.pdf';
        return $pdf->download($fileName);
    }
    
    public function searchSantri(Request $request)
    {
        $search = $request->get('term');
        // Pencarian tidak lagi dibatasi status 'Aktif'
        $santris = Santri::where('nama_lengkap', 'LIKE', '%' . $search . '%')
                         ->limit(10)
                         ->get(['id', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'pendidikan', 'foto']);
        return response()->json($santris);
    }
}