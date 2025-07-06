<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendidikan;
use App\Models\Santri;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SantriController extends Controller
{
    /**
     * Menampilkan daftar semua santri dengan filter.
     */
    public function index(Request $request)
    {
        // Mengambil data master untuk filter dropdown
        $pendidikans = Pendidikan::orderBy('nama_pendidikan')->get();
        // Menggunakan nama variabel yang konsisten: $kelases
        $kelases = Kelas::orderBy('nama_kelas')->get(); 
        $statuses = Status::orderBy('nama_status')->get();

        $query = Santri::with(['pendidikan', 'kelas', 'status']);

        if ($request->filled('search')) {
            $query->where('nama_santri', 'like', '%' . $request->search . '%')
                  ->orWhere('id_santri', 'like', '%' . $request->search . '%');
        }
        // Menggunakan 'id_status' sesuai name di form
        if ($request->filled('id_status')) { 
            $query->where('id_status', $request->id_status);
        }
        if ($request->filled('id_pendidikan')) {
            $query->where('id_pendidikan', $request->id_pendidikan);
        }
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        $santris = $query->latest()->paginate(10)->withQueryString();
        
        // Mengganti nama variabel 'kelas' menjadi 'kelases' agar konsisten
        return view('santri.index', compact('santris', 'pendidikans', 'kelases', 'statuses'));
    }

    /**
     * Menampilkan form untuk menambah santri baru.
     */
    public function create()
    {
        $this->authorize('manage-santri'); // Proteksi Gate

        return view('santri.create', [
            'pendidikans' => Pendidikan::orderBy('nama_pendidikan')->get(),
            'kelases' => Kelas::orderBy('nama_kelas')->get(),
            'statuses' => Status::orderBy('nama_status')->get(),
        ]);
    }

    /**
     * Menyimpan santri baru ke database.
     */
    public function store(Request $request)
    {
        $this->authorize('manage-santri'); // Proteksi Gate

        $validated = $request->validate([
            'nama_santri' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nomor_hp_wali' => 'required|string|max:20',
            'tahun_masuk' => 'required|digits:4|integer|min:1900|max:' . (date('Y')),
            'id_pendidikan' => 'required|exists:pendidikans,id_pendidikan',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_status' => 'required|exists:statuses,id_status',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $tahunMasuk = substr($request->tahun_masuk, -2);
        $santriTerakhir = Santri::where('id_santri', 'like', "NA{$tahunMasuk}%")->orderBy('id_santri', 'desc')->first();
        $nomorUrut = $santriTerakhir ? ((int)substr($santriTerakhir->id_santri, -4)) + 1 : 1;
        $validated['id_santri'] = "NA" . $tahunMasuk . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/fotos');
            $validated['foto'] = basename($path);
        }

        Santri::create($validated);

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik santri.
     */
    public function show(Santri $santri)
    {
        $santri->load(['pendidikan', 'kelas', 'status']);
        return view('santri.show', compact('santri'));
    }

    /**
     * Menampilkan form untuk mengedit data santri.
     */
    public function edit(Santri $santri)
    {
        $this->authorize('manage-santri'); // Proteksi Gate

        return view('santri.edit', [
            'santri' => $santri,
            'pendidikans' => Pendidikan::orderBy('nama_pendidikan')->get(),
            'kelases' => Kelas::orderBy('nama_kelas')->get(),
            'statuses' => Status::orderBy('nama_status')->get(),
        ]);
    }

    /**
     * Mengupdate data santri di database.
     */
    public function update(Request $request, Santri $santri)
    {
        $this->authorize('manage-santri'); // Proteksi Gate

        $validated = $request->validate([
            'nama_santri' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nomor_hp_wali' => 'required|string|max:20',
            // tahun_masuk sengaja tidak divalidasi karena readonly
            'id_pendidikan' => 'required|exists:pendidikans,id_pendidikan',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'id_status' => 'required|exists:statuses,id_status',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($santri->foto) {
                Storage::delete('public/fotos/' . $santri->foto);
            }
            $path = $request->file('foto')->store('public/fotos');
            $validated['foto'] = basename($path);
        }

        // Hapus tahun_masuk dari data yang akan diupdate untuk keamanan
        unset($validated['tahun_masuk']);

        $santri->update($validated);

        return redirect()->route('santri.show', $santri->id_santri)->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Menghapus data santri dari database.
     */
    public function destroy(Santri $santri)
    {
        $this->authorize('manage-santri'); // Proteksi Gate

        if ($santri->foto) {
            Storage::delete('public/fotos/' . $santri->foto);
        }
        
        $santri->delete();

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil dihapus.');
    }

    /**
     * Membuat dan menampilkan biodata santri dalam format PDF.
     */
    public function detailPdf(Santri $santri)
    {
        $santri->load(['pendidikan', 'kelas', 'status']);
        $pdf = Pdf::loadView('santri.detail_pdf', compact('santri'));
        return $pdf->stream('biodata-santri-' . $santri->nama_santri . '.pdf');
    }

    /**
     * Menampilkan halaman print-friendly.
     */
    public function print(Santri $santri)
    {
        $santri->load(['pendidikan', 'kelas', 'status']);
        return view('santri.print', compact('santri'));
    }
}