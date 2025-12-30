<template>
  <div class="page-wrapper">
    <PublicNavbar />
    
    <main class="content-main">
      <!-- Back Button & Title -->
      <div class="profile-header">
        <button class="back-btn" @click="$router.push('/')">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
          </svg>
        </button>
        <h1 class="profile-title">PROFIL SMK YASMU</h1>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="loading-state">
        <span class="loader"></span>
        <p>Memuat data...</p>
      </div>

      <template v-else>
        <!-- Profil Section - Text Left, Image Right -->
        <section class="profil-section">
          <div class="profil-grid">
            <div class="profil-text">
              <p class="profil-intro" :class="{ expanded: showMoreProfil }">
                {{ profilContent }}
              </p>
              <button v-if="profilContent && profilContent.length > 500" class="toggle-btn" @click="showMoreProfil = !showMoreProfil">
                {{ showMoreProfil ? 'Sembunyikan' : 'Selengkapnya' }}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="{ rotated: showMoreProfil }">
                  <path d="M6 9l6 6 6-6"/>
                </svg>
              </button>
            </div>
            <div class="profil-image">
              <img :src="profileImage" alt="SMK YASMU Building" @error="handleImageError" />
            </div>
          </div>
        </section>

        <!-- Vision & Mission Section -->
        <section class="vision-mission-section">
          <div class="vm-wrapper">
            <h2 class="section-title">VISI DAN MISI</h2>
            
            <div class="vm-layout">
              <!-- Left: Cards -->
              <div class="vm-cards-container">
                <!-- Visi Card -->
                <div class="vm-card">
                  <div class="vm-card-header">
                    <Eye :size="20" stroke-width="2" />
                    VISI KAMI
                  </div>
                  <div class="vm-card-body">
                    <p>{{ visionContent }}</p>
                  </div>
                </div>
                
                <!-- Misi Card -->
                <div class="vm-card">
                  <div class="vm-card-header">
                    <Target :size="20" stroke-width="2" />
                    MISI KAMI
                  </div>
                  <div class="vm-card-body" :class="{ expanded: showMoreMisi }">
                    <div class="mission-list">
                      <div v-for="(item, index) in missionItems" :key="index" class="mission-item">
                        <span class="mission-number">{{ index + 1 }}</span>
                        <span class="mission-text">{{ item }}</span>
                      </div>
                    </div>
                  </div>
                  <button v-if="missionItems.length > 3" class="vm-toggle-btn" @click="showMoreMisi = !showMoreMisi">
                    {{ showMoreMisi ? 'Sembunyikan' : 'Selengkapnya' }}
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="{ rotated: showMoreMisi }">
                      <path d="M6 9l6 6 6-6"/>
                    </svg>
                  </button>
                </div>
              </div>
              
              <!-- Right: Image Illustration -->
              <div class="vm-illustration">
                <img src="/images/visi-misi-illustration.png" alt="Visi Misi Illustration" class="vm-img" />
              </div>
            </div>
          </div>
        </section>

        <!-- History Section - Text Left, Image Right -->
        <section v-if="historyData" class="history-section">
          <h2 class="section-title">{{ historyData.title || 'SEJARAH' }}</h2>
          
          <div class="history-grid">
            <div class="history-text">
              <p class="history-content" :class="{ expanded: showMoreHistory }">
                {{ historyData.content }}
              </p>
              <button v-if="historyData.content && historyData.content.length > 600" class="toggle-btn" @click="showMoreHistory = !showMoreHistory">
                {{ showMoreHistory ? 'Sembunyikan' : 'Selengkapnya' }}
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="{ rotated: showMoreHistory }">
                  <path d="M6 9l6 6 6-6"/>
                </svg>
              </button>
            </div>
            <div class="history-image">
              <img :src="historyImage" alt="Gedung YASMU" @error="handleHistoryImageError" />
            </div>
          </div>
        </section>

        <!-- Former Principals Section -->
        <section class="former-principals-section" v-if="formerPrincipals.length > 0">
          <div class="fp-header">
            <h2 class="fp-title">KEPALA SEKOLAH</h2>
            <h3 class="fp-subtitle">TERDAHULU</h3>
          </div>
          
          <div class="fp-grid">
            <div 
              v-for="principal in formerPrincipals" 
              :key="principal.id" 
              class="fp-card"
            >
              <div class="fp-photo-frame">
                <img 
                  :src="getImageUrl(principal.photo)" 
                  :alt="principal.name"
                  @error="handlePrincipalImageError"
                />
              </div>
              <div class="fp-info">
                <h4 class="fp-name">{{ principal.name }}</h4>
                <p class="fp-period">PERIODE {{ principal.period || '-' }}</p>
                <p class="fp-year">TH {{ principal.years || '-' }}</p>
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
import { Eye, Target } from 'lucide-vue-next';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';
import { publicApi } from '../services/api';

