<template>
  <div class="detail-wrapper" v-if="program">
    <PublicNavbar />
    
    <main class="detail-main">
      <!-- Hero Section -->
      <section class="hero-section">
        <!-- Background media (Video or Image) -->
        <div class="hero-bg">
          <div class="overlay"></div>
          <video 
            v-if="program.video" 
            :src="getVideoUrl(program.video)" 
            autoplay 
            loop 
            muted 
            playsinline
            class="hero-media"
          ></video>
          <img 
            v-else 
            :src="getImageUrl(program.image)" 
            :alt="program.name" 
            class="hero-media"
          />
        </div>

        <!-- Back Button -->
        <button class="back-btn" @click="goBack">
          <ArrowLeft :size="28" />
        </button>

        <!-- Content Card -->
        <div class="content-card">
          <h1 class="program-title" v-html="formatTitle(program.name)"></h1>
          <div class="program-desc">
            <p><strong>Jurusan {{ program.name }}</strong> {{ program.description }}</p>
            <p v-if="program.full_description">{{ program.full_description }}</p>
          </div>
        </div>
      </section>

      <!-- Cards Section - Layout desain baru -->
      <section class="cards-section">
        <div class="cards-container">
          <!-- Row 1: Peluang Karir + Kompetensi Lulusan (SLIDER) -->
          <div class="cards-row top-row">
            <!-- Peluang Karir Card -->
            <div 
              class="detail-card career-card"
              :class="{ 'card-active': activeCard === 0 }"
              @click="toggleCard(0)"
            >
              <div class="card-icon-bg">
                <svg viewBox="0 0 24 24" fill="currentColor" opacity="0.15">
                  <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.58 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                </svg>
              </div>
              <h3>PELUANG<br>KARIR</h3>
              <p v-if="activeCard === 0" class="card-text">{{ program.career_opportunities || detailData.career }}</p>
            </div>

            <!-- Kompetensi Lulusan Card -->
            <div 
              class="detail-card competency-card"
              :class="{ 'card-active': activeCard === 1 }"
              @click="toggleCard(1)"
            >
              <h3>KOMPETENSI<br>LULUSAN</h3>
              <div class="card-icon-bg small">
                <svg viewBox="0 0 24 24" fill="currentColor" opacity="0.15">
                  <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.58 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                </svg>
              </div>
              <div v-if="activeCard === 1" class="competency-with-image">
                <div class="competency-text">
                  <p v-if="!hasCompetencies">{{ detailData.competencyText }}</p>
                  <ul v-else class="detail-list">
                    <li v-for="(comp, i) in displayCompetencies" :key="i">{{ comp }}</li>
                  </ul>
                </div>
                <div class="card-person-image">
                  <img :src="getImageUrl(program.career_image) || 'https://img.freepik.com/free-photo/mechanical-engineer-working-his-workshop_23-2148905252.jpg'" alt="Siswa" />
                </div>
              </div>
            </div>
          </div>

          <!-- Row 2: Ruang Praktek + Alat Praktek + Gambar -->
          <div class="cards-row bottom-row">
            <!-- Ruang Praktek Card -->
            <div class="detail-card facility-card">
              <h3>RUANG<br>PRAKTEK</h3>
              <ol class="detail-list numbered">
                <li v-for="(room, i) in displayFacilities" :key="i">{{ room }}</li>
              </ol>
            </div>

            <!-- Alat Praktek Card -->
            <div class="detail-card equipment-card">
              <h3>ALAT<br>PRAKTEK</h3>
              <ol class="detail-list numbered">
                <li v-for="(tool, i) in displayEquipment" :key="i">{{ tool }}</li>
              </ol>
            </div>

            <!-- Workshop Image/Gallery -->
            <div class="workshop-gallery">
              <img :src="getImageUrl(program.workshop_image) || getImageUrl(detailData.facilityImage)" alt="Kegiatan Praktek" />
              <div class="gallery-nav">
                <button class="nav-btn">&lt;</button>
                <span>10 / 39</span>
                <button class="nav-btn">&gt;</button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Scroll to Top -->
      <button class="scroll-top" @click="scrollToTop">
        <ArrowUp :size="24" />
      </button>
    </main>
  </div>
  <div v-else-if="loading" class="loading-state">
    <span class="loader"></span>
  </div>
  <div v-else class="error-state">
    <p>Jurusan tidak ditemukan</p>
    <button @click="goBack" class="btn-primary">Kembali</button>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { ArrowLeft, ArrowUp } from 'lucide-vue-next';
