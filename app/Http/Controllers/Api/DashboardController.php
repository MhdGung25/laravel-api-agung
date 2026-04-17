<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Information;
use Illuminate\Http\Request;
use Exception;

class DashboardController extends Controller
{
    /**
     * Mengambil data statistik terbaru dari database.
     * Data ini akan berubah secara otomatis (real-time) setiap kali Dashboard di-refresh
     * atau dipanggil oleh fungsi fetchData di React.
     * * Endpoint: GET /api/dashboard-data
     */
    public function index()
    {
        try {
            // 1. Hitung total semua laporan secara langsung dari MySQL
            $totalReports = Report::count();
            
            // 2. Hitung statistik spesifik menggunakan Scope yang sudah kita buat di Model Report
            // Ini akan otomatis menghitung laporan dengan status 'pending' dan 'selesai'
            $totalPending = Report::pending()->count();
            $totalSelesai = Report::selesai()->count();

            // 3. Hitung pengumuman/informasi yang statusnya aktif (is_active = 1)
            $totalActiveInfo = Information::where('is_active', true)->count();

            // 4. Status keamanan (Dibuat dinamis berdasarkan jumlah laporan pending)
            // Jika laporan pending lebih dari 5, status berubah menjadi 'Perlu Perhatian'
            $securityStatus = ($totalPending > 5) ? 'Perlu Perhatian' : 'Sangat Baik'; 

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_reports' => $totalReports,
                    'total_pending' => $totalPending,
                    'total_selesai' => $totalSelesai,
                    'active_informations' => $totalActiveInfo,
                    'security_status' => $securityStatus,
                ],
                'message' => 'Statistik berhasil diperbarui secara real-time dari MySQL'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data statistik: ' . $e->getMessage()
            ], 500);
        }
    }
}