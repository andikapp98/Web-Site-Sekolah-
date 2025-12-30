<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Article;
use App\Models\Program;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hero Slides
        $this->seedHeroSlides();
        
        // Programs (Jurusan)
        $this->seedPrograms();
        
        // Achievements (Jejak Gemilang)
        $this->seedAchievements();
        
        // Partners (Mitra)
        $this->seedPartners();
        
        // Testimonials (Alumni)
        $this->seedTestimonials();
    }

    private function seedHeroSlides(): void
    {
        $slides = [
            [
                'title' => null,
                'description' => null,
                'image' => 'hero/Frame 313.png',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'BEKALI SISWA HADAPI DUNIA INDUSTRI',
                'description' => 'SMK Yasmu gelar pembekalan PKL untuk siswa kelas XI.',
                'image' => 'hero/Frame 313.png',
                'button_text' => 'Selengkapnya',
                'button_link' => '#',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'INOVASI HIJAU: UBAH SAMPAH JADI PUPUK',
                'description' => 'Program lingkungan mengolah sampah organik menjadi pupuk.',
                'image' => 'hero/Frame 313.png',
                'button_text' => 'Selengkapnya',
                'button_link' => '#',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'PRESTASI GEMILANG SISWA SMK YASMU',
                'description' => 'Meraih berbagai prestasi tingkat regional dan nasional.',
                'image' => 'hero/Frame 313.png',
                'button_text' => 'Selengkapnya',
                'button_link' => '#',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }
    }

    private function seedPrograms(): void
    {
        $programs = [
            [
                'name' => 'Teknik Pemesinan',
                'slug' => 'teknik-pemesinan',
                'short_name' => 'TPM',
                'description' => 'Teknik Pemesinan mempersiapkan siswa untuk menjadi tenaga ahli yang profesional dan terampil dalam mengoperasikan mesin-mesin industri. Pembelajaran mencakup seluruh aspek permesinan.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Teknik Kendaraan Ringan Otomotif',
                'slug' => 'teknik-otomotif',
                'short_name' => 'TKR',
                'description' => 'Teknik Otomotif mempersiapkan siswa untuk menjadi tenaga mekanik yang profesional dan terampil dalam menangani kendaraan ringan (mobil). Pembelajaran mencakup seluruh aspek.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Desain Komunikasi Visual',
                'slug' => 'desain-komunikasi-visual',
                'short_name' => 'DKV',
                'description' => 'Desain Komunikasi Visual mempersiapkan siswa untuk menjadi tenaga kreatif yang profesional dalam bidang desain grafis, multimedia, dan videografi.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Teknik Analisis Pengujian Laboratorium',
                'slug' => 'teknik-analisis-pengujian-laboratorium',
                'short_name' => 'TAPL',
                'description' => 'Analisis Pengujian Lab mempersiapkan siswa untuk menjadi tenaga analis yang profesional dalam laboratorium industri dan pengujian kualitas produk.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Manajemen Perkantoran dan Layanan Bisnis',
                'slug' => 'manajemen-perkantoran',
                'short_name' => 'MPLB',
                'description' => 'Manajemen Perkantoran mempersiapkan siswa untuk menjadi tenaga administrasi profesional yang terampil dalam pengelolaan kantor modern.',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Teknik Logistik',
                'slug' => 'teknik-logistik',
                'short_name' => 'LOG',
                'description' => 'Teknik Logistik mempersiapkan siswa untuk menjadi tenaga ahli dalam manajemen rantai pasokan dan distribusi barang.',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }

    private function seedAchievements(): void
    {
        $achievements = [
            [
                'title' => 'Laga Sengit di Final HMM Futsal Championship, Tim Futsal SMK YASMU Raih Posisi Runner-Up',
                'excerpt' => 'GRESIK – Atmosfer Gedung Wahana Ekspresi Poesponegoro (WEP) bergemuruh oleh sorak-sorai ratusan suporter pada Jumat sore (3/3/2023) lalu. Mereka menjadi saksi laga pu...',
                'category' => 'KEGIATAN',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Lanjutkan Tren Juara, BRIGPASMU SMK YASMU Sabet Juara Caraka 2 di LKBB Tingkat Jawa Timur',
                'excerpt' => 'GRESIK – Konsistensi dalam berprestasi kembali dibuktikan oleh tim Paskibra SMK YASMU Manyar. Usai meraih sukses di tingkat kabupaten, tim BRIGPASMU (Brigadir Pasuka...',
                'category' => 'KEGIATAN',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'BRIGPASMU SMK YASMU Gresik Torehkan Prestasi Gemilang, Juarai LKBB GAPURA 4.0',
                'excerpt' => 'GRESIK – Prestasi membanggakan kembali diukir oleh siswa-siswi SMK YASMU Manyar, Kabupaten Gresik. Kali ini, tim Paskibra sekolah yang dikenal dengan nama BRIGPAS...',
                'category' => 'KEGIATAN',
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }

    private function seedPartners(): void
    {
        $partners = [
            ['name' => 'Indopipe', 'order' => 1, 'is_active' => true],
            ['name' => 'PT Swadaya Graha', 'order' => 2, 'is_active' => true],
            ['name' => 'JD Global', 'order' => 3, 'is_active' => true],
            ['name' => 'PT Indo Bismar', 'order' => 4, 'is_active' => true],
            ['name' => 'Jindal Stainless', 'order' => 5, 'is_active' => true],
            ['name' => 'PT Petrokimia Gresik', 'order' => 6, 'is_active' => true],
            ['name' => 'PT Semen Indonesia', 'order' => 7, 'is_active' => true],
            ['name' => 'PT United Steel Center', 'order' => 8, 'is_active' => true],
        ];

        foreach ($partners as $partner) {
            Partner::create($partner);
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'M. LUKMAN HAKIM',
                'jurusan' => 'ALUMNI TPM',
                'quote' => 'SMK Yasmu bukan hanya ngajarin teori, tapi juga bikin aku siap terjun ke dunia kerja.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'APRI SUGIAN HADY',
                'jurusan' => 'ALUMNI TKR',
                'quote' => 'Di sini aku belajar banyak hal baru, termasuk percaya diri dan kerja tim.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'SITI KHOIROTUL',
                'jurusan' => 'ALUMNI DKV',
                'quote' => 'Jurusan DKV membuka banyak peluang kreatif untuk karirku.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'AHMAD FAUZI',
                'jurusan' => 'ALUMNI TAPL',
                'quote' => 'Ilmu yang didapat sangat aplikatif di dunia kerja.',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'AZIMATUL ILMIYAH',
                'jurusan' => 'ALUMNI MPLB',
                'quote' => 'Bangga pernah jadi bagian dari SMK Yasmu - tempat aku tumbuh dan berkembang.',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'BUDI SANTOSO',
                'jurusan' => 'ALUMNI TPM',
                'quote' => 'Bekal praktek industri sangat membantu karir profesionalku.',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'DEWI RAHMAWATI',
                'jurusan' => 'ALUMNI TKR',
                'quote' => 'Guru-guru di sini sangat supportif dan membimbing dengan tulus.',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'RENDI PRATAMA',
                'jurusan' => 'ALUMNI LOG',
                'quote' => 'Program magang membuka jalan karirku di industri logistik.',
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
