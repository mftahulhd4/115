<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Pendidikan;
use App\Models\Kelas;
use App\Models\Kamar;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Exports\SantriExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SantriController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-santri')->except(['index', 'show', 'detailPdf', 'print']);
    }

    public function index(Request $request)
    {
        $query = Santri::with(['pendidikan', 'kelas', 'status', 'kamar'])->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_santri', 'like', "%{$search}%")
                  ->orWhere('id_santri', 'like', "%{$search}%");
            });
        }
        if ($request->filled('id_status')) { $query->where('id_status', $request->input('id_status')); }
        if ($request->filled('id_pendidikan')) { $query->where('id_pendidikan', $request->input('id_pendidikan')); }
        if ($request->filled('id_kelas')) { $query->where('id_kelas', $request->input('id_kelas')); }
        if ($request->filled('jenis_kelamin')) { $query->where('jenis_kelamin', $request->input('jenis_kelamin')); }
        if ($request->filled('id_kamar')) { $query->where('id_kamar', $request->input('id_kamar')); }

        $santris = $query->paginate(15)->appends(request()->query());

        $pendidikans = Pendidikan::orderBy('nama_pendidikan')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $statuses = Status::orderBy('nama_status')->get();
        $kamars = Kamar::orderBy('nama_kamar')->get();

        return view('santri.index', compact('santris', 'pendidikans', 'kelases', 'statuses', 'kamars'));
    }

    public function create()
    {
        $pendidikans = Pendidikan::all();
        $kelases = Kelas::all();
        $statuses = Status::all();
        $kamars = Kamar::all();
        return view('santri.create', compact('pendidikans', 'kelases', 'statuses', 'kamars'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_santri' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nomor_hp_wali' => 'required|string|max:15',
            'tahun_masuk' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'id_pendidikan' => 'required|exists:pendidikans,id_pendidikan',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_status' => 'required|exists:statuses,id_status',
            'id_kamar' => 'nullable|exists:kamars,id_kamar',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tahun_masuk = $validated['tahun_masuk'];
        $lastSantri = Santri::where('tahun_masuk', $tahun_masuk)->orderBy('id_santri', 'desc')->first();
        
        $nextNumber = 1;
        if ($lastSantri) {
            $lastNumber = (int) substr($lastSantri->id_santri, -4);
            $nextNumber = $lastNumber + 1;
        }
        $validated['id_santri'] = 'NA' . $tahun_masuk . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/fotos');
            $validated['foto'] = basename($path);
        }

        Santri::create($validated);
        return redirect()->route('santri.index')->with('success', 'Data santri berhasil ditambahkan.');
    }

    public function show(Santri $santri)
    {
        return view('santri.show', compact('santri'));
    }

    public function edit(Santri $santri)
    {
        $pendidikans = Pendidikan::all();
        $kelases = Kelas::all();
        $statuses = Status::all();
        $kamars = Kamar::all();
        return view('santri.edit', compact('santri', 'pendidikans', 'kelases', 'statuses', 'kamars'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nama_santri' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nomor_hp_wali' => 'required|string|max:15',
            'tahun_masuk' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'id_pendidikan' => 'required|exists:pendidikans,id_pendidikan',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_status' => 'required|exists:statuses,id_status',
            'id_kamar' => 'nullable|exists:kamars,id_kamar',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($santri->foto) {
                Storage::delete('public/fotos/' . $santri->foto);
            }
            $path = $request->file('foto')->store('public/fotos');
            $validated['foto'] = basename($path);
        }

        $santri->update($validated);
        return redirect()->route('santri.show', $santri)->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        if ($santri->foto) {
            Storage::delete('public/fotos/' . $santri->foto);
        }
        $santri->delete();
        return redirect()->route('santri.index')->with('success', 'Data santri berhasil dihapus.');
    }
    
    // ===============================================
    //           BAGIAN YANG DIPERBAIKI
    // ===============================================
    public function exportExcel(Request $request)
    {
        // Mengambil setiap nilai filter dari request
        $search = $request->input('search');
        $id_status = $request->input('id_status');
        $id_pendidikan = $request->input('id_pendidikan');
        $id_kelas = $request->input('id_kelas');
        $jenis_kelamin = $request->input('jenis_kelamin');
        $id_kamar = $request->input('id_kamar');

        // Mengirimkan setiap nilai sebagai argumen terpisah
        return Excel::download(new SantriExport($search, $id_status, $id_pendidikan, $id_kelas, $jenis_kelamin, $id_kamar), 'daftar-santri.xlsx');
    }
    // ===============================================
    //           AKHIR PERBAIKAN
    // ===============================================
    
    public function detailPdf(Santri $santri)
    {
        $pdf = Pdf::loadView('santri.detail_pdf', compact('santri'));
        return $pdf->stream('biodata-' . $santri->nama_santri . '.pdf');
    }

    public function print(Santri $santri)
    {
        return view('santri.print', compact('santri'));
    }
}