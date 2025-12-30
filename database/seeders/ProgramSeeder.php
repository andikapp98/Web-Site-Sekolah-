<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'name' => 'TEKNIK PEMESINAN',
                'short_name' => 'TPM',
                'description' => 'Mempersiapkan tenaga ahli dalam pengoperasian mesin industri, CAD/CAM, dan perawatan mesin.',
                'full_description' => 'Jurusan Teknik Pemesinan berfokus pada pendidikan dan pelatihan di bidang manufaktur presisi. Siswa belajar menggambar teknik, penggunaan perkakas tangan, serta pengoperasian mesin produksi konvensional (bubut, frais, gerinda) hingga mesin modern berbasis komputer (CNC). Tujuannya adalah mencetak tenaga kerja kompeten sebagai operator, teknisi, atau juru gambar teknik.',
                'image' => null, // User can upload later
                'order' => 1,
            ],
            [
                'name' => 'TEKNIK KENDARAAN RINGAN',
                'short_name' => 'TKR',
                'description' => 'Mempelajari sistem otomotif modern, perbaikan mesin, dan manajemen bengkel.',
                'full_description' => 'Kompetensi Keahlian Teknik Kendaraan Ringan Otomotif membekali peserta didik dengan ilmu kendaraan ringan otomotif yang menekankan pada keahlian jasa perbaikan kendaraan ringan roda empat. Kompetensi keahlian ini menyiapkan peserta didik untuk bekerja pada bidang pekerjaan jasa perawatan dan perbaikan di dunia usaha/industri.',
                'image' => null,
                'order' => 2,
            ],
            [
                'name' => 'DESAIN KOMUNIKASI VISUAL',
                'short_name' => 'DKV',
                'description' => 'Mengembangkan kreativitas dalam desain grafis, fotografi, videografi, dan branding.',
                'full_description' => 'Jurusan Desain Komunikasi Visual (DKV) mempelajari ilmu tentang penyampaian pesan (komunikasi) dengan menggunakan elemen-elemen visual atau rupa. Disini siswa akan mengembangkan kreativitas dalam mengolah bahasa visual (gambar) untuk keperluan penyampaian pesan yang efektif, informatif dan komunikatif.',
                'image' => null,
                'order' => 3,
            ],
            [
                'name' => 'ANALISIS PENGUJIAN LAB',
                'short_name' => 'APL',
                'description' => 'Fokus pada pengujian laboratorium kimia, analisis produk industri, dan kontrol kualitas.',
                'full_description' => 'Analisis Pengujian Laboratorium adalah jurusan yang mempelajari tentang analisis kimia dasar, teknik pengambilan sampel, analisis mikrobiologi, analisis instrumen, dan manajemen laboratorium. Lulusan jurusan ini diharapkan mampu menjadi tenaga analis laboratorium yang profesional.',
                'image' => null,
                'order' => 4,
            ],
            [
                'name' => 'MANAJEMEN PERKANTORAN',
                'short_name' => 'MP',
                'description' => 'Keahlian dalam administrasi bisnis, manajemen kearsipan, dan komunikasi perkantoran.',
                'full_description' => 'Manajemen Perkantoran dan Layanan Bisnis membekali peserta didik dengan keterampilan, pengetahuan dan sikap agar kompeten dalam bidang pengelolaan administrasi perkantoran, komunikasi kantor, kearsipan, teknologi perkantoran, dan pelayanan publik.',
                'image' => null,
                'order' => 5,
            ],
            [
                'name' => 'TEKNIK LOGISTIK',
                'short_name' => 'LOG',
                'description' => 'Manajemen rantai pasok, pergudangan, distribusi, dan manajemen transportasi.',
                'full_description' => 'Teknik Logistik mempelajari perencanaan, pelaksanaan, dan pengendalian aliran barang, jasa, dan informasi dari titik asal ke titik konsumsi. Siswa akan belajar tentang manajemen pergudangan, transportasi, distribusi, dan manajemen rantai pasok (Supply Chain Management).',
                'image' => null,
                'order' => 6,
            ],
        ];

        foreach ($programs as $program) {
            \App\Models\Program::create($program);
        }
    }
}
