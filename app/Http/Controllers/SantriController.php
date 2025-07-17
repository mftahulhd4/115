<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Kelas;
use App\Models\Pendidikan;
use App\Models\Santri;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Pastikan ini ada
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SantriExport;
use Maatwebsite\Excel\Facades\Excel;

class SantriController extends Controller
{
    /**
     * Menampilkan daftar semua santri dengan filter.
     */
    public function index(Request $request)
    {
        $pendidikans = Pendidikan::orderBy('nama_pendidikan')->get();
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $statuses = Status::orderBy('nama_status')->get();
        $kamars = Kamar::orderBy('nama_kamar')->get();
        $query = Santri::with(['pendidikan', 'kelas', 'status', 'kamar']);
        if ($request->filled('search')) { $query->where('nama_santri', 'like', '%' . $request->search . '%')->orWhere('id_santri', 'like', '%' . $request->search . '%'); }
        if ($request->filled('id_status')) { $query->where('id_status', $request->id_status); }
        if ($request->filled('id_pendidikan')) { $query->where('id_pendidikan', $request->id_pendidikan); }
        if ($request->filled('id_kelas')) { $query->where('id_kelas', $request->id_kelas); }
        if ($request->filled('jenis_kelamin')) { $query->where('jenis_kelamin', $request->jenis_kelamin); }
        if ($request->filled('id_kamar')) { $query->where('id_kamar', $request->id_kamar); }
        $santris = $query->latest()->paginate(10)->withQueryString();
        return view('santri.index', compact('santris', 'pendidikans', 'kelases', 'statuses', 'kamars'));
    }

    /**
     * Menampilkan form untuk menambah santri baru.
     */
    public function create()
    {
        $this->authorize('manage-santri');
        return view('santri.create', [ 'pendidikans' => Pendidikan::orderBy('nama_pendidikan')->get(), 'kelases' => Kelas::orderBy('nama_kelas')->get(), 'statuses' => Status::orderBy('nama_status')->get(), 'kamars' => Kamar::orderBy('nama_kamar')->get(), ]);
    }

