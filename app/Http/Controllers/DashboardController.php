<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua status dari database beserta jumlah santri di setiap status
        $semuaStatus = Status::withCount('santris')->get();

        // Menyiapkan data khusus untuk grafik dari data yang sudah diambil
        $chartData = [
            'labels' => $semuaStatus->pluck('nama_status'), // Mengambil nama status untuk label grafik
            'data'   => $semuaStatus->pluck('santris_count'), // Mengambil jumlah santri untuk data grafik
        ];

        // Kirim kedua variabel ke view:
        // $semuaStatus -> untuk kartu dinamis
        // $chartData -> untuk grafik
        return view('dashboard', [
            'semuaStatus' => $semuaStatus,
            'chartData'   => $chartData,
        ]);
    }
}