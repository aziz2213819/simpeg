<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            'IV/c', 'IV/a', 'III/d', 'III/c', 'III/b', 'III/a', 
            'II/d', 'II/c', 'II/b', 'II/a', 'I/d', 'I/c', 
            'IX', 'V', 'III', 'I'
        ];

        foreach ($grades as $grade) {
            Grade::updateOrCreate(
                ['grade_code' => $grade], // Cek berdasarkan kode golongan
                ['grade_code' => $grade]  // Jika belum ada, buat baru
            );
        }
    }
}
