<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // CARA LAMA (BERPOTENSI GAGAL JIKA CASE BERBEDA)
        // $statusAktifId = Status::where('nama_status', 'Aktif')->value('id_status');
        
        // --- SOLUSI: Gunakan whereRaw untuk perbandingan case-insensitive ---
        $statusAktifId = Status::whereRaw('LOWER(nama_status) = ?', ['aktif'])->value('id_status');
        $statusBaruId = Status::whereRaw('LOWER(nama_status) = ?', ['baru'])->value('id_status');
        $statusPengurusId = Status::whereRaw('LOWER(nama_status) = ?', ['pengurus'])->value('id_status');
        $statusAlumniId = Status::whereRaw('LOWER(nama_status) = ?', ['alumni'])->value('id_status');

        // Hitung santri berdasarkan id_status
        $jumlahSantriAktif = $statusAktifId ? Santri::where('id_status', $statusAktifId)->count() : 0;
        $jumlahSantriBaru = $statusBaruId ? Santri::where('id_status', $statusBaruId)->count() : 0;
        $jumlahPengurus = $statusPengurusId ? Santri::where('id_status', $statusPengurusId)->count() : 0;
        $jumlahAlumni = $statusAlumniId ? Santri::where('id_status', $statusAlumniId)->count() : 0;
        
        // Kirim semua data yang sudah dihitung ke view 'dashboard'
        return view('dashboard', [
            'jumlahSantriAktif' => $jumlahSantriAktif,
            'jumlahSantriBaru' => $jumlahSantriBaru,
            'jumlahPengurus' => $jumlahPengurus,
            'jumlahAlumni' => $jumlahAlumni,
        ]);
    }
}