const router = useRouter();
const loading = ref(true);
const profiles = ref({});
const formerPrincipals = ref([]);
const showMoreProfil = ref(false);
const showMoreHistory = ref(false);
const showMoreMisi = ref(false);

// Default fallback data
const fallbackProfil = `Sebagai institusi pendidikan vokasi yang terkemuka di bawah naungan Yayasan Mu'allimin Mu'allimat (YASMU) Manyar Gresik, Sekolah Menengah Kejuruan Yasmu (SMK YASMU) telah memantapkan posisinya sebagai pilar utama dalam penyiapan sumber daya manusia yang andal.`;

const fallbackVision = `Melalui proses pendidikan dan pelatihan yang profesional, kita jadikan SMK YASMU Manyar Gresik sebagai lembaga pendidikan yang unggul.`;

const fallbackMission = [
  'Mengintegrasikan nilai-nilai delapan dimensi profil lulusan dalam kegiatan belajar',
  'Membangun kerja sama dengan dunia usaha dan industri (DUDIKA)',
  'Menjalankan kurikulum sesuai kebutuhan industri dan dunia kerja',
  'Menyelenggarakan pelatihan dan sertifikasi kompetensi bagi guru dan siswa',
  'Mencetak lulusan yang produktif dan siap kerja',
  'Mewujudkan lingkungan sekolah yang bersih dan hijau',
  'Membentuk karakter warga sekolah yang beriman dan berakhlak mulia',
  'Pembekalan Bahasa Asing (Inggris, Jepang, dan Cina)',
  'Program magang atau kerja luar negeri'
];

// Computed properties
const profilContent = computed(() => {
  return profiles.value.school_info?.content || fallbackProfil;
});

const historyData = computed(() => {
  return profiles.value.history || null;
});

const visionContent = computed(() => {
  return profiles.value.vision?.content || fallbackVision;
});

const missionItems = computed(() => {
  const mission = profiles.value.mission;
  if (mission?.content) {
    const items = mission.content
      .split(/[\n\r]+/)
      .map(item => item.replace(/^\d+\.\s*/, '').trim())
      .filter(item => item.length > 0);
    return items.length > 0 ? items : fallbackMission;
  }
  return fallbackMission;
});

const profileImage = ref('/hero/Frame 313.png');
const historyImage = ref('/hero/Frame 313.png');

const handleImageError = (e) => {
  e.target.src = '/LOGO BULAT PNG.png';
};

const handleHistoryImageError = (e) => {
  e.target.src = '/LOGO BULAT PNG.png';
};

