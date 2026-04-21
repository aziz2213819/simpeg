<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            'Sekretaris',
            'Kabid Penaatan Lingkungan Hidup',
            'Kabid Pelayanan Tata Lingkungan',
            'Kabid P2Kl Dan Pl',
            'Kabid Pengelolaan Sampah Dan Limbah B3',
            'Pengendali Dampak Lingkungan Ahli Muda',
            'Ka. Subbag Keuangan',
            'Ka.Subbag Perencanaan Dan Evaluasi',
            'Kasubag Umum Dan Kepegawaian',
            'Kepala Upt Pengelolaan Sampah',
            'Penelaah Teknis Kebijakan',
            'Pengolah Data Dan Informasi',
            'Pengawas Lapangan Petugas Kebersihan, Jalan, Dan Selokan',
            'Analis Pajak Dan Retribusi Daerah',
            'Penata Laporan Keuangan',
            'Penata Kelola Sistem Dan Teknologi Informasi',
            'Pemelihara Tumbuhan',
            'Pranata Taman',
            'Ahli Pertama - Perencana',
            'Penyuluh Lingkungan Ahli Pertama',
            'Pengendali Dampak Lingkungan Ahli Pertama',
            'Pengawas Lingkungan Hidup Ahli Pertama',
            'Penyuluh Lingkungan Hidup Ahli Pertama',
            'Penata Layanan Operasional',
            'Operator Layanan Operasional',
            'Pengadministrasi Perkantoran',
            'Pengelola Umum Operasional',
            'Kepala Dinas Lingkungan Hidup',
            'Analis Perencana Program Dan Kegiatan',
            'Penyuluh Lingkungan Hidup',
            'Analis Taman',
            'Analis Lingkungan Hidup',
            'Pengelola Data',
            'Analis Data Dan Informasi',
            'Pengawas Lapangan Petugas Pertamanan',
            'Penyusun Kebutuhan Barang Inventaris',
            'Pemelihara Jalan',
            'Pengadministrasi Hukum',
            'Pengadministrasi Keuangan',
            'Pranata Pengambil Sampel',
            'Pengadministasi Umum',
            'Pengadministrasi Rapat',
            'Pengadministrasi Umum',
            'Pengadministrasi Sarana Dan Prasarana',
            'Petugas Keamanan',
            'Pengadministrasi Tempat Pembuangan Akhir',
            'Pengadministrasi Persuratan',
            'Pengadministrasi Kepegawaian',
            'Pramu Kebersihan',
            'Pramu Taman',
        ];
        foreach ($positions as $position) {
            Position::updateOrCreate(
                ['position_name' => $position],
                ['position_name' => $position]
            );
        }
    }
}
