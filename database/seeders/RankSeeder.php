<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [
            'Pembina Utama Muda',
            'Pembina Tk. I',
            'Pembina',
            'Penata Tk. I',
            'Penata',
            'Penata Muda Tk. I',
            'Penata Muda',
            'Pengatur Tk. I',
            'Pengatur',
            'Pengatur Muda Tk. I',
            'Pengatur Muda',
            'Juru Tk. I',
            'Juru'
        ];
        foreach ($ranks as $rank) {
            Rank::updateOrCreate(
                ['rank_name' => $rank],
                ['rank_name' => $rank]
            );
        }
    }
}