import PublicNavbar from '../components/PublicNavbar.vue';
import { publicApi } from '../services/api';

const route = useRoute();
const router = useRouter();
const program = ref(null);
const loading = ref(true);
const activeCard = ref(-1); // -1 = none active, 0 = career, 1 = competency

const toggleCard = (index) => {
  activeCard.value = activeCard.value === index ? -1 : index;
};

// Computed untuk menampilkan data dari database atau fallback
const hasCompetencies = computed(() => {
  return program.value?.competencies && program.value.competencies.length > 0;
});

const displayCompetencies = computed(() => {
  if (program.value?.competencies && program.value.competencies.length > 0) {
    return program.value.competencies;
  }
  return detailData.value.competencies;
});

const displayFacilities = computed(() => {
  if (program.value?.facilities && program.value.facilities.length > 0) {
    return program.value.facilities;
  }
  return detailData.value.rooms;
});

const displayEquipment = computed(() => {
  if (program.value?.equipment && program.value.equipment.length > 0) {
    return program.value.equipment;
  }
  return detailData.value.tools;
});

// Mock Data Extension (Ideally this comes from API/DB later)
const detailData = computed(() => {
  const defaultData = {
    career: 'Lulusan dapat bekerja di berbagai sektor industri sebagai teknisi, operator, atau wirausaha.',
    competencyText: 'Lulusan dibekali dengan kompetensi yang lengkap untuk melakukan perawatan dan perbaikan pada kendaraan ringan. Kompetensi ini mencakup kemampuan mendiagnosis kerusakan, melakukan servis berkala (tune-up), serta menangani perbaikan pada berbagai sistem vital kendaraan.',
    competencies: ['Kompetensi Dasar Kejuruan', 'Kompetensi Kejuruan Spesifik', 'Wirausaha & Produk Kreatif'],
    rooms: ['Ruang Teori', 'Bengkel Praktek'],
    tools: ['Peralatan Tangan', 'Mesin Dasar'],
    facilityImage: '/slide-3.jpg'
  };

  if (program.value) {
    const slug = (program.value.slug || '').toLowerCase();
    const name = (program.value.name || '').toLowerCase();

    if (slug.includes('pemesinan') || name.includes('pemesinan') || slug.includes('tpm')) {
      return {
        career: 'Peluang karir bagi lulusan Teknik Pemesinan sangat luas dan tersebar di berbagai sektor industri manufaktur. Mereka dapat berkarir sebagai Operator Mesin Bubut, Frais, Gerinda, maupun Operator CNC yang sangat dibutuhkan di pabrik otomotif, alat berat, dan komponen presisi. Posisi lain meliputi Teknisi Permesinan, Drafter, hingga Quality Control (QC).',
        competencyText: 'Lulusan memiliki kompetensi dalam pengoperasian mesin konvensional dan CNC, pemahaman gambar teknik, serta kemampuan quality control yang memadai untuk industri manufaktur.',
        competencies: ['Gambar Teknik Manufaktur', 'Teknologi Mekanik', 'Teknik Pemesinan Bubut & Frais', 'Teknik Pemesinan Gerinda', 'Teknik Pemesinan NC/CNC & CAM'],
        rooms: ['Ruang Praktek Kerja Bangku', 'Ruang Praktek Mesin RPS', 'Ruang Praktek CNC', 'Ruang Praktek Gambar Manufaktur', 'Ruang Toolman'],
        tools: ['Mesin RPS', 'Mesin Bubut, Mesin Frais, Mesin LAS', 'Mesin gerinda datar (surface grinding)', 'Mesin Bubut Konvensional & CNC', 'Mesin Frais Konvensional & CNC'],
        facilityImage: '/slide-1.jpg'
      };
    }

    if (slug.includes('otomotif') || name.includes('otomotif') || slug.includes('tkr')) {
      return {
        career: 'Lulusan dapat bekerja sebagai Teknisi Otomotif, Mekanik Kendaraan, Service Advisor, Parts Specialist, atau membuka bengkel otomotif sendiri. Peluang karir juga terbuka di dealer resmi, bengkel umum, dan industri otomotif.',
        competencyText: 'Lulusan jurusan Teknik Otomotif dibekali dengan kompetensi yang lengkap untuk melakukan perawatan dan perbaikan pada kendaraan ringan. Kompetensi ini mencakup kemampuan mendiagnosis kerusakan, melakukan servis berkala (tune-up), serta menangani perbaikan pada berbagai sistem vital kendaraan, meliputi mesin, sasis, sistem pemindah tenaga, suspensi, rem, dan sistem kelistrikan termasuk Air Conditioner (AC).',
        competencies: ['Perawatan Mesin Kendaraan', 'Sistem Kelistrikan Otomotif', 'Sistem Pemindah Tenaga', 'Sistem Chasis & Suspensi', 'Diagnosa Kerusakan Kendaraan'],
        rooms: ['Ruang Praktek Otomotif', 'Ruang Bengkel', 'Ruang Praktek Gambar', 'Ruang Praktek Kelistrikan', 'Ruang Engine Stand'],
        tools: ['Mesin Spooring, Mesin Balancing, Mesin VVTI', 'Komenrail, Scantools, Injector Cleaner', 'Engine Stand, Trainer AC', 'Hydraulic Lift, Jack Stand', 'Diagnostic Tools'],
        facilityImage: '/slide-2.jpg'
      };
    }
  }
  return defaultData;
});

