<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::query();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status_santri')) {
            $query->where('status_santri', $request->status_santri);
        }
        if ($request->filled('pendidikan')) {
            $query->where('pendidikan', $request->pendidikan);
        }
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $santris = $query->latest()->paginate(10)->withQueryString();

        $statusOptions = Santri::select('status_santri')->distinct()->whereNotNull('status_santri')->pluck('status_santri');
        $pendidikanOptions = Santri::select('pendidikan')->distinct()->whereNotNull('pendidikan')->orderBy('pendidikan')->pluck('pendidikan');
        $kelasOptions = Santri::select('kelas')->distinct()->whereNotNull('kelas')->orderBy('kelas')->pluck('kelas');

        return view('santri.index', compact('santris', 'pendidikanOptions', 'statusOptions', 'kelasOptions'));
    }

    public function create()
    {
        return view('santri.create');
    }

    public function store(Request $request)
    {
        // SEMUA 'Id_santri' DIUBAH MENJADI 'id_santri'
        $validated = $request->validate([
            'id_santri' => 'nullable|string|max:20|unique:santris,id_santri',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'pendidikan' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:50',
            'kamar' => 'nullable|string|max:50',
            'nama_bapak' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nomer_orang_tua' => 'required|string|max:15',
            'status_santri' => 'required|in:Aktif,Baru,Pengurus,Alumni',
            'tahun_masuk' => 'required|digits:4|integer|min:1900',
            'tahun_keluar' => 'nullable|digits:4|integer|min:1900|after_or_equal:tahun_masuk',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if (empty($validated['id_santri'])) {
            $tahunMasuk = $validated['tahun_masuk'];
            $lastSantri = Santri::where(DB::raw('SUBSTRING(id_santri, 1, 4)'), $tahunMasuk)
                                ->orderBy('id_santri', 'desc')
                                ->first();
            
            $nomorUrut = 1;
            if ($lastSantri) {
                $lastNomorUrut = (int) substr($lastSantri->id_santri, 4, 3);
                $nomorUrut = $lastNomorUrut + 1;
            }
            
            $validated['id_santri'] = $tahunMasuk . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
        }

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
        return view('santri.edit', compact('santri'));
    }

    public function update(Request $request, Santri $santri)
    {
        // SEMUA 'Id_santri' DIUBAH MENJADI 'id_santri'
        $validated = $request->validate([
            'id_santri' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('santris', 'id_santri')->ignore($santri->id),
            ],
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'pendidikan' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:50',
            'kamar' => 'nullable|string|max:50',
            'nama_bapak' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nomer_orang_tua' => 'required|string|max:15',
            'status_santri' => 'required|in:Aktif,Baru,Pengurus,Alumni',
            'tahun_masuk' => 'required|digits:4|integer|min:1900',
            'tahun_keluar' => 'nullable|digits:4|integer|min:1900|after_or_equal:tahun_masuk',
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
        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Santri $santri)
    {
        if ($santri->foto) {
            Storage::delete('public/fotos/' . $santri->foto);
        }
        $santri->delete();
        return redirect()->route('santri.index')->with('success', 'Data santri berhasil dihapus.');
    }
    
    public function cetakDetailPdf(Santri $santri)
    {
        $pdf = Pdf::loadView('santri.detail_pdf', compact('santri'));
        return $pdf->download('biodata-santri-' . $santri->nama_lengkap . '.pdf');
    }

    public function cetakBrowser(Santri $santri)
    {
        return view('santri.print', compact('santri'));
    }
}