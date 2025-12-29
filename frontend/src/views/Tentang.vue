<template>
  <div class="page-wrapper">
    <PublicNavbar />
    
    <main class="content-main">
      <div class="page-header">
        <h1>TENTANG KAMI</h1>
        <p>Mengenal lebih dekat SMK YASMU Gresik</p>
      </div>

      <!-- Content from Why Section -->
      <section class="why-section">
        <div class="why-container">
          <div class="why-content">
            <h2 class="section-title">Kenapa Harus SMK YASMU?</h2>
            <p class="section-desc">SMK YASMU Manyar Gresik merupakan sekolah kejuruan yang terus berkembang sejak 1999, dengan enam kompetensi keahlian dan lebih dari 4.000 lulusan hingga tahun 2025.</p>
            
            <div class="why-cards">
              <div v-for="(item, index) in whyItems" :key="index" class="why-card">
                <div class="why-icon">{{ item.icon }}</div>
                <h3>{{ item.title }}</h3>
                <p>{{ item.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Vision & Mission -->
      <section class="vision-mission">
        <div class="container">
          <div class="vision">
            <h2>VISI</h2>
            <p>"Menjadi Lembaga Pendidikan Kejuruan yang Unggul, Berkarakter, Berbudaya Lingkungan, dan Berdaya Saing Global."</p>
          </div>
        </div>
      </section>
    </main>

    <PublicFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';

const whyItems = ref([
  { title: 'Kurikulum Industri', icon: '⚙️', desc: 'Kurikulum kami disusun bersama mitra industri strategis untuk memastikan kesesuaian kompetensi lulusan dengan kebutuhan lapangan kerja.' },
  { title: 'Fasilitas Modern', icon: '💻', desc: 'Laboratorium dan workshop lengkap dengan peralatan standar industri untuk menunjang kegiatan praktek siswa secara maksimal.' },
  { title: 'Tenaga Ahli', icon: '👨‍🏫', desc: 'Guru-guru kami adalah praktisi dan akademisi berpengalaman yang memiliki sertifikasi kompetensi di bidangnya masing-masing.' },
  { title: 'Jejaring Lulusan', icon: '🤝', desc: 'Kami memiliki jejaring alumni yang kuat di berbagai perusahaan nasional dan internasional, memudahkan akses peluang kerja.' }
]);
</script>

<style scoped>
.page-wrapper { min-height: 100vh; background: #fff; }
.content-main { margin-top: 80px; padding: 4rem 2rem; }

.page-header { text-align: center; margin-bottom: 4rem; }
.page-header h1 { font-size: 3rem; color: #155d27; font-weight: 800; }
.page-header p { color: #64748b; font-size: 1.2rem; }

.container { max-width: 1200px; margin: 0 auto; }

.why-section { background: #f8faf9; padding: 6rem 2rem; border-radius: 40px; margin-bottom: 4rem; }
.section-title { font-size: 2.5rem; color: #155d27; text-align: center; margin-bottom: 1rem; }
.section-desc { text-align: center; max-width: 800px; margin: 0 auto 4rem; color: #475569; line-height: 1.7; }

.why-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; }
.why-card { background: #fff; padding: 3rem 2rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s; }
.why-card:hover { transform: translateY(-10px); }
.why-icon { font-size: 3rem; margin-bottom: 1.5rem; }
.why-card h3 { color: #155d27; margin-bottom: 1rem; font-size: 1.3rem; }
.why-card p { color: #64748b; line-height: 1.6; font-size: 0.95rem; }

.vision-mission { padding: 4rem 2rem; text-align: center; }
.vision h2 { color: #155d27; font-size: 2rem; margin-bottom: 1.5rem; }
.vision p { font-size: 1.4rem; color: #334155; font-style: italic; max-width: 900px; margin: 0 auto; line-height: 1.6; }

@media (max-width: 768px) {
  .page-header h1 { font-size: 2rem; }
  .section-title { font-size: 1.8rem; }
}
</style>
