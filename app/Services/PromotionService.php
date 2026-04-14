<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Notification;
use Illuminate\Support\Str;

class PromotionService
{
    protected array $schedules = [
        ['key' => 'h_8_bulan', 'method' => 'subMonths', 'value' => 8, 'label' => '8 Bulan'],
        // ['key' => 'h_6_bulan', 'method' => 'subMonths', 'value' => 6, 'label' => '6 Bulan'],
        // ['key' => 'h_4_bulan', 'method' => 'subMonths', 'value' => 4, 'label' => '4 Bulan'],
        // ['key' => 'h_2_bulan', 'method' => 'subMonths', 'value' => 2, 'label' => '2 Bulan'],
        // ['key' => 'h_1_minggu', 'method' => 'subWeeks',  'value' => 1, 'label' => '1 Minggu'],
        // ['key' => 'h_1_hari',   'method' => 'subDays',   'value' => 1, 'label' => '1 Hari'],
    ];

    protected array $supportedTypes = ['pangkat', 'gaji_berkala', 'pensiun'];

    public function checkAndGenerateNotifications($users)
    {
        $now = Carbon::parse(now())->startOfDay();
        $debugNotif = [];

        foreach ($users as $user) {
            $employee = $user->employee;
            if (!$employee) continue;

            // Loop ke setiap jenis notifikasi (Pangkat, Gaji, Pensiun)
            foreach ($this->supportedTypes as $type) {
                
                // Panggil "Otak" penghitung tanggal
                $targetDate = $this->calculateTargetDate($employee, $type, $now);

                // Jika data tidak valid (misal TMT kosong) atau target sudah lewat, lewati
                if (!$targetDate || $now->greaterThanOrEqualTo($targetDate)) {
                    continue;
                }

                // Cek jadwal peringatan (H-8 bulan)
                foreach ($this->schedules as $schedule) {
                    
                    $triggerDate = $targetDate->copy()->{$schedule['method']}($schedule['value']);

                    // Jika hari ini sudah masuk rentang peringatan
                    if ($now->greaterThanOrEqualTo($triggerDate)) {
                        
                        // KUNCI GEMBOK: Cek berdasarkan Type ENUM & Judul agar tidak memblokir H- yang lain
                        $alreadyNotified = Notification::where('employee_id', $employee->id)
                            ->where('type', $type)
                            ->where('title', 'LIKE', '%H-' . $schedule['label'] . '%') 
                            ->whereYear('created_at', $now->year)
                            ->exists();

                        if (!$alreadyNotified) {
                            $debugNotif[] = [
                                'nama' => $employee->name,
                                'jenis' => Str::headline($type),
                                'kategori_notif' => 'H-' . $schedule['label'],
                                'tanggal_target' => $targetDate->format('d M Y'),
                                'tanggal_trigger' => $triggerDate->format('d M Y'),
                            ];

                            // KODE ASLI UNTUK INSERT DB:
                            /*
                            Notification::create([
                                'employee_id' => $employee->id,
                                'type'        => $type, // Murni 'pangkat', 'gaji_berkala', atau 'pensiun' (Aman untuk ENUM)
                                'title'       => 'Peringatan H-' . $schedule['label'] . ' ' . ucwords(str_replace('_', ' ', $type)),
                                'message'     => "Sistem mendeteksi jadwal {$type} untuk {$employee->name} jatuh pada " . $targetDate->format('d M Y') . ". Mohon siapkan berkas.",
                                'is_read'     => false,
                            ]);
                            */
                        }
                    }
                }
            }
        }

        return $debugNotif;
    }

    /**
     * "OTAK" LOGIKA: Menghitung target tanggal berdasarkan aturan masing-masing tipe
     */
    private function calculateTargetDate($employee, string $type, Carbon $now)
    {
        switch ($type) {
            case 'pangkat':
                // Aturan: Kelipatan 4 Tahun dari TMT
                if (!$employee->tmt_start) return null;
                $tmt = Carbon::parse($employee->tmt_start)->startOfDay();
                $yearsElapsed = $tmt->floatDiffInYears($now);
                
                $nextCycle = ceil($yearsElapsed / 4) * 4;
                if ($nextCycle == 0) $nextCycle = 4;
                return $tmt->copy()->addYears($nextCycle);

            case 'gaji_berkala':
                // Aturan: Kelipatan 2 Tahun dari TMT
                if (!$employee->tmt_start) return null;
                $tmt = Carbon::parse($employee->tmt_start)->startOfDay();
                $yearsElapsed = $tmt->floatDiffInYears($now);
                
                $nextCycle = ceil($yearsElapsed / 2) * 2;
                if ($nextCycle == 0) $nextCycle = 2;
                return $tmt->copy()->addYears($nextCycle);

            case 'pensiun':
                // Aturan: Umur 58 Tahun (Asumsi menggunakan kolom birth_date)
                // Pastikan Anda memiliki kolom birth_date di tabel employees
                if (!$employee->birth_date) return null;
                
                $bday = Carbon::parse($employee->birth_date)->startOfDay();
                $umurPensiun = 60; // Batas Usia Pensiun (BUP) umum ASN
                
                return $bday->copy()->addYears($umurPensiun);

            default:
                return null;
        }
    }
}