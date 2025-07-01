<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('id_santri', 'like', "%{$search}%");
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

        $santris = $query->latest()->paginate(10);
        return view('santri.index', compact('santris'));
    }

    public function create()
    {
        return view('santri.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'alamat' => 'required|string',
            'pendidikan' => ['required', Rule::in(['Mts Nurul Amin', 'MA Nurul Amin'])],
            'kelas' => ['required', Rule::in(['VII', 'VIII', 'IX', 'X', 'XI', 'XII'])],
            'kamar' => 'required|string|max:50',
            'tahun_masuk' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'nama_bapak' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nomer_orang_tua' => 'required|string|max:20',
            'status_santri' => ['required', Rule::in(['Santri Baru', 'Santri Aktif', 'Pengurus', 'Alumni'])],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $santri_terakhir = Santri::where('tahun_masuk', $request->tahun_masuk)->orderBy('id_santri', 'desc')->first();
        $nomor_urut = $santri_terakhir ? (int)substr($santri_terakhir->id_santri, 4) + 1 : 1;
        $id_santri = $request->tahun_masuk . str_pad($nomor_urut, 3, '0', STR_PAD_LEFT);

        $validated['id_santri'] = $id_santri;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->storeAs('public/fotos', $id_santri . '.' . $request->file('foto')->extension());
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
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'alamat' => 'required|string',
            'pendidikan' => ['required', Rule::in(['Mts Nurul Amin', 'MA Nurul Amin'])],
            'kelas' => ['required', Rule::in(['VII', 'VIII', 'IX', 'X', 'XI', 'XII'])],
            'kamar' => 'required|string|max:50',
            'tahun_masuk' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'nama_bapak' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'nomer_orang_tua' => 'required|string|max:20',
            'status_santri' => ['required', Rule::in(['Santri Baru', 'Santri Aktif', 'Pengurus', 'Alumni'])],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($santri->foto) {
                Storage::delete('public/fotos/' . $santri->foto);
            }
            $path = $request->file('foto')->storeAs('public/fotos', $santri->id_santri . '.' . $request->file('foto')->extension());
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
    
    public function detailPdf(Santri $santri)
    {
        $pdf = Pdf::loadView('santri.detail_pdf', compact('santri'));
        return $pdf->stream('detail-santri-' . $santri->id_santri . '.pdf');
    }

    public function print(Santri $santri)
    {
        return view('santri.print', compact('santri'));
    }

    
}