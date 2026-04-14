<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GeneratePromotionNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-promotion-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cycleTime = 1;
        
        // Waktu sekarang (dibulatkan ke hari)
        $now = now()->startOfDay();
        
        // Target yang dicek (1 hari ke depan)
        $targetTime = $now->copy()->addDay();
        
        // Titik nol simulasi (awal hari ini)
        $anchor = now()->startOfDay();

        // Hitung berapa menit yang sudah berjalan sejak tadi pagi
        $dayRunning = $anchor->diffInDays($targetTime);

        $users = User::has('employee')->with('employee')->get();
        $this->info("=== SIMULASI MENIT BERJALAN ===");
        $this->info("Waktu Sistem : " . $now->format('d.m.Y'));
        $this->info("Cek Target   : " . $targetTime->format('d.m.Y') . " ({$dayRunning} hari dari awal hari)");

        foreach ($users as $user) {
            // Logika: Jika menit yang berjalan adalah kelipatan 2, kirim notifikasi
            if ($dayRunning > 0 && $dayRunning % $cycleTime == 0) {
                try {
                    // Tambahkan pengecekan agar tidak duplikat di menit yang sama
                    $exists = Notification::where('employee_id', $user->employee->id)
                        ->where('created_at', '>=', $now)
                        ->exists();

                    if (!$exists) {
                        Notification::create([
                            'employee_id' => $user->employee->id,
                            'type'        => 'pangkat',
                            'title'       => 'Peringatan Kenaikan Jabatan (Simulasi)',
                            'message'     => "Pegawai {$user->employee->name} akan naik jabatan dalam 1 menit.",
                            'is_read'     => false,
                        ]);
                        $this->info("   [OK] Notifikasi terkirim ke: {$user->employee->name}");
                    }
                } catch (\Exception $e) {
                    $this->error("   [ERROR] " . $e->getMessage());
                }
            }
        }
    }

    // public function handle()
    // {
    //     // 1. Konfigurasi Aturan Bisnis
    //     $hMonths = 3;       // Notifikasi H-3 Bulan
    //     $cycleYears = 4;    // Kelipatan 4 Tahun

    //     // 2. Waktu Target (Hari ini + 3 Bulan)
    //     // Kita gunakan startOfDay agar waktu jam/menit tidak mengganggu akurasi pencocokan tanggal
    //     $targetDate = now()->addMonths($hMonths)->startOfDay();

    //     // 3. Ambil pegawai yang memiliki data employee dan tmt_start tidak kosong
    //     $users = User::has('employee')->with('employee')->whereHas('employee', function($query) {
    //         $query->whereNotNull('tmt_start');
    //     })->get();

    //     $this->info("=== CEK NOTIFIKASI KENAIKAN PANGKAT ===");
    //     $this->info("Waktu Sistem : " . now()->format('d M Y'));
    //     $this->info("Cek Target   : " . $targetDate->format('d M Y') . " (H+{$hMonths} Bulan)");

    //     foreach ($users as $user) {
    //         $employee = $user->employee;

    //         // 4. Jadikan tmt_start sebagai Anchor (Titik Nol)
    //         $anchor = Carbon::parse($employee->tmt_start)->startOfDay();

    //         // 5. Cek kecocokan Hari dan Bulan (Abaikan tahun untuk sementara)
    //         if ($anchor->month === $targetDate->month && $anchor->day === $targetDate->day) {
                
    //             // 6. Jika hari dan bulan cocok, hitung selisih tahunnya
    //             $diffInYears = $anchor->diffInYears($targetDate);

    //             // 7. Cek apakah selisih tahun adalah kelipatan 4
    //             if ($diffInYears > 0 && $diffInYears % $cycleYears === 0) {
    //                 try {
    //                     // 8. Proteksi Duplikasi: Cek apakah tahun ini sudah pernah dikirim notifikasi pangkat
    //                     $exists = Notification::where('employee_id', $employee->id)
    //                         ->where('type', 'pangkat')
    //                         ->whereYear('created_at', now()->year)
    //                         ->exists();

    //                     if (!$exists) {
    //                         Notification::create([
    //                             'employee_id' => $employee->id,
    //                             'type'        => 'pangkat',
    //                             'title'       => 'Persiapan Kenaikan Jabatan',
    //                             'message'     => "Pegawai {$employee->name} akan mencapai masa kerja {$diffInYears} tahun pada " . $targetDate->format('d M Y') . ". Segera siapkan berkas.",
    //                             'is_read'     => false,
    //                         ]);
    //                         $this->info("   [OK] Notifikasi terkirim ke: {$employee->name} (Masa Kerja: {$diffInYears} Tahun)");
    //                     } else {
    //                         $this->line("   [SKIP] {$employee->name} sudah menerima notifikasi tahun ini.");
    //                     }
    //                 } catch (\Exception $e) {
    //                     $this->error("   [ERROR] " . $e->getMessage());
    //                 }
    //             }
    //         }
    //     }
    // }
}
