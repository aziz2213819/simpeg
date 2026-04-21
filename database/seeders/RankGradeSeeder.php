<?php

namespace Database\Seeders;

use App\Models\RankGrade;
use Illuminate\Database\Seeder;

class RankGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [
            'Pembina Tk. I', 'Pembina', 'Penata Tk. I', 'Penata', 
            'Penata Muda Tk. I', 'Penata Muda', 'Pengatur Tk. I', 
            'Pengatur', 'Pengatur Muda Tk. I', 'Pengatur Muda', 
            'Juru Tk. I', 'Juru', 'Juru Muda Tk. I', 'Juru Muda',
            null, null, null, null, null, null, null, null, null
        ];
        $grades = [
            'IV/b', 'IV/a', 'III/d', 'III/c', 'III/b', 'III/a', 
            'II/d', 'II/c', 'II/b', 'II/a', 'I/d', 'I/c', 
            'I/b', 'I/a', 'IX', 'VIII', 'VII', 'VI', 'V', 'IV', 
            'III', 'II', 'I'
        ];

        $data = [];

        foreach ($grades as $index => $gradeValue) {
            $data[] = [
                'grade_code'       => $gradeValue,
                'rank_name'        => $ranks[$index],
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }

        RankGrade::insert($data);
    }
}
