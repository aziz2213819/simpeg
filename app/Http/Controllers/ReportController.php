<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $reports = Report::query()
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nama_pelapor', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('lokasi_manual', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest() // Urutkan dari laporan terbaru
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengaduan.index', compact('reports', 'search', 'status'));
    }

    public function show(Report $pengaduan)
    {
        // $pengaduan otomatis ditarik dari database berkat Route Model Binding
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    public function updateStatus(Request $request, Report $pengaduan)
    {
        $request->validate(['status' => 'required|in:pending,proses,selesai']);        
        $pengaduan->update(['status' => $request->status]);
        return back()->with('success', 'Status laporan berhasil diperbarui menjadi ' . strtoupper($request->status));
    }
}
