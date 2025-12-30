<template>
  <div class="page-wrapper">
    <PublicNavbar />
    
    <main class="content-main">
      <!-- Header -->
      <div class="page-header">
        <button class="back-btn" @click="$router.push('/')">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
        </button>
        <h1 class="page-title">SEJARAH SMK YASMU</h1>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="loading-state">
        <span class="loader"></span>
        <p>Memuat data...</p>
      </div>

      <template v-else>
        <!-- History Content -->
        <section class="history-section">
          <div class="history-grid">
            <div class="history-image">
              <img :src="schoolImage" alt="SMK YASMU Building" @error="handleImageError" />
            </div>
            <div class="history-text">
              <p class="history-content" v-for="(paragraph, index) in historyParagraphs" :key="index">
                {{ paragraph }}
              </p>
            </div>
          </div>
        </section>

        <!-- Principal Message Section (if available) -->
        <section v-if="principalData" class="principal-section">
          <h2 class="section-title">SAMBUTAN KEPALA SEKOLAH</h2>
          <div class="principal-card">
            <div class="principal-image" v-if="principalData.image">
              <img :src="getImageUrl(principalData.image)" alt="Kepala Sekolah" />
            </div>
            <div class="principal-content">
              <p class="principal-message">{{ principalData.content }}</p>
              <div class="principal-name">
                <strong>{{ principalData.subtitle || 'Kepala Sekolah' }}</strong>
                <span>SMK YASMU Gresik</span>
              </div>
            </div>
          </div>
        </section>
      </template>
    </main>

    <PublicFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';
import { publicApi } from '../services/api';

const router = useRouter();
const loading = ref(true);
const profiles = ref({});

// Default fallback data
const fallbackHistory = `Sebagai institusi pendidikan vokasi yang terkemuka di bawah naungan Yayasan Mu'allimin Mu'allimat (YASMU) Manyar Gresik, Sekolah Menengah Kejuruan Yasmu (SMK YASMU) telah memantapkan posisinya sebagai pilar utama dalam penyiapan sumber daya manusia yang andal. Sejak didirikan secara resmi pada tahun 1999, SMK YASMU mengembangkan misi mulia untuk melahirkan generasi profesional yang tidak hanya terampil secara teknis, tetapi juga memiliki daya saing tinggi untuk menjawab tantangan dunia industri.

Perjalanan bersejarah ini diawali dengan pembukaan dua kompetensi keahlian yang menjadi fondasi awal, yakni Teknik Pemesinan dan Teknik Otomotif. Visi ini merupakan jawaban strategis terhadap kebutuhan tenaga kerja di sektor manufaktur dan otomotif. Seiring dengan perkembangan zaman dan tuntutan industri yang semakin beragam, SMK YASMU terus berinovasi melalui penambahan program keahlian baru.`;

// Computed properties
const historyParagraphs = computed(() => {
  const history = profiles.value.history;
  if (history?.content) {
    return history.content.split('\n').filter(p => p.trim());
  }
  return fallbackHistory.split('\n').filter(p => p.trim());
});

const principalData = computed(() => {
  return profiles.value.principal_message || null;
});

const schoolImage = ref('/LOGO BULAT PNG.png');

const handleImageError = (e) => {
  e.target.src = '/LOGO BULAT PNG.png';
};

const getImageUrl = (path) => {
  if (!path) return '/LOGO BULAT PNG.png';
  if (path.startsWith('http') || path.startsWith('/')) return path;
  return `http://localhost:8000/storage/${path}`;
};

// Load data from API
const loadData = async () => {
  loading.value = true;
  try {
    const response = await publicApi.getSchoolProfiles();
    if (response.data) {
      profiles.value = response.data;
      
      // Set school image if available from school_info
      if (profiles.value.school_info?.image) {
        schoolImage.value = getImageUrl(profiles.value.school_info.image);
      }
    }
  } catch (error) {
    console.error('Error loading profiles:', error);
  } finally {
    loading.value = false;
  }
};

onMounted(loadData);
</script>

<style scoped>
.page-wrapper {
  min-height: 100vh;
  background: #fff;
}

.content-main {
  margin-top: 80px;
  padding: 3rem 2rem 6rem;
  max-width: 1400px;
  margin-left: auto;
  margin-right: auto;
}

/* Loading State */
.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem;
  color: #64748b;
}

.loader {
  width: 40px;
  height: 40px;
  border: 3px solid #e2e8f0;
  border-top-color: #155d27;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Page Header */
.page-header {
  display: flex;
  align-items: center;
  gap: 2rem;
  margin-bottom: 3rem;
}

.back-btn {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #155d27;
  border: none;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;
  flex-shrink: 0;
}

.back-btn:hover {
  background: #0d4f1f;
  transform: scale(1.05);
}

.page-title {
  font-size: 2.5rem;
  font-weight: 900;
  color: #155d27;
  text-align: center;
  flex: 1;
  margin: 0;
}

/* History Section */
.history-section {
  margin-bottom: 4rem;
}

.history-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  align-items: start;
}

.history-image {
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  height: 500px;
  background: #f8fafc;
  position: sticky;
  top: 100px;
}

.history-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s;
}

.history-image:hover img {
  transform: scale(1.05);
}

.history-text {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.history-content {
  color: #334155;
  font-size: 1.05rem;
  line-height: 1.9;
  text-align: justify;
  margin: 0;
  white-space: pre-line;
}

/* Principal Section */
.principal-section {
  padding: 4rem 0;
  border-top: 1px solid #e5e7eb;
}

.section-title {
  font-size: 2rem;
  font-weight: 800;
  color: #155d27;
  text-align: center;
  margin-bottom: 3rem;
}

.principal-card {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 3rem;
  background: linear-gradient(135deg, #f8fafc 0%, #f0fdf4 100%);
  border-radius: 24px;
  padding: 3rem;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
}

.principal-image {
  border-radius: 16px;
  overflow: hidden;
  height: 350px;
}

.principal-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.principal-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.principal-message {
  font-size: 1.1rem;
  line-height: 1.9;
  color: #334155;
  margin: 0 0 2rem 0;
  text-align: justify;
  white-space: pre-line;
}

.principal-name {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.principal-name strong {
  font-size: 1.25rem;
  color: #155d27;
}

.principal-name span {
  font-size: 0.9rem;
  color: #64748b;
}

/* Responsive */
@media (max-width: 1024px) {
  .principal-card {
    grid-template-columns: 1fr;
  }
  
  .principal-image {
    height: 300px;
  }
}

@media (max-width: 968px) {
  .page-header {
    gap: 1rem;
  }

  .page-title {
    font-size: 1.8rem;
  }

  .history-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
  }

  .history-image {
    position: static;
    height: 350px;
  }
}

@media (max-width: 640px) {
  .content-main {
    padding: 1.5rem 1rem 3rem;
  }

  .page-header {
    gap: 0.75rem;
    margin-bottom: 2rem;
  }

  .page-title {
    font-size: 1.3rem;
    text-align: left;
  }

  .back-btn {
    width: 40px;
    height: 40px;
  }

  .history-image {
    height: 250px;
    border-radius: 16px;
  }

  .history-content {
    font-size: 0.95rem;
    line-height: 1.8;
    text-align: left;
  }

  .principal-card {
    padding: 1.5rem;
  }

  .principal-image {
    height: 200px;
  }
}
</style>
