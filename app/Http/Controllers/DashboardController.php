<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Status; // 1. Tambahkan use statement untuk model Status
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 2. Cara baru yang lebih baik untuk menghitung santri berdasarkan relasi
        
        // Cari ID untuk setiap status
        $statusAktifId = Status::where('nama_status', 'Aktif')->value('id_status');
        $statusBaruId = Status::where('nama_status', 'Baru')->value('id_status');
        $statusPengurusId = Status::where('nama_status', 'Pengurus')->value('id_status');
        $statusAlumniId = Status::where('nama_status', 'Alumni')->value('id_status');

        // Hitung santri berdasarkan id_status
        $jumlahSantriAktif = Santri::where('id_status', $statusAktifId)->count();
        $jumlahSantriBaru = Santri::where('id_status', $statusBaruId)->count();
        $jumlahPengurus = Santri::where('id_status', $statusPengurusId)->count();
        $jumlahAlumni = Santri::where('id_status', $statusAlumniId)->count();
        

        // Kirim semua data yang sudah dihitung ke view 'dashboard'
        return view('dashboard', [
            'jumlahSantriAktif' => $jumlahSantriAktif,
            'jumlahSantriBaru' => $jumlahSantriBaru,
            'jumlahPengurus' => $jumlahPengurus,
            'jumlahAlumni' => $jumlahAlumni,
        ]);
    }
}