    /**
     * Menyimpan santri baru ke database dan mencatat aktivitas.
     */
    public function store(Request $request)
    {
        $this->authorize('manage-santri');
        $validated = $request->validate([ 'nama_santri' => 'required|string|max:255', 'tempat_lahir' => 'required|string|max:255', 'tanggal_lahir' => 'required|date', 'jenis_kelamin' => 'required|in:Laki-laki,Perempuan', 'alamat' => 'required|string', 'nama_ayah' => 'required|string|max:255', 'nama_ibu' => 'required|string|max:255', 'nomor_hp_wali' => 'required|string|max:20', 'tahun_masuk' => 'required|digits:4|integer|min:1900|max:' . (date('Y')), 'id_pendidikan' => 'required|exists:pendidikans,id_pendidikan', 'id_kelas' => 'required|exists:kelas,id_kelas', 'id_status' => 'required|exists:statuses,id_status', 'id_kamar' => 'nullable|exists:kamars,id_kamar', 'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', ]);
        $tahunMasuk = substr($request->tahun_masuk, -2); $santriTerakhir = Santri::where('id_santri', 'like', "NA{$tahunMasuk}%")->orderBy('id_santri', 'desc')->first(); $nomorUrut = $santriTerakhir ? ((int)substr($santriTerakhir->id_santri, -4)) + 1 : 1; $validated['id_santri'] = "NA" . $tahunMasuk . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
        if ($request->hasFile('foto')) { $path = $request->file('foto')->store('public/fotos'); $validated['foto'] = basename($path); }

        $santri = Santri::create($validated);

        // === PENCATATAN LOG SEDERHANA (CREATE) ===
        $user = auth()->user();
        Log::info("CREATE SANTRI: Santri baru \"{$santri->nama_santri}\" (ID: {$santri->id_santri}) ditambahkan oleh {$user->name} (ID: {$user->id}).");
        // === AKHIR PENCATATAN ===

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik santri.
     */
    public function show(Santri $santri)
    {
        $santri->load(['pendidikan', 'kelas', 'status', 'kamar']);
        return view('santri.show', compact('santri'));
    }

    /**
     * Menampilkan form untuk mengedit data santri.
     */
    public function edit(Santri $santri)
    {
        $this->authorize('manage-santri');
        return view('santri.edit', [ 'santri' => $santri, 'pendidikans' => Pendidikan::orderBy('nama_pendidikan')->get(), 'kelases' => Kelas::orderBy('nama_kelas')->get(), 'statuses' => Status::orderBy('nama_status')->get(), 'kamars' => Kamar::orderBy('nama_kamar')->get(), ]);
    }

    /**
     * Mengupdate data santri di database dan mencatat aktivitas.
     */
    public function update(Request $request, Santri $santri)
    {
        $this->authorize('manage-santri');
        $validated = $request->validate([ 'nama_santri' => 'required|string|max:255', 'tempat_lahir' => 'required|string|max:255', 'tanggal_lahir' => 'required|date', 'jenis_kelamin' => 'required|in:Laki-laki,Perempuan', 'alamat' => 'required|string', 'nama_ayah' => 'required|string|max:255', 'nama_ibu' => 'required|string|max:255', 'nomor_hp_wali' => 'required|string|max:20', 'id_pendidikan' => 'required|exists:pendidikans,id_pendidikan', 'id_kelas' => 'required|exists:kelas,id_kelas', 'id_status' => 'required|exists:statuses,id_status', 'id_kamar' => 'nullable|exists:kamars,id_kamar', 'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', ]);
        if ($request->hasFile('foto')) { if ($santri->foto) { Storage::delete('public/fotos/' . $santri->foto); } $path = $request->file('foto')->store('public/fotos'); $validated['foto'] = basename($path); }
        unset($validated['tahun_masuk']);

        $santri->update($validated);
        
        // === PENCATATAN LOG SEDERHANA (UPDATE) ===
        $user = auth()->user();
        Log::info("UPDATE SANTRI: Data santri \"{$santri->nama_santri}\" (ID: {$santri->id_santri}) diperbarui oleh {$user->name} (ID: {$user->id}).");
        // === AKHIR PENCATATAN ===

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Menghapus data santri dari database dan mencatat aktivitas.
     */
    public function destroy(Santri $santri)
    {
        $this->authorize('manage-santri');
        $namaSantri = $santri->nama_santri;
        $idSantri = $santri->id_santri;
        if ($santri->foto) {
            Storage::delete('public/fotos/' . $santri->foto);
        }
        
        $santri->delete();

        // === PENCATATAN LOG SEDERHANA (DELETE) ===
        $user = auth()->user();
        Log::info("DELETE SANTRI: Data santri \"{$namaSantri}\" (ID: {$idSantri}) dihapus oleh {$user->name} (ID: {$user->id}).");
        // === AKHIR PENCATATAN ===

        return redirect()->route('santri.index')->with('success', 'Data santri berhasil dihapus.');
    }

    /**
     * Membuat dan menampilkan biodata santri dalam format PDF.
     */
    public function detailPdf(Santri $santri)
    {
        $santri->load(['pendidikan', 'kelas', 'status', 'kamar']);
        $pdf = Pdf::loadView('santri.detail_pdf', compact('santri'));
        return $pdf->stream('biodata-santri-' . $santri->nama_santri . '.pdf');
    }

    /**
     * Menampilkan halaman print-friendly.
     */
    public function print(Santri $santri)
    {
        $santri->load(['pendidikan', 'kelas', 'status', 'kamar']);
        return view('santri.print', compact('santri'));
    }

    /**
     * Mengekspor data ke Excel dengan mempertahankan filter.
     */
    public function exportExcel(Request $request)
    {
        $search = $request->get('search');
        $statusId = $request->get('id_status');
        $pendidikanId = $request->get('id_pendidikan');
        $kelasId = $request->get('id_kelas');
        $jenisKelamin = $request->get('jenis_kelamin');
        $kamarId = $request->get('id_kamar');
        $fileName = 'daftar-santri-' . date('Y-m-d-His') . '.xlsx';
        return Excel::download(new SantriExport($search, $statusId, $pendidikanId, $kelasId, $jenisKelamin, $kamarId), $fileName);
    }
}