const handlePrincipalImageError = (e) => {
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
    // Load profiles and former principals in parallel
    const [profilesResponse, principalsResponse] = await Promise.all([
      publicApi.getSchoolProfiles(),
      publicApi.getFormerPrincipals()
    ]);
    
    if (profilesResponse.data) {
      // API returns keyed object
      profiles.value = profilesResponse.data;
      
      // Set profile image if available from school_info
      if (profiles.value.school_info?.image) {
        profileImage.value = getImageUrl(profiles.value.school_info.image);
      }
      
      // Set history image if available
      if (profiles.value.history?.image) {
        historyImage.value = getImageUrl(profiles.value.history.image);
      }
    }
    
    if (principalsResponse.data) {
      formerPrincipals.value = principalsResponse.data;
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

/* Profile Header */
.profile-header {
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

.profile-title {
  font-size: 2.5rem;
  font-weight: 900;
  color: #155d27;
  text-align: center;
  flex: 1;
  margin: 0;
}

/* Profil Section */
.profil-section {
  margin-bottom: 4rem;
}

.profil-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: start;
}

.profil-text {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.profil-intro {
  color: #334155;
  font-size: 1rem;
  line-height: 1.9;
  text-align: justify;
  margin: 0;
  white-space: pre-line;
  max-height: 400px;
  overflow: hidden;
  transition: max-height 0.5s ease;
}

.profil-intro.expanded {
  max-height: 3000px;
}

.toggle-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: transparent;
  border: none;
  color: #155d27;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s;
  padding: 0.5rem 0;
}

.toggle-btn:hover {
  color: #0d4f1f;
}

.toggle-btn svg {
  transition: transform 0.3s;
}

.toggle-btn svg.rotated {
  transform: rotate(180deg);
}

.profil-image {
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  height: 450px;
  position: sticky;
  top: 100px;
}

.profil-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* History Section */
.history-section {
  padding: 0;
  margin-bottom: 4rem;
}

.history-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: start;
}

.history-text {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.history-content {
  color: #334155;
  font-size: 1rem;
  line-height: 1.9;
  text-align: justify;
  margin: 0;
  white-space: pre-line;
  max-height: 350px;
  overflow: hidden;
  transition: max-height 0.5s ease;
}

.history-content.expanded {
  max-height: 3000px;
}

.history-image {
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  height: 400px;
}

.history-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Vision & Mission Section */
.vision-mission-section {
  background: #f1f5f9;
  margin: 0 -2rem;
  padding: 4rem 2rem;
  margin-bottom: 4rem;
}

.vm-wrapper {
  max-width: 1400px;
  margin: 0 auto;
}

.section-title {
  font-size: 2rem;
  font-weight: 800;
  color: #155d27;
  margin-bottom: 2rem;
}

.vm-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: center;
}

.vm-cards-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.vm-card {
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.vm-card-header {
  background: linear-gradient(135deg, #155d27 0%, #1e7e34 100%);
  color: #fff;
  font-size: 1.1rem;
  font-weight: 700;
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.vm-icon {
  font-size: 1.25rem;
}

.vm-card-body {
  padding: 1.5rem;
  max-height: 200px;
  overflow: hidden;
  transition: max-height 0.5s ease;
}

.vm-card-body.expanded {
  max-height: 1000px;
}

.vm-card-body p {
  color: #334155;
  font-size: 0.95rem;
  line-height: 1.8;
  margin: 0;
}

.mission-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.mission-item {
  display: flex;
  gap: 0.75rem;
  align-items: flex-start;
}

.mission-number {
  background: #155d27;
  color: #fff;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
  flex-shrink: 0;
}

.mission-text {
  color: #334155;
  font-size: 0.9rem;
  line-height: 1.6;
}

.vm-toggle-btn {
  width: 100%;
  padding: 0.75rem;
  background: #f8fafc;
  border: none;
  border-top: 1px solid #e2e8f0;
  color: #155d27;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: all 0.3s;
}

.vm-toggle-btn:hover {
  background: #f0fdf4;
}

.vm-toggle-btn svg {
  transition: transform 0.3s;
}

.vm-toggle-btn svg.rotated {
  transform: rotate(180deg);
}

.vm-illustration {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.vm-img {
  width: 100%;
  max-width: 550px;
  height: auto;
  object-fit: contain;
  mix-blend-mode: multiply;
  filter: contrast(1.05) brightness(1.05);
}

.vm-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.vm-icon {
  font-size: 2.5rem;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
}

.mission-card .vm-icon {
  background: #f0fdf4;
}

.vm-header h3 {
  font-size: 1.5rem;
  font-weight: 800;
  margin: 0;
}

.vision-card .vm-header h3 {
  color: #fff;
}

.mission-card .vm-header h3 {
  color: #155d27;
}

.vm-content {
  font-size: 1.2rem;
  line-height: 1.8;
  font-style: italic;
  margin: 0;
  color: rgba(255, 255, 255, 0.95);
}

.vm-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.vm-list li {
  position: relative;
  padding-left: 2rem;
  color: #475569;
  line-height: 1.6;
  font-size: 1rem;
}

.vm-list li::before {
  content: '✓';
  position: absolute;
  left: 0;
  color: #155d27;
  font-weight: 800;
  font-size: 1.2rem;
}

/* Principal Section */
.principal-section {
  padding: 4rem 0;
  border-top: 1px solid #e5e7eb;
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
@media (max-width: 968px) {
  .profile-header {
    gap: 1rem;
  }

  .profile-title {
    font-size: 1.8rem;
  }

  .profil-grid,
  .vm-layout,
  .history-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
  }

  .profil-image {
    order: -1;
    height: 300px;
    position: relative;
    top: 0;
  }

  .vm-illustration {
    order: -1;
    padding: 1rem;
  }

  .vm-img {
    max-width: 400px;
  }

  .history-image {
    order: -1;
    height: 280px;
  }

  .vision-mission-section {
    margin: 0 -1rem;
    padding: 3rem 1rem;
  }
}

@media (max-width: 640px) {
  .content-main {
    padding: 1.5rem 1rem 3rem;
  }

  .profile-header {
    gap: 0.75rem;
    margin-bottom: 2rem;
  }

  .profile-title {
    font-size: 1.3rem;
    text-align: left;
  }

  .back-btn {
    width: 40px;
    height: 40px;
  }

  .profil-image {
    height: 220px;
    border-radius: 16px;
  }

  .profil-intro,
  .history-content {
    font-size: 0.9rem;
    line-height: 1.7;
    text-align: left;
  }

  .history-image {
    height: 200px;
    border-radius: 16px;
  }

  .vm-card-header {
    font-size: 1rem;
    padding: 0.75rem 1rem;
  }

  .vm-card-content {
    padding: 1rem;
    font-size: 0.85rem;
  }

  .mission-item {
    font-size: 0.8rem;
  }

  .section-title {
    font-size: 1.4rem;
    margin-bottom: 1.5rem;
  }
}

/* Former Principals Section */
.former-principals-section {
  padding: 4rem 0;
  border-top: 1px solid #e5e7eb;
}

.fp-header {
  margin-bottom: 3rem;
}

.fp-title {
  font-size: 2rem;
  font-weight: 800;
  color: #155d27;
  margin: 0;
  line-height: 1.2;
}

.fp-subtitle {
  font-size: 2rem;
  font-weight: 800;
  color: #155d27;
  margin: 0;
  line-height: 1.2;
}

.fp-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 2rem;
  max-width: 1000px;
}

.fp-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.fp-photo-frame {
  width: 160px;
  height: 200px;
  border: 6px solid #d4a843;
  border-radius: 8px;
  overflow: hidden;
  background: #f5f5f5;
  margin-bottom: 1rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.fp-photo-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.fp-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.fp-name {
  font-size: 1rem;
  font-weight: 700;
  color: #155d27;
  margin: 0;
  line-height: 1.3;
}

.fp-period {
  font-size: 0.85rem;
  font-weight: 600;
  color: #333;
  margin: 0;
}

.fp-year {
  font-size: 0.85rem;
  color: #666;
  margin: 0;
}

@media (max-width: 768px) {
  .fp-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  
  .fp-photo-frame {
    width: 130px;
    height: 165px;
    border-width: 4px;
  }
  
  .fp-title,
  .fp-subtitle {
    font-size: 1.5rem;
  }
  
  .fp-name {
    font-size: 0.9rem;
  }
  
  .fp-period,
  .fp-year {
    font-size: 0.8rem;
  }
}

@media (max-width: 480px) {
  .fp-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }
  
  .fp-photo-frame {
    width: 110px;
    height: 140px;
  }
  
  .fp-header {
    margin-bottom: 2rem;
  }
}
</style>
