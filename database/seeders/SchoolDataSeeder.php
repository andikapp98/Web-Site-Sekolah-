<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class SchoolDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedSchoolProfiles();
        $this->seedTeachers();
    }

    private function seedSchoolProfiles(): void
    {
        $profiles = [
            [
                'key' => 'principal_message',
                'title' => 'Sambutan Kepala Sekolah',
                'subtitle' => 'H. MOH. SHOLEH, S.Pd., M.Pd.',
                'content' => 'Assalamu\'alaikum Warahmatullahi Wabarakatuh.

Puji syukur kita panjatkan kehadirat Allah SWT yang telah melimpahkan rahmat dan hidayah-Nya kepada kita semua.

Selamat datang di website resmi SMK YASMU Manyar Gresik. Website ini merupakan media informasi dan komunikasi bagi seluruh civitas akademika SMK YASMU, orang tua/wali siswa, dunia usaha dan industri, serta masyarakat luas.

SMK YASMU Manyar Gresik berkomitmen untuk menghasilkan lulusan yang kompeten, berkarakter, dan siap bersaing di dunia kerja maupun melanjutkan ke jenjang pendidikan yang lebih tinggi. Dengan motto "Disiplin Kunci Sukses & Berprestasi", kami terus berupaya meningkatkan kualitas pendidikan melalui pembelajaran berbasis industri dan penguatan karakter.

Kami berharap website ini dapat memberikan informasi yang bermanfaat bagi seluruh pemangku kepentingan. Kritik dan saran yang membangun sangat kami harapkan untuk kemajuan bersama.

Wassalamu\'alaikum Warahmatullahi Wabarakatuh.',
                'metadata' => [
                    'position' => 'Kepala Sekolah',
                    'nip' => '196812121990031015',
                ],
                'is_active' => true,
            ],
            [
                'key' => 'vision',
                'title' => 'Visi',
                'content' => 'Menjadi SMK yang unggul, berkarakter, dan berdaya saing global dalam menyiapkan sumber daya manusia yang kompeten di bidang teknologi dan industri.',
                'is_active' => true,
            ],
            [
                'key' => 'mission',
                'title' => 'Misi',
                'content' => '1. Menyelenggarakan pendidikan kejuruan yang berkualitas sesuai dengan kebutuhan dunia usaha dan industri.
2. Mengembangkan pembelajaran berbasis teknologi informasi dan komunikasi.
3. Menumbuhkan semangat keunggulan dan daya saing warga sekolah.
4. Menanamkan nilai-nilai akhlak mulia, disiplin, dan tanggung jawab.
5. Membekali peserta didik dengan jiwa kewirausahaan dan kemandirian.
6. Menjalin kerjasama dengan dunia usaha dan industri dalam pengembangan kurikulum dan penempatan lulusan.
7. Mengembangkan potensi peserta didik dalam bidang akademik, non-akademik, dan keterampilan vokasional.',
                'is_active' => true,
            ],
            [
                'key' => 'history',
                'title' => 'Sejarah Sekolah',
                'content' => 'SMK YASMU (Yayasan Sunan Muria) Manyar Gresik didirikan pada tahun 1999 sebagai wujud kepedulian masyarakat terhadap pendidikan kejuruan di wilayah Gresik.

Berawal dari 2 program keahlian yaitu Teknik Pemesinan dan Teknik Kendaraan Ringan, SMK YASMU terus berkembang hingga kini memiliki 6 program keahlian yang relevan dengan kebutuhan industri.

Sejak berdiri hingga tahun 2025, SMK YASMU telah meluluskan lebih dari 4.000 alumni yang tersebar di berbagai perusahaan nasional dan multinasional. Dengan status sebagai SMK Pusat Keunggulan, SMK YASMU menjadi rujukan bagi sekolah swasta lainnya di Kabupaten Gresik.',
                'metadata' => [
                    'founded_year' => 1999,
                    'total_alumni' => 4309,
                    'accreditation' => 'A',
                ],
                'is_active' => true,
            ],
            [
                'key' => 'school_info',
                'title' => 'Informasi Sekolah',
                'content' => 'SMK YASMU Manyar Gresik',
                'metadata' => [
                    'npsn' => '20500123',
                    'status' => 'Swasta',
                    'accreditation' => 'A',
                    'address' => 'Jl. Raya Manyar KM. 15, Manyar, Gresik, Jawa Timur 61151',
                    'phone' => '(031) 3951234',
                    'email' => 'info@smkyasmu.sch.id',
                    'website' => 'https://smkyasmu.sch.id',
                    'founded_year' => 1999,
                    'total_students' => 818,
                    'total_teachers' => 50,
                    'total_alumni' => 4309,
                ],
                'is_active' => true,
            ],
        ];

        foreach ($profiles as $profile) {
            SchoolProfile::create($profile);
        }
    }

    private function seedTeachers(): void
    {
        $teachers = [
            [
                'name' => 'H. MOH. SHOLEH, S.Pd., M.Pd.',
                'position' => 'Kepala Sekolah',
                'education' => 'S2 Pendidikan',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Drs. AHMAD FAUZI, M.M.',
                'position' => 'Wakil Kepala Sekolah',
                'subject' => 'Kurikulum',
                'education' => 'S2 Manajemen',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'SITI RAHAYU, S.Pd.',
                'position' => 'Wakil Kepala Sekolah',
                'subject' => 'Kesiswaan',
                'education' => 'S1 Pendidikan',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'BUDI SANTOSO, S.T.',
                'position' => 'Wakil Kepala Sekolah',
                'subject' => 'Hubungan Industri',
                'education' => 'S1 Teknik',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'AHMAD RIZKI, S.T.',
                'position' => 'Guru',
                'subject' => 'Teknik Pemesinan',
                'education' => 'S1 Teknik Mesin',
                'program_id' => 1,
                'order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'DEWI LESTARI, S.Pd.',
                'position' => 'Guru',
                'subject' => 'Teknik Kendaraan Ringan',
                'education' => 'S1 Pendidikan Teknik Otomotif',
                'program_id' => 2,
                'order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'RINI WULANDARI, S.Ds.',
                'position' => 'Guru',
                'subject' => 'Desain Komunikasi Visual',
                'education' => 'S1 Desain Komunikasi Visual',
                'program_id' => 3,
                'order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. ANDI PRATAMA, M.Si.',
                'position' => 'Guru',
                'subject' => 'Teknik Analisis Lab',
                'education' => 'S3 Kimia',
                'program_id' => 4,
                'order' => 13,
                'is_active' => true,
            ],
            [
                'name' => 'LISA ANDRIANI, S.E.',
                'position' => 'Guru',
                'subject' => 'Manajemen Perkantoran',
                'education' => 'S1 Ekonomi',
                'program_id' => 5,
                'order' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'HENDRA WIJAYA, S.Log.',
                'position' => 'Guru',
                'subject' => 'Teknik Logistik',
                'education' => 'S1 Logistik',
                'program_id' => 6,
                'order' => 15,
                'is_active' => true,
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }
    }
}
