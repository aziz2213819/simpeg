<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\Notification;
use Illuminate\Support\Str;

class PromotionService
{
    // Pemetaan jadwal spesifik untuk masing-masing tipe notifikasi
    protected array $typeSchedules = [
        'pangkat' => [
            ['method' => 'subMonths', 'value' => 8, 'label' => '8 Bulan']
        ],
        'gaji_berkala' => [
            ['method' => 'subMonths', 'value' => 1, 'label' => '1 Bulan']
        ],
        'pensiun' => [
            ['method' => 'subYears', 'value' => 1, 'label' => '1 Tahun']
        ],
    ];

    public function checkAndGenerateNotifications($users)
    {
        $now = Carbon::parse(now())->startOfDay();
        $debugNotif = [];

        foreach ($users as $user) {
            $employee = $user->employee;
            if (!$employee) continue;

            // Loop berdasarkan tipe yang ada di array pemetaan di atas
            foreach ($this->typeSchedules as $type => $schedules) {
                
                // Panggil "Otak" penghitung tanggal target
                $targetDate = $this->calculateTargetDate($employee, $type, $now);

                // Jika data tidak valid atau target sudah lewat, lewati
                if (!$targetDate || $now->greaterThanOrEqualTo($targetDate)) {
                    continue;
                }

                // Loop jadwal peringatan khusus untuk tipe ini saja
                foreach ($schedules as $schedule) {
                    
                    // Hitung kapan notifikasi harus mulai muncul (Trigger Date)
                    $triggerDate = $targetDate->copy()->{$schedule['method']}($schedule['value']);

                    // Jika hari ini sudah masuk masa peringatan (Trigger Date)
                    if ($now->greaterThanOrEqualTo($triggerDate)) {
                        
                        // KUNCI GEMBOK: Cek agar tidak terjadi duplikasi notifikasi di tahun yang sama
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

                            // Insert ke Database
                            Notification::create([
                                'employee_id' => $employee->id,
                                'type'        => $type,
                                'title'       => 'Peringatan H-' . $schedule['label'] . ' ' . Str::headline($type),
                                'message'     => "Sistem mendeteksi jadwal " . Str::headline($type) . " untuk {$employee->name} jatuh pada " . $targetDate->format('d M Y') . ". Mohon segera persiapkan berkas yang dibutuhkan.",
                                'is_read'     => false,
                            ]);
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
                // Kelipatan 4 Tahun dari TMT
                if (!$employee->tmt_start) return null;
                $tmt = Carbon::parse($employee->tmt_start)->startOfDay();
                $yearsElapsed = $tmt->floatDiffInYears($now);
                
                $nextCycle = ceil($yearsElapsed / 4) * 4;
                if ($nextCycle == 0) $nextCycle = 4;
                return $tmt->copy()->addYears($nextCycle);
                
            case 'gaji_berkala':
                // Kelipatan 2 Tahun dari TMT
                if (!$employee->tmt_start) return null;
                $tmt = Carbon::parse($employee->tmt_start)->startOfDay();
                $yearsElapsed = $tmt->floatDiffInYears($now);
                
                $nextCycle = ceil($yearsElapsed / 2) * 2;
                if ($nextCycle == 0) $nextCycle = 2;
                return $tmt->copy()->addYears($nextCycle);

            case 'pensiun':
                // Umur 60 Tahun
                if (!$employee->birth_date) return null;
                
                $bday = Carbon::parse($employee->birth_date)->startOfDay();
                $umurPensiun = 60; // Batas Usia Pensiun (BUP)
                
                return $bday->copy()->addYears($umurPensiun);

            default:
                return null;
        }
    }
}
