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
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $statuses = Status::orderBy('nama_status')->get();

        // Query dasar dengan eager loading untuk relasi
        $query = Santri::with(['pendidikan', 'kelas', 'status']);

        // Terapkan filter jika ada
        if ($request->filled('search')) {
            $query->where('nama_santri', 'like', '%' . $request->search . '%')
                  ->orWhere('id_santri', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status_id')) {
            $query->where('id_status', $request->status_id);
        }
        if ($request->filled('id_pendidikan')) {
            $query->where('id_pendidikan', $request->id_pendidikan);
        }
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        // Ambil data dengan paginasi
        $santris = $query->latest()->paginate(10)->withQueryString();

        return view('santri.index', compact('santris', 'pendidikans', 'kelas', 'statuses'));
    }

    /**
     * Menampilkan form untuk menambah santri baru.
     */
    public function create()
    {
        // Mengirim data master ke view
        return view('santri.create', [
            'pendidikans' => Pendidikan::orderBy('nama_pendidikan')->get(),
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'statuses' => Status::orderBy('nama_status')->get(),
        ]);
    }

    /**
     * Menyimpan santri baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
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

        // Membuat ID Santri unik
        $tahunMasuk = substr($request->tahun_masuk, -2); // Ambil 2 digit terakhir
        $santriTerakhir = Santri::where('id_santri', 'like', "NA{$tahunMasuk}%")->orderBy('id_santri', 'desc')->first();
        $nomorUrut = $santriTerakhir ? ((int)substr($santriTerakhir->id_santri, -4)) + 1 : 1;
        $validated['id_santri'] = "NA" . $tahunMasuk . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

        // Proses upload foto
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
        // Memastikan relasi sudah ter-load
        $santri->load(['pendidikan', 'kelas', 'status']);
        return view('santri.show', compact('santri'));
    }

    /**
     * Menampilkan form untuk mengedit data santri.
     */
    public function edit(Santri $santri)
    {
        return view('santri.edit', [
            'santri' => $santri,
            'pendidikans' => Pendidikan::orderBy('nama_pendidikan')->get(),
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'statuses' => Status::orderBy('nama_status')->get(),
        ]);
    }

    /**
     * Mengupdate data santri di database.
     */
    public function update(Request $request, Santri $santri)
    {
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

        // Proses upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($santri->foto) {
                Storage::delete('public/fotos/' . $santri->foto);
            }
            $path = $request->file('foto')->store('public/fotos');
            $validated['foto'] = basename($path);
        }

        $santri->update($validated);

        return redirect()->route('santri.show', $santri->id_santri)->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Menghapus data santri dari database.
     */
    public function destroy(Santri $santri)
    {
        // Hapus foto dari storage jika ada
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

    public function print(Santri $santri)
    {
        $santri->load(['pendidikan', 'kelas', 'status']);
        return view('santri.print', compact('santri'));
    }
}

