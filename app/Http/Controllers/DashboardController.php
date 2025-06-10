<?php

namespace App\Http\Controllers;

use App\Models\Santri; // Pastikan model Santri di-import
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil jumlah santri berdasarkan status
        $jumlahSantriAktif = Santri::where('status_santri', 'Aktif')->count();
        $jumlahAlumni = Santri::where('status_santri', 'Alumni')->count();
        $jumlahSantriBaru = Santri::where('status_santri', 'Baru')->count();
        $jumlahPengurus = Santri::where('status_santri', 'Pengurus')->count();
        
        // Opsional: Total semua entri santri
        $totalSantri = Santri::count();

        // Kirim data ke view dashboard
        return view('dashboard', [
            'jumlahSantriAktif' => $jumlahSantriAktif,
            'jumlahAlumni' => $jumlahAlumni,
            'jumlahSantriBaru' => $jumlahSantriBaru,
            'jumlahPengurus' => $jumlahPengurus,
            'totalSantri' => $totalSantri, // Anda bisa gunakan ini jika mau
        ]);
    }

    // Metode lain seperti create, store, dll. tidak kita perlukan untuk DashboardController sederhana ini,
    // jadi bisa dihapus jika ter-generate otomatis oleh 'make:controller --resource'.
    // Jika Anda hanya menjalankan 'make:controller DashboardController', hanya index() yang perlu Anda buat.
}
