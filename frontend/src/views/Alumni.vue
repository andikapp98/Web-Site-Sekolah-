<template>
  <div class="page-wrapper">
    <PublicNavbar />
    
    <main class="content-main">
      <div class="page-header">
        <h1>ALUMNI & KARIR</h1>
        <p>Inspirasi dari mereka yang telah sukses berkarya</p>
      </div>

      <!-- Marquee content moved to static or filtered grid -->
      <section class="alumni-success">
        <div class="container">
          <div class="alumni-grid">
            <div v-for="(t, i) in allTestimonials" :key="i" class="alumni-card">
              <div class="alumni-photo">
                <img :src="t.photo" :alt="t.name" @error="(e) => e.target.src = '/default-avatar.png'" />
              </div>
              <div class="alumni-header">
                <h3>{{ t.name }}</h3>
                <span class="alumni-jurusan">{{ t.jurusan }}</span>
              </div>
              <div class="alumni-quote">
                <span class="quote-icon">“</span>
                <p>{{ t.quote }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Tracer Study Info -->
      <section class="tracer-study">
        <div class="tracer-container">
          <div class="tracer-text">
            <h2>Tracer Study</h2>
            <p>Kami bangga melihat lulusan kami berkontribusi di berbagai industri kelas dunia. 90% alumni kami langsung terserap di dunia industri dalam waktu 6 bulan setelah lulus.</p>
            <div class="tracer-stats">
              <div class="stat"><span>70%</span><p>Bekerja</p></div>
              <div class="stat"><span>20%</span><p>Wirausaha</p></div>
              <div class="stat"><span>10%</span><p>Kuliah</p></div>
            </div>
          </div>
          <div class="tracer-visual">📊</div>
        </div>
      </section>
    </main>

    <PublicFooter />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';

const testimonialRow1 = [
  { name: 'M. LUKMAN HAKIM', jurusan: 'ALUMNI TPM', photo: '/alumni/lukman.jpg', quote: 'SMK Yasmu bukan hanya ngajarin teori, tapi juga bikin aku siap terjun ke dunia kerja.' },
  { name: 'APRI SUGIAN HADY', jurusan: 'ALUMNI TKR', photo: '/alumni/apri.jpg', quote: 'Di sini aku belajar banyak hal baru, termasuk percaya diri dan kerja tim.' },
  { name: 'SITI KHOIROTUL', jurusan: 'ALUMNI DKV', photo: '/alumni/siti.jpg', quote: 'Jurusan DKV membuka banyak peluang kreatif untuk karirku.' },
  { name: 'AHMAD FAUZI', jurusan: 'ALUMNI TAPL', photo: '/alumni/fauzi.jpg', quote: 'Ilmu yang didapat sangat aplikatif di dunia kerja.' }
];

const testimonialRow2 = [
  { name: 'AZIMATUL ILMIYAH', jurusan: 'ALUMNI MM', photo: '/alumni/azimatul.jpg', quote: 'Bangga pernah jadi bagian dari SMK Yasmu - tempat aku tumbuh dan berkembang.' },
  { name: 'BUDI SANTOSO', jurusan: 'ALUMNI TPM', photo: '/alumni/budi.jpg', quote: 'Bekal praktek industri sangat membantu karir profesionalku.' },
  { name: 'DEWI RAHMAWATI', jurusan: 'ALUMNI TKR', photo: '/alumni/dewi.jpg', quote: 'Guru-guru di sini sangat supportif dan membimbing dengan tulus.' },
  { name: 'RENDI PRATAMA', jurusan: 'ALUMNI LOGISTIK', photo: '/alumni/rendi.jpg', quote: 'Program magang membuka jalan karirku di industri logistik.' }
];

const allTestimonials = computed(() => [...testimonialRow1, ...testimonialRow2]);
</script>

<style scoped>
.page-wrapper { min-height: 100vh; background: #fff; }
.content-main { margin-top: 80px; padding: 4rem 2rem; }

.page-header { text-align: center; margin-bottom: 5rem; }
.page-header h1 { font-size: 3rem; color: #155d27; font-weight: 800; }
.page-header p { color: #64748b; font-size: 1.2rem; }

.container { max-width: 1200px; margin: 0 auto; }

.alumni-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 6rem; }
.alumni-card { background: #fff; border: 1px solid #e5e7eb; padding: 2rem; border-radius: 20px; transition: all 0.3s; position: relative; overflow: hidden; }
.alumni-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); border-color: #155d27; }

.alumni-photo { width: 80px; height: 80px; border-radius: 50%; overflow: hidden; margin-bottom: 1.5rem; border: 3px solid #f0f9f4; }
.alumni-photo img { width: 100%; height: 100%; object-fit: cover; }

.alumni-header h3 { font-size: 1.2rem; color: #155d27; margin-bottom: 0.25rem; }
.alumni-jurusan { font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

.alumni-quote { position: relative; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9; }
.quote-icon { position: absolute; top: -10px; left: 0; font-size: 3rem; color: #f0fdf4; z-index: -1; }
.alumni-quote p { color: #475569; line-height: 1.6; font-style: italic; z-index: 1; }

.tracer-study { background: #155d27; color: #fff; padding: 6rem 2rem; border-radius: 40px; margin: 4rem 0; }
.tracer-container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr; gap: 4rem; align-items: center; }
.tracer-text h2 { font-size: 2.5rem; margin-bottom: 1.5rem; }
.tracer-text p { font-size: 1.1rem; opacity: 0.9; line-height: 1.8; margin-bottom: 3rem; }

.tracer-stats { display: flex; gap: 3rem; }
.stat span { display: block; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; color: #fbbf24; }
.stat p { font-size: 1rem; opacity: 0.8; }

.tracer-visual { font-size: 10rem; text-align: center; }

@media (max-width: 768px) {
  .tracer-container { grid-template-columns: 1fr; text-align: center; }
  .tracer-visual { display: none; }
  .tracer-stats { justify-content: center; gap: 1.5rem; }
  .stat span { font-size: 2rem; }
}
</style>
