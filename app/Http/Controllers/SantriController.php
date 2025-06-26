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
    /**
     * Display a listing of the resource.
     * Method ini akan kita perbarui di tahap selanjutnya.
     */
    public function index(Request $request)
    {
        $query = Santri::query();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status_santri')) {
            $query->where('status_santri', $request->status_santri);
        }

        $santris = $query->orderBy('nama_lengkap', 'asc')->paginate(10);
        return view('santri.index', compact('santris'));
    }

    /**
     * Show the form for creating a new resource.
     * Method ini memanggil view yang akan kita perbarui di Langkah 2.
     */
    public function create()
    {
        return view('santri.create');
    }

    /**
     * Store a newly created resource in storage.
     * Logika validasi dan penyimpanan data baru ada di sini.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $validated = $request->validate([
            'Id_santri' => 'nullable|string|max:20|unique:santris,Id_santri',
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
        
        // 2. Generate Id_santri otomatis jika user tidak mengisinya
        if (empty($validated['Id_santri'])) {
            $tahunMasuk = $validated['tahun_masuk'];
            // Cari nomor urut terakhir di tahun yang sama
            $lastSantri = Santri::where(DB::raw('SUBSTRING(Id_santri, 1, 4)'), $tahunMasuk)
                                ->orderBy('Id_santri', 'desc')
                                ->first();
            
            $nomorUrut = 1;
            if ($lastSantri) {
                // Ambil 3 digit terakhir dari ID, ubah ke integer, lalu tambah 1
                $lastNomorUrut = (int) substr($lastSantri->Id_santri, 4, 3);
                $nomorUrut = $lastNomorUrut + 1;
            }
            
            // Gabungkan tahun masuk dengan nomor urut (format 3 digit)
            $validated['Id_santri'] = $tahunMasuk . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);
        }

        // 3. Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/fotos');
            $validated['foto'] = basename($path);
        }

        // 4. Simpan data ke database
        Santri::create($validated);

        // 5. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('santri.index')->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Santri $santri)
    {
        return view('santri.show', compact('santri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Santri $santri)
    {
        return view('santri.edit', compact('santri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Santri $santri)
    {
        // Logika untuk update akan kita sesuaikan di tahap selanjutnya
        $validated = $request->validate([
            'Id_santri' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('santris', 'Id_santri')->ignore($santri->id),
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Santri $santri)
    {
        if ($santri->foto) {
            Storage::delete('public/fotos/' . $santri->foto);
        }
        $santri->delete();
        return redirect()->route('santri.index')->with('success', 'Data santri berhasil dihapus.');
    }
    
    /**
     * Print PDF.
     */
    public function cetakDetailPdf(Santri $santri)
    {
        $pdf = Pdf::loadView('santri.detail_pdf', compact('santri'));
        return $pdf->download('biodata-santri-' . $santri->nama_lengkap . '.pdf');
    }

    /**
     * Print Browser.
     */
    public function cetakBrowser(Santri $santri)
    {
        return view('santri.print', compact('santri'));
    }
}