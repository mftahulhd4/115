<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Untuk Str::slug
use PDF; // Untuk DomPDF
use Illuminate\Contracts\View\View; // Untuk return type view()
use Illuminate\Http\RedirectResponse; // Untuk return type redirect()
use Illuminate\Http\Response; // Untuk return type PDF stream/download
use Illuminate\Http\JsonResponse; // Untuk return type searchSantri

class TagihanController extends Controller
{
    public function index(): View
    {
        $tagihans = Tagihan::with('santri')->orderBy('tanggal_tagihan', 'desc')->latest()->paginate(10);
        return view('tagihan.index', compact('tagihans'));
    }

    public function create(): View
    {
        return view('tagihan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'jenis_tagihan' => 'required|string|max:255',
            'nominal_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_tagihan',
            'keterangan' => 'nullable|string',
            'status_tagihan' => ['required', Rule::in(['Belum Lunas', 'Lunas'])],
            'tanggal_pelunasan' => ['nullable', 'date', 'required_if:status_tagihan,Lunas', 'after_or_equal:tanggal_tagihan'],
        ], [
            'santri_id.required' => 'Nama santri harus dipilih.',
            'jenis_tagihan.required' => 'Jenis tagihan harus diisi.',
            'nominal_tagihan.required' => 'Nominal tagihan harus diisi.',
            'tanggal_tagihan.required' => 'Tanggal tagihan harus diisi.',
            'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo harus sama atau setelah tanggal tagihan.',
            'status_tagihan.required' => 'Status tagihan harus dipilih.',
            'tanggal_pelunasan.required_if' => 'Tanggal pelunasan wajib diisi jika status tagihan adalah Lunas.',
            'tanggal_pelunasan.after_or_equal' => 'Tanggal pelunasan harus sama atau setelah tanggal tagihan.',
        ]);

        if ($validatedData['status_tagihan'] == 'Belum Lunas') {
            $validatedData['tanggal_pelunasan'] = null;
        }

        try {
            Tagihan::create($validatedData);
            return redirect()->route('tagihan.index')->with('success', 'Data tagihan santri berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data tagihan (store): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data tagihan. Pesan: ' . $e->getMessage());
        }
    }

    public function show(Tagihan $tagihan): View
    {
        $tagihan->load('santri');
        return view('tagihan.show', compact('tagihan'));
    }

    public function edit(Tagihan $tagihan): View
    {
        $tagihan->load('santri');
        return view('tagihan.edit', compact('tagihan'));
    }

    public function update(Request $request, Tagihan $tagihan): RedirectResponse
    {
        $validatedData = $request->validate([
            'jenis_tagihan' => 'required|string|max:255',
            'nominal_tagihan' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:tanggal_tagihan',
            'keterangan' => 'nullable|string',
            'status_tagihan' => ['required', Rule::in(['Belum Lunas', 'Lunas'])],
            'tanggal_pelunasan' => ['nullable', 'date', 'required_if:status_tagihan,Lunas', 'after_or_equal:tanggal_tagihan'],
        ], [
            'jenis_tagihan.required' => 'Jenis tagihan harus diisi.',
            'nominal_tagihan.required' => 'Nominal tagihan harus diisi.',
            'tanggal_tagihan.required' => 'Tanggal tagihan harus diisi.',
            'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo harus sama atau setelah tanggal tagihan.',
            'status_tagihan.required' => 'Status tagihan harus dipilih.',
            'tanggal_pelunasan.required_if' => 'Tanggal pelunasan wajib diisi jika status tagihan adalah Lunas.',
            'tanggal_pelunasan.after_or_equal' => 'Tanggal pelunasan harus sama atau setelah tanggal tagihan.',
        ]);

        if ($validatedData['status_tagihan'] == 'Belum Lunas') {
            $validatedData['tanggal_pelunasan'] = null;
        }
        
        try {
            $tagihan->update($validatedData);
            return redirect()->route('tagihan.show', $tagihan->id)->with('success', 'Data tagihan berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate data tagihan (update): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data tagihan. Pesan: ' . $e->getMessage());
        }
    }

    public function destroy(Tagihan $tagihan): RedirectResponse
    {
        try {
            $infoTagihan = $tagihan->jenis_tagihan . ' untuk ' . ($tagihan->santri->nama_lengkap ?? 'Santri ID: '.$tagihan->santri_id);
            $tagihan->delete();
            Log::info('Data tagihan berhasil dihapus: ' . $infoTagihan);
            return redirect()->route('tagihan.index')->with('success', 'Data tagihan (' . $infoTagihan . ') berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data tagihan (destroy): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('tagihan.index')->with('error', 'Terjadi kesalahan saat menghapus data tagihan.');
        }
    }

    public function searchSantri(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $santris = Santri::where('nama_lengkap', 'LIKE', "%{$query}%")
                              ->select('id', 'nama_lengkap', 'foto', 'tanggal_lahir', 'jenis_kelamin', 'pendidikan_terakhir', 'kamar')
                              ->take(10)
                              ->get();
            return response()->json($santris);
        } catch (\Exception $e) {
            Log::error('Error searchSantri di TagihanController: ' . $e->getMessage(), ['query' => $query, 'exception' => $e]);
            return response()->json(['error' => 'Terjadi kesalahan pada server saat mencari santri.'], 500);
        }
    }

    /**
     * Generate PDF for the specified tagihan.
     *
     * @param  \App\Models\Tagihan  $tagihan
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function printPDF(Tagihan $tagihan): Response|RedirectResponse
    {
        try {
            $tagihan->load('santri'); // Pastikan data santri sudah ter-load
            $pdf = PDF::loadView('tagihan.pdf', compact('tagihan'));
            $santriName = $tagihan->santri ? Str::slug($tagihan->santri->nama_lengkap, '_') : 'unknown';
            $fileName = 'tagihan_' . Str::slug($tagihan->jenis_tagihan, '_') . '_' . $santriName . '_' . $tagihan->id . '.pdf';
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF tagihan: ' . $e->getMessage(), ['tagihan_id' => $tagihan->id, 'exception' => $e]);
            return redirect()->back()->with('error', 'Gagal membuat dokumen PDF. Pesan Error: ' . $e->getMessage());
        }
    }
}