const loadData = async () => {
  loading.value = true;
  try {
    const slug = route.params.slug;
    const response = await publicApi.getProgram(slug);
    program.value = response.data.data || response.data;
  } catch (error) {
    console.error('Error loading program:', error);
  } finally {
    loading.value = false;
  }
};

const goBack = () => {
  router.push('/jurusan');
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const getImageUrl = (path) => {
  if (!path) return '/slide-1.jpg';
  if (path.startsWith('http') || path.startsWith('/')) return path;
  return `http://localhost:8000/storage/${path}`;
};

const getVideoUrl = (path) => {
  if (!path) return null;
  if (path.startsWith('http')) return path;
  return `http://localhost:8000/storage/${path}`;
};

const formatTitle = (name) => {
  if (!name) return '';
  return name.replace(' ', '<br>');
};

onMounted(loadData);
</script>

<style scoped>
.detail-wrapper {
  position: relative;
  min-height: 100vh;
  background: #fff;
}

.detail-main {
  width: 100%;
}

/* Hero Section */
.hero-section {
  position: relative;
  height: 100vh;
  width: 100%;
  overflow: hidden;
  display: flex;
  align-items: center;
  padding: 0 5%;
}

.hero-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 0;
}

.hero-media {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.25);
  z-index: 1;
}

.back-btn {
  position: absolute;
  top: 100px;
  left: 5%;
  z-index: 20;
  background: #155d27;
  color: white;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.back-btn:hover {
  background: #0d4f1f;
  transform: scale(1.1);
}

.content-card {
  position: relative;
  z-index: 10;
  background: rgba(255, 255, 255, 0.45);
  backdrop-filter: blur(15px);
  padding: 3rem;
  border-radius: 20px;
  max-width: 600px;
  margin-top: 80px;
  border: 1px solid rgba(255, 255, 255, 0.4);
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.program-title {
  font-size: 3.5rem;
  font-weight: 800;
  color: #155d27;
  line-height: 1.1;
  margin-bottom: 1.5rem;
  text-transform: uppercase;
}

.program-desc p {
  font-size: 1.1rem;
  line-height: 1.6;
  color: #0f172a;
  text-align: justify;
  margin-bottom: 1rem;
}

/* Cards Section */
.cards-section {
  padding: 3rem 5%;
  background: #fff;
}

.cards-container {
  max-width: 1400px;
  margin: 0 auto;
}

.cards-row {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

/* Top Row - Slider Style */
.top-row {
  height: 320px;
}

/* Detail Card Base */
.detail-card {
  background: #e9f2eb;
  border-radius: 30px 5px 30px 5px;
  padding: 2rem;
  cursor: pointer;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.detail-card h3 {
  color: #155d27;
  font-size: 1.6rem;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  position: relative;
  z-index: 2;
}

.card-icon-bg {
  position: absolute;
  left: 1rem;
  bottom: 1rem;
  width: 120px;
  height: 120px;
  color: #155d27;
  z-index: 1;
}

.card-icon-bg.small {
  width: 80px;
  height: 80px;
  right: 1rem;
  left: auto;
  top: auto;
  bottom: 1rem;
}

.card-icon-bg svg {
  width: 100%;
  height: 100%;
}

/* Career Card - Slider */
.career-card {
  flex: 0 0 280px;
  min-width: 280px;
  height: 100%;
}

.career-card.card-active {
  flex: 1;
}

.expanded-text {
  animation: fadeSlideIn 0.4s ease;
  font-size: 0.95rem;
  line-height: 1.7;
  color: #1e293b;
  text-align: justify;
  position: relative;
  z-index: 2;
  overflow-y: auto;
  max-height: 200px;
}

/* Competency Card - Slider */
.competency-card {
  flex: 0 0 280px;
  min-width: 280px;
  height: 100%;
  background: #d4d4d4;
}

.competency-card.card-active {
  flex: 1;
}

.competency-header {
  position: relative;
}

.competency-with-image {
  display: flex;
  gap: 1.5rem;
  flex: 1;
  animation: fadeSlideIn 0.4s ease;
  overflow: hidden;
}

.competency-text {
  flex: 1;
}

.competency-text p {
  color: #1e293b;
  font-size: 0.95rem;
  line-height: 1.7;
  text-align: justify;
}

.card-person-image {
  flex-shrink: 0;
  width: 180px;
  border-radius: 15px;
  overflow: hidden;
}

.card-person-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

@keyframes fadeSlideIn {
  from { opacity: 0; transform: translateX(-10px); }
  to { opacity: 1; transform: translateX(0); }
}

/* Bottom Row - Static */
.bottom-row {
  display: flex;
}

.facility-card,
.equipment-card {
  flex: 1;
  min-height: 280px;
  cursor: default;
}

.workshop-gallery {
  flex: 1.5;
  border-radius: 20px;
  overflow: hidden;
  position: relative;
  min-height: 280px;
}

.workshop-gallery img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.gallery-nav {
  position: absolute;
  bottom: 1rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  align-items: center;
  gap: 1rem;
  background: rgba(255, 255, 255, 0.95);
  padding: 0.5rem 1rem;
  border-radius: 20px;
}

.nav-btn {
  width: 30px;
  height: 30px;
  border: none;
  background: #155d27;
  color: white;
  border-radius: 50%;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.2s;
}

.nav-btn:hover {
  background: #0d4f1f;
}

.gallery-nav span {
  font-size: 0.9rem;
  color: #333;
  font-weight: 500;
}

/* Detail List */
.detail-list {
  list-style: none;
  padding: 0;
  margin: 0.5rem 0 0;
}

.detail-list li {
  color: #1e293b;
  font-size: 0.9rem;
  font-weight: 500;
  padding: 0.3rem 0;
  display: flex;
  align-items: flex-start;
}

.detail-list li::before {
  content: "•";
  margin-right: 8px;
  color: #155d27;
  font-weight: bold;
}

.detail-list.numbered {
  counter-reset: item;
}

.detail-list.numbered li::before {
  counter-increment: item;
  content: counter(item) ".";
  min-width: 20px;
  font-weight: 700;
}

/* Responsive */
@media (max-width: 1024px) {
  .top-row {
    height: auto;
    flex-direction: column;
  }
  
  .career-card,
  .competency-card {
    flex: none;
    width: 100%;
    min-height: 200px;
  }
  
  .bottom-row {
    flex-wrap: wrap;
  }
  
  .facility-card,
  .equipment-card {
    flex: 1 1 45%;
  }
  
  .workshop-gallery {
    flex: 1 1 100%;
    margin-top: 1rem;
  }
  
  .competency-with-image {
    flex-direction: column;
  }
  
  .card-person-image {
    width: 100%;
    height: 180px;
  }
}

@media (max-width: 640px) {
  .facility-card,
  .equipment-card {
    flex: 1 1 100%;
  }
  
  .detail-card h3 {
    font-size: 1.4rem;
  }
}

.scroll-top {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  width: 50px;
  height: 50px;
  background: #155d27;
  color: white;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
}

.scroll-top:hover {
  background: #0d4f1f;
  transform: translateY(-3px);
}

.loading-state, .error-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
}

/* Additional Responsive */
@media (max-width: 968px) {
  .program-title { font-size: 2.5rem; }
  .content-card { padding: 2rem; margin: 80px 1rem 0; width: auto; }
}

</style>


