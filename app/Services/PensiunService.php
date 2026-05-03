<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;

class PensiunService
{
    public function process()
    {
        $now = now()->startOfDay();

        // 🔴 ambil hanya yang BELUM pensiun
        $employees = Employee::whereNotNull('birth_date')
            ->where(function ($q) {
                $q->where('status', '!=', 'nonactive');
            })
            ->get();

        foreach ($employees as $employee) {

            if (!$this->isEligible($employee, $now)) {
                continue;
            }

            $this->retire($employee, $now);
        }
    }

    private function isEligible($employee, $now)
    {
        $birth = Carbon::parse($employee->birth_date)->startOfDay();
        $retirementDate = $birth->copy()->addYears(60);

        // 🔴 sudah mencapai usia pensiun
        return $now->gte($retirementDate);
    }

    private function retire($employee, $date)
    {
        $employee->update([
            'status' => 'nonactive',
        ]);
    }
}