<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
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


class PerizinanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $perizinans = Perizinan::with('santri')->latest()->paginate(10);
        return view('perizinan.index', compact('perizinans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('perizinan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'santri_id' => 'required|exists:santris,id',
            'kepentingan_izin' => 'required|string|max:255',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_izin',
            'keterangan' => 'nullable|string',
        ], [
            'santri_id.required' => 'Nama santri harus dipilih.',
            'kepentingan_izin.required' => 'Kepentingan izin harus diisi.',
            'tanggal_izin.required' => 'Tanggal izin harus diisi.',
            'tanggal_kembali_rencana.required' => 'Tanggal kembali rencana harus diisi.',
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali rencana harus sama atau setelah tanggal izin.',
        ]);

        try {
            $perizinan = new Perizinan();
            $perizinan->santri_id = $validatedData['santri_id'];
            $perizinan->kepentingan_izin = $validatedData['kepentingan_izin'];
            $perizinan->tanggal_izin = $validatedData['tanggal_izin'];
            $perizinan->tanggal_kembali_rencana = $validatedData['tanggal_kembali_rencana'];
            $perizinan->keterangan = $validatedData['keterangan'] ?? null;
            $perizinan->status_izin = 'Diajukan'; // Default status

            $perizinan->save();

            return redirect()->route('perizinan.index')->with('success', 'Data perizinan santri berhasil diajukan!');

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data perizinan (store): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengajukan perizinan. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Perizinan $perizinan): View
    {
        $perizinan->load('santri');
        return view('perizinan.show', compact('perizinan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perizinan $perizinan): View
    {
        $perizinan->load('santri');
        return view('perizinan.edit', compact('perizinan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perizinan $perizinan): RedirectResponse
    {
        $validatedData = $request->validate([
            'kepentingan_izin' => 'required|string|max:255',
            'tanggal_izin' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_izin',
            'tanggal_kembali_aktual' => 'nullable|date|after_or_equal:tanggal_izin',
            'keterangan' => 'nullable|string',
             // Tambahkan validasi untuk status_izin jika admin bisa mengubahnya langsung
            'status_izin' => ['sometimes', 'required', Rule::in(['Diajukan', 'Disetujui', 'Ditolak', 'Selesai', 'Terlambat Kembali'])],
        ], [
            'kepentingan_izin.required' => 'Kepentingan izin harus diisi.',
            'tanggal_izin.required' => 'Tanggal izin harus diisi.',
            'tanggal_kembali_rencana.required' => 'Tanggal kembali rencana harus diisi.',
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali rencana harus sama atau setelah tanggal izin.',
            'tanggal_kembali_aktual.after_or_equal' => 'Tanggal kembali aktual harus sama atau setelah tanggal izin.',
        ]);

        try {
            $perizinan->kepentingan_izin = $validatedData['kepentingan_izin'];
            $perizinan->tanggal_izin = $validatedData['tanggal_izin'];
            $perizinan->tanggal_kembali_rencana = $validatedData['tanggal_kembali_rencana'];
            $perizinan->keterangan = $validatedData['keterangan'] ?? null;

            // Jika admin bisa mengubah status langsung dari form edit
            if ($request->has('status_izin')) {
                 $perizinan->status_izin = $validatedData['status_izin'];
            }

            if (!empty($validatedData['tanggal_kembali_aktual'])) {
                $perizinan->tanggal_kembali_aktual = $validatedData['tanggal_kembali_aktual'];
                $tglAktual = \Carbon\Carbon::parse($validatedData['tanggal_kembali_aktual']);
                $tglRencana = \Carbon\Carbon::parse($perizinan->tanggal_kembali_rencana);

                if ($perizinan->status_izin !== 'Ditolak') {
                    if ($tglAktual->greaterThan($tglRencana)) {
                        $perizinan->status_izin = 'Terlambat Kembali';
                    } else {
                        $perizinan->status_izin = 'Selesai';
                    }
                }
            } else {
                 // Jika tanggal aktual dikosongkan dan status bukan Diajukan/Ditolak, set ke Disetujui
                if (!in_array($perizinan->status_izin, ['Diajukan', 'Ditolak'])) {
                    // Hanya set ke Disetujui jika sebelumnya bukan Diajukan atau Ditolak
                     if (in_array($perizinan->status_izin, ['Selesai', 'Terlambat Kembali'])) {
                        $perizinan->status_izin = 'Disetujui'; // atau status default setelah disetujui
                    }
                }
                $perizinan->tanggal_kembali_aktual = null;
            }

            $perizinan->save();

            return redirect()->route('perizinan.show', $perizinan->id)->with('success', 'Data perizinan berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate data perizinan (update): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui perizinan. Pesan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perizinan $perizinan): RedirectResponse
    {
        try {
            $infoIzin = $perizinan->kepentingan_izin . ' oleh ' . ($perizinan->santri->nama_lengkap ?? 'Santri ID: '.$perizinan->santri_id);
            $perizinan->delete();
            Log::info('Data perizinan berhasil dihapus: ' . $infoIzin);
            return redirect()->route('perizinan.index')->with('success', 'Data perizinan (' . $infoIzin . ') berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data perizinan (destroy): ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('perizinan.index')->with('error', 'Terjadi kesalahan saat menghapus data perizinan.');
        }
    }

    /**
     * Search for santri (for AJAX request).
     */
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
            Log::error('Error searchSantri di PerizinanController: ' . $e->getMessage(), ['query' => $query, 'exception' => $e]);
            return response()->json(['error' => 'Terjadi kesalahan pada server saat mencari santri.'], 500);
        }
    }

    /**
     * Generate PDF for the specified perizinan.
     *
     * @param  \App\Models\Perizinan  $perizinan
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function printPDF(Perizinan $perizinan): Response|RedirectResponse
    {
        try {
            $perizinan->load('santri'); // Pastikan data santri sudah ter-load
            $pdf = PDF::loadView('perizinan.pdf', compact('perizinan'));
            $santriName = $perizinan->santri ? Str::slug($perizinan->santri->nama_lengkap, '_') : 'unknown';
            $fileName = 'izin_santri_' . $santriName . '_' . $perizinan->id . '.pdf';
            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            Log::error('Gagal generate PDF perizinan: ' . $e->getMessage(), ['perizinan_id' => $perizinan->id, 'exception' => $e]);
            return redirect()->back()->with('error', 'Gagal membuat dokumen PDF. Pesan Error: ' . $e->getMessage());
        }
    }
}