<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'BRIGPASMU SMK YASMU Gresik Torehkan Prestasi Gemilang, Juarai LKBB GAPURA 4.0',
                'slug' => 'brigpasmu-juarai-lkbb-gapura',
                'excerpt' => 'Tim Paskibra SMK YASMU yang dikenal dengan nama BRIGPASMU berhasil meraih gelar juara dalam kompetisi LKBB GAPURA 4.0.',
                'content' => 'GRESIK – Prestasi membanggakan kembali diukir oleh siswa-siswi SMK YASMU Manyar, Kabupaten Gresik. Kali ini, tim Paskibra sekolah yang dikenal dengan nama BRIGPASMU (Brigadir Pasukan SMK YASMU) berhasil meraih gelar juara dalam kompetisi Lomba Keterampilan Baris-Berbaris (LKBB) GAPURA 4.0. Keberhasilan ini menambah deretan prestasi yang telah ditorehkan oleh siswa-siswi SMK YASMU di berbagai ajang kompetisi.',
                'category' => 'Prestasi',
                'is_published' => true,
            ],
            [
                'title' => 'Siswa SMK YASMU Raih Juara 1 Lomba Kompetensi Siswa Tingkat Provinsi',
                'slug' => 'juara-lks-provinsi',
                'excerpt' => 'Muhammad Rizky siswa jurusan Teknik Pemesinan berhasil meraih juara 1 LKS Provinsi Jawa Timur.',
                'content' => 'Kabar membanggakan datang dari SMK YASMU Gresik. Muhammad Rizky, siswa kelas XII jurusan Teknik Pemesinan, berhasil meraih juara 1 dalam Lomba Kompetensi Siswa (LKS) tingkat Provinsi Jawa Timur bidang CNC Milling. Prestasi ini membuktikan kualitas pendidikan vokasi di SMK YASMU yang terus meningkat.',
                'category' => 'Prestasi',
                'is_published' => true,
            ],
            [
                'title' => 'Tim Robotik SMK YASMU Lolos ke Final Nasional Kontes Robot Indonesia',
                'slug' => 'tim-robotik-final-nasional',
                'excerpt' => 'Tim robotik SMK YASMU berhasil lolos ke babak final Kontes Robot Indonesia 2025.',
                'content' => 'Tim robotik SMK YASMU yang dibimbing oleh Bapak Ahmad Fauzi, S.T., berhasil lolos ke babak final Kontes Robot Indonesia (KRI) 2025. Robot yang mereka buat berhasil menyelesaikan berbagai tantangan dengan baik di babak penyisihan regional. Final akan diselenggarakan di Jakarta bulan depan.',
                'category' => 'Prestasi',
                'is_published' => true,
            ],
            [
                'title' => 'Kunjungan Industri SMK YASMU ke PT. Madukismo Yogyakarta',
                'slug' => 'kunjungan-industri-madukismo',
                'excerpt' => 'Sebanyak 287 siswa melakukan kunjungan industri ke pabrik gula PT. Madukismo.',
                'content' => 'Dalam upaya berkelanjutan untuk memperkaya pengalaman belajar dan mendekatkan siswa dengan dunia industri secara nyata, SMK YASMU Gresik menghelat program kunjungan industri berskala besar. Pada tanggal 28 Januari 2025, sebanyak 287 siswa dari berbagai kompetensi keahlian, didampingi oleh 50 guru pembimbing, bertolak menuju PT. Madukismo di Daerah Istimewa Yogyakarta.',
                'category' => 'Kegiatan',
                'is_published' => true,
            ],
            [
                'title' => 'SMK YASMU Gelar Workshop Persiapan Menghadapi Era Industry 4.0',
                'slug' => 'workshop-industry-4',
                'excerpt' => 'Workshop bertema Industry 4.0 diikuti oleh seluruh siswa kelas XI dan XII.',
                'content' => 'SMK YASMU mengadakan workshop bertema "Persiapan Menghadapi Era Industry 4.0" yang diikuti oleh seluruh siswa kelas XI dan XII. Workshop ini menghadirkan narasumber dari berbagai perusahaan teknologi terkemuka untuk memberikan wawasan tentang perkembangan teknologi terkini dan keterampilan yang dibutuhkan di masa depan.',
                'category' => 'Kegiatan',
                'is_published' => true,
            ],
            [
                'title' => 'Pelaksanaan Praktik Kerja Lapangan (PKL) Siswa Kelas XII',
                'slug' => 'pkl-kelas-xii',
                'excerpt' => 'Siswa kelas XII memulai program PKL di berbagai perusahaan mitra.',
                'content' => 'Program Praktik Kerja Lapangan (PKL) untuk siswa kelas XII SMK YASMU resmi dimulai. Sebanyak 350 siswa dari 6 kompetensi keahlian telah ditempatkan di berbagai perusahaan mitra di wilayah Gresik, Surabaya, dan sekitarnya. Program PKL berlangsung selama 3 bulan untuk memberikan pengalaman kerja nyata kepada para siswa.',
                'category' => 'Kegiatan',
                'is_published' => true,
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }
}
