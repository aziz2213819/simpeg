<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Comment; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// Import Manager dan Driver untuk Intervention Image V3
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ReportController extends Controller
{
    /**
     * SISI WARGA: Menyimpan pengaduan baru dari Landing Page/Portal.
     * Menggunakan Intervention Image V3 untuk kompresi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelapor'  => 'required|string|max:100',
            'tipe_sampah'   => 'required|in:organik,anorganik,b3,lainnya',
            'deskripsi'     => 'required|string|max:1000',
            'lokasi_manual' => 'nullable|string|max:255',
            'lat'           => 'required|numeric',
            'lng'           => 'required|numeric',
            'foto_bukti'    => 'required|image|max:10240', 
        ]);

        try {
            $fotoPath = null;

            if ($request->hasFile('foto_bukti')) {
                $file = $request->file('foto_bukti');
                $filename = time() . '_' . Str::random(10) . '.jpg';
                
                if (!Storage::disk('public')->exists('reports')) {
                    Storage::disk('public')->makeDirectory('reports');
                }

                // --- PROSES KOMPRESI (VERSI 3) ---
                // 1. Inisialisasi Manager dengan Driver GD
                $manager = new ImageManager(new Driver());
                
                // 2. Baca file gambar
                $image = $manager->read($file->getRealPath());
                
                // 3. Resize lebar ke 800px (skala otomatis)
                $image->scale(width: 800);

                // 4. Encode ke format JPG dengan kualitas 70%
                $encoded = $image->toJpeg(70);

                // 5. Simpan ke storage
                Storage::disk('public')->put('reports/' . $filename, (string) $encoded);
                
                $fotoPath = 'reports/' . $filename;
            }

            $trackingId = 'TRK-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            Report::create([
                'tracking_id'   => $trackingId,
                'nama_pelapor'  => $request->nama_pelapor,
                'tipe_sampah'   => $request->tipe_sampah,
                'deskripsi'     => $request->deskripsi,
                'lokasi_manual' => $request->lokasi_manual,
                'lat'           => round($request->lat, 8),
                'lng'           => round($request->lng, 8),
                'foto_bukti'    => $fotoPath,
                'status'        => 'pending',
            ]);

            return redirect()->back()->with('success', 'Laporan berhasil dikirim! ID Tracking: ' . $trackingId);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    /**
     * SISI ADMIN: Menampilkan daftar semua pengaduan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $type = $request->input('tipe_sampah');

        $reports = Report::query()
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nama_pelapor', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('lokasi_manual', 'like', "%{$search}%")
                      ->orWhere('tracking_id', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($type, function ($query, $type) {
                return $query->where('tipe_sampah', $type);
            })
            ->withCount('comments') 
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengaduan.index', compact('reports', 'search', 'status', 'type'));
    }

    /**
     * SISI PORTAL: Menampilkan daftar pengaduan (Public).
     */
    public function portal()
    {
        $allReports = Report::withCount('comments')
            ->latest()
            ->get();
        
        return view('welcome', compact('allReports')); 
    }

    /**
     * ADMIN: Detail Pengaduan.
     */
    public function show(Report $pengaduan)
    {
        $pengaduan->load(['user', 'comments.user']);
        return view('admin.pengaduan.show', ['item' => $pengaduan]);
    }

    /**
     * ADMIN: Kirim Komentar.
     */
    public function storeComment(Request $request, Report $pengaduan)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'report_id' => $pengaduan->id,
            'user_id'   => Auth::id(), 
            'body'      => $request->body,
        ]);

        return back()->with('success', 'Tanggapan berhasil dikirim!');
    }

    /**
     * ADMIN: Update Status.
     */
    public function updateStatus(Request $request, Report $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai'
        ]);
        
        $pengaduan->update([
            'status' => $request->status
        ]);
        
        return back()->with('success', 'Status diperbarui.');
    }

    /**
     * ADMIN: Hapus Laporan.
     */
    public function destroy(Report $pengaduan)
    {
        try {
            if ($pengaduan->foto_bukti && Storage::disk('public')->exists($pengaduan->foto_bukti)) {
                Storage::disk('public')->delete($pengaduan->foto_bukti);
            }

            $pengaduan->delete();
            return redirect()->route('admin.pengaduan.index')->with('success', 'Laporan dihapus.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus laporan.');
        }
    }
}