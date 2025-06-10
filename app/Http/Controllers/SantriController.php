<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PDF; // <--- PASTIKAN BARIS INI ADA DAN BENAR (INI YANG PALING PENTING UNTUK ERROR PDF)
use Illuminate\Contracts\View\View; // Untuk return type view()
use Illuminate\Http\Response; // Untuk return type PDF stream/download
use Illuminate\Http\RedirectResponse; // Untuk return type redirect()

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View // Mengubah return type ke View
    {
        $searchKeyword = $request->input('search');
        $query = Santri::query();

        if ($searchKeyword) {
            $query->where(function($q) use ($searchKeyword) {
                $q->where('nama_lengkap', 'like', "%{$searchKeyword}%")
                  ->orWhere('kamar', 'like', "%{$searchKeyword}%")
                  ->orWhere('pendidikan_terakhir', 'like', "%{$searchKeyword}%")
                  ->orWhere('status_santri', 'like', "%{$searchKeyword}%")
                  ->orWhere('tahun_masuk', 'like', "%{$searchKeyword}%")
                  ->orWhere('tahun_keluar', 'like', "%{$searchKeyword}%");
            });
        }
        $santris = $query->orderBy('nama_lengkap', 'asc')->paginate(10)->withQueryString();
        return view('santri.index', compact('santris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View // Mengubah return type ke View
    {
        return view('santri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse // Mengubah return type ke RedirectResponse
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'kamar' => 'nullable|string|max:50',
            'tahun_masuk' => 'required|string|digits:4|numeric',
            'tahun_keluar' => 'nullable|string|digits:4|numeric|gte:tahun_masuk',
            'nama_orang_tua' => 'required|string|max:255',
            'nomor_telepon_orang_tua' => 'nullable|string|max:20',
            'status_santri' => ['required', Rule::in(['Aktif', 'Alumni', 'Pengurus', 'Baru'])],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],[
            'tahun_keluar.gte' => 'Tahun keluar harus sama atau setelah tahun masuk.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpeg, png, jpg, gif, atau svg.',
            'foto.max' => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        try {
            $santri = new Santri();
            $dataToSave = $request->except('foto');
            $santri->fill($dataToSave);

            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                try {
                    $namaFileFoto = time() . '_' . $request->file('foto')->getClientOriginalName();
                    $pathFoto = $request->file('foto')->storeAs('fotos_santri', $namaFileFoto, 'public');
                    $santri->foto = $pathFoto;
                    Log::info('Foto santri berhasil diunggah (store): ' . $pathFoto);
                } catch (\Exception $fileException) {
                    Log::error('Gagal mengunggah foto santri (store): ' . $fileException->getMessage(), ['exception' => $fileException]);
                }
            }

            $santri->save();
            return redirect()->route('santri.index')->with('success', 'Data santri baru berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data santri (store): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data santri. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Santri  $santri
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Santri $santri): View // Mengubah return type ke View
    {
        return view('santri.show', compact('santri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Santri $santri): View // Mengubah return type ke View
    {
        return view('santri.edit', compact('santri'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Santri $santri): RedirectResponse // Mengubah return type ke RedirectResponse
    {
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'kamar' => 'nullable|string|max:50',
            'tahun_masuk' => 'required|string|digits:4|numeric',
            'tahun_keluar' => 'nullable|string|digits:4|numeric|gte:tahun_masuk',
            'nama_orang_tua' => 'required|string|max:255',
            'nomor_telepon_orang_tua' => 'nullable|string|max:20',
            'status_santri' => ['required', Rule::in(['Aktif', 'Alumni', 'Pengurus', 'Baru'])],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],[
            'tahun_keluar.gte' => 'Tahun keluar harus sama atau setelah tahun masuk.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpeg, png, jpg, gif, atau svg.',
            'foto.max' => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        try {
            $dataToUpdate = $request->except('foto');
            $santri->fill($dataToUpdate);

            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                if ($santri->foto) {
                    try {
                        if (Storage::disk('public')->exists($santri->foto)) {
                            Storage::disk('public')->delete($santri->foto);
                            Log::info('Foto lama santri berhasil dihapus (update): ' . $santri->foto);
                        }
                    } catch (\Exception $deleteException) {
                        Log::error('Gagal menghapus foto lama santri (update): ' . $deleteException->getMessage(), ['path' => $santri->foto, 'exception' => $deleteException]);
                    }
                }
                try {
                    $namaFileFoto = time() . '_' . $request->file('foto')->getClientOriginalName();
                    $pathFoto = $request->file('foto')->storeAs('fotos_santri', $namaFileFoto, 'public');
                    $santri->foto = $pathFoto;
                    Log::info('Foto santri berhasil diperbarui dan diunggah (update): ' . $pathFoto);
                } catch (\Exception $fileException) {
                    Log::error('Gagal mengunggah foto baru santri (update): ' . $fileException->getMessage(), ['exception' => $fileException]);
                }
            }

            $santri->save();
            return redirect()->route('santri.index')->with('success', 'Data santri berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate data santri (update): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data santri. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Santri $santri): RedirectResponse // Mengubah return type ke RedirectResponse
    {
        try {
            $namaSantri = $santri->nama_lengkap;
            $fotoPath = $santri->foto;

            $santri->delete();
            Log::info('Data santri berhasil dihapus dari database: ' . $namaSantri);

            if ($fotoPath) {
                try {
                    if (Storage::disk('public')->exists($fotoPath)) {
                        Storage::disk('public')->delete($fotoPath);
                        Log::info('Foto santri berhasil dihapus dari storage (destroy): ' . $fotoPath);
                    }
                } catch (\Exception $deleteException) {
                    Log::error('Gagal menghapus foto santri dari storage (destroy): ' . $deleteException->getMessage(), ['path' => $fotoPath, 'exception' => $deleteException]);
                }
            }
            return redirect()->route('santri.index')->with('success', 'Data santri "' . $namaSantri . '" berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data santri (destroy): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('santri.index')->with('error', 'Terjadi kesalahan saat menghapus data santri.');
        }
    }

    /**
     * Generate PDF for the specified santri.
     *
     * @param  \App\Models\Santri  $santri
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function printPDF(Santri $santri): Response|RedirectResponse // Mengubah return type agar sesuai
    {
        try {
            $pdf = PDF::loadView('santri.pdf', compact('santri'));
            $fileName = 'data_santri_' . Str::slug($santri->nama_lengkap, '_') . '_' . $santri->id . '.pdf';
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF santri: ' . $e->getMessage(), ['santri_id' => $santri->id, 'exception' => $e]);
            return redirect()->back()->with('error', 'Gagal membuat dokumen PDF. Pesan Error: ' . $e->getMessage());
        }
    }
}