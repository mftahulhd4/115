<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menghitung jumlah santri berdasarkan statusnya masing-masing
        $jumlahSantriAktif = Santri::where('status_santri', 'Aktif')->count();
        $jumlahSantriBaru = Santri::where('status_santri', 'Baru')->count();
        $jumlahPengurus = Santri::where('status_santri', 'Pengurus')->count();
        $jumlahAlumni = Santri::where('status_santri', 'Alumni')->count();

        // Kirim semua data yang sudah dihitung ke view 'dashboard'
        return view('dashboard', [
            'jumlahSantriAktif' => $jumlahSantriAktif,
            'jumlahSantriBaru' => $jumlahSantriBaru,
            'jumlahPengurus' => $jumlahPengurus,
            'jumlahAlumni' => $jumlahAlumni,
        ]);
    }
}