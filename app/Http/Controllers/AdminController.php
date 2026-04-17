<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Report;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Hitung jumlah laporan pending
        $pendingReportsCount = Report::where('status', 'pending')->count();

        // 2. Hitung jumlah pegawai (Pisahkan ASN dan Non ASN)
        $asnCount = Employee::where('type', 'ASN')->count();
        $nonAsnCount = Employee::where('type', 'Non ASN')->count();

        // 3. Ambil 5 laporan pending terbaru untuk ditampilkan di tabel bawah
        $recentReports = Report::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'pendingReportsCount', 
            'asnCount', 
            'nonAsnCount', 
            'recentReports'
        ));
    }
}
