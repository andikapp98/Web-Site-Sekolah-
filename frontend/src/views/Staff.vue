<template>
  <div class="staff-page">
    <PublicNavbar />

    <!-- Principal Section (Sambutan) -->
    <section class="principal-section">
      <div class="container">
        <!-- Back Button -->
        <button @click="router.back()" class="back-circle-btn">
          <ChevronLeft :size="24" />
        </button>

        <div class="principal-grid">
          <div class="principal-info">
            <div class="label-group">
              <span class="section-label">KEPALA SEKOLAH</span>
              <h1 class="principal-name">{{ principal?.name || 'Dra. SRI ANGGRAHITANINGSIH, M.M.' }}</h1>
            </div>
            
            <div class="principal-message">
              <p v-for="(p, i) in principalMessageParagraphs" :key="i">
                {{ p }}
              </p>
              <div v-if="!principalMessageParagraphs.length" class="fallback-message">
                <p>Dengan memanjatkan puji syukur ke hadirat Allah SWT dan melantunkan salawat kepada teladan kita, Nabi Muhammad SAW, kami menegaskan keyakinan bahwa pendidikan adalah pilar utama peradaban sebuah bangsa. Kami memandang bahwa puncak tertinggi dari penguasaan ilmu pengetahuan dan keterampilan adalah terwujudnya akhlak mulia (al-akhlaq al-karimah) yang terpatri sebagai karakter utama. Inilah fondasi yang menuntun setiap langkah dan tujuan pendidikan di lingkungan SMK YASMU Gresik.</p>
                <p>Berlandaskan prinsip tersebut, SMK YASMU Gresik, yang telah mengabdi selama lebih dari dua dekade, mendedikasikan diri untuk membekali generasi muda dengan kompetensi kejuruan yang unggul sekaligus karakter yang tangguh dan berintegritas. Kami mempersiapkan para siswa tidak hanya agar siap menghadapi dunia kerja, tetapi juga untuk menjadi calon pemimpin di masa depan. Oleh karena itu, kami mengajak segenap orang tua/wali murid dan pemangku kepentingan untuk bersinergi bersama kami dalam menjalankan amanah mulia ini. Dengan bertawakal kepada Allah SWT, semoga ikhtiar kita bersama senantiasa diberkahi.</p>
              </div>
            </div>
          </div>
          <div class="principal-photo-wrapper">
            <div class="photo-frame">
              <img :src="principalPhoto" alt="Principal" @error="handleImageError" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Former Principals Section -->
    <section class="former-principals-section">
      <div class="container">
        <div class="former-grid-layout">
          <div class="former-title-side">
            <h2>KEPALA SEKOLAH<br>TERDAHULU</h2>
          </div>
          <div class="former-cards-side">
            <div v-for="item in formerPrincipals" :key="item.id" class="former-card">
              <div class="former-photo">
                <img :src="getImageUrl(item.photo)" :alt="item.name" @error="handleFormerImageError" />
                <div class="image-overlay"></div>
              </div>
              <div class="former-info">
                <h3 class="former-name">{{ item.name }}</h3>
                <p class="former-period-label">PERIODE {{ item.period }}</p>
                <p class="former-years">TH {{ item.years }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Scroll Top Button -->
      <button @click="scrollToTop" class="scroll-top-btn" :class="{ show: showScrollTop }">
        <ChevronUp :size="24" />
      </button>
    </section>

    <!-- Wakil Kepala Sekolah Section -->
    <section class="wakakepsek-section">
      <div class="container">
        <div class="section-title-left">
          <h2>WAKIL<br>KEPALA SEKOLAH</h2>
        </div>
        <div class="wakakepsek-grid">
          <div v-for="waka in wakilKepsek" :key="waka.id" class="waka-card fade-in-item">
            <div class="waka-photo">
              <img v-if="waka.photo" :src="getImageUrl(waka.photo)" :alt="waka.name" @error="waka.photo = null" />
              <div v-else class="waka-placeholder">
                <User :size="64" stroke-width="1" />
              </div>
            </div>
            <div class="waka-info">
              <h3 class="waka-name">{{ waka.name }}</h3>
              <p class="waka-position">{{ waka.subject || waka.position }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Teachers & Staff List Section -->
    <section id="teachers-list" class="staff-list-section">
      <div class="container">
        <div class="section-title-left">
          <h2>STAFF &<br>TENAGA PENDIDIK</h2>
        </div>

        <div class="staff-filters">
          <div class="search-box">
            <Search :size="20" />
            <input type="text" v-model="searchQuery" placeholder="Cari nama guru..." />
          </div>
        </div>

        <div class="staff-grid">
          <!-- Skeleton when loading and no data -->
          <template v-if="loading && filteredStaff.length === 0">
            <div v-for="i in 8" :key="'skeleton-'+i" class="staff-card skeleton">
              <div class="staff-photo-wrapper skeleton-img"></div>
              <div class="staff-info">
                <div class="skeleton-text name"></div>
                <div class="skeleton-text position"></div>
              </div>
            </div>
          </template>

          <template v-else>
            <div v-for="staff in filteredStaff" :key="staff.id" class="staff-card fade-in-item">
              <div class="staff-photo-wrapper">
                <img v-if="staff.photo" :src="getImageUrl(staff.photo)" :alt="staff.name" @error="staff.photo = null" />
                <div v-else class="staff-placeholder">
                  <User :size="64" stroke-width="1" />
                </div>
              </div>
              <div class="staff-info">
                <h3 class="staff-name">{{ staff.name }}</h3>
                <p class="staff-position">{{ staff.subject || staff.position }}</p>
              </div>
            </div>
          </template>
        </div>

        <div v-if="!loading && filteredStaff.length === 0" class="empty-state">
          <div class="empty-icon"><UserMinus :size="48" /></div>
          <p>Tidak ada data guru yang ditemukan.</p>
        </div>
      </div>
    </section>

    <PublicFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { ChevronLeft, ChevronUp, User, Search, UserMinus } from 'lucide-vue-next';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';
import { publicApi } from '../services/api';

const router = useRouter();

// Try to load initial data from localStorage for instant display
const cachedTeachers = JSON.parse(localStorage.getItem('cached_teachers') || '[]');
const cachedProfiles = JSON.parse(localStorage.getItem('cached_profiles') || '{}');
const cachedFormer = JSON.parse(localStorage.getItem('cached_former') || '[]');

const loading = ref(true);
const teachers = ref(cachedTeachers);
const profiles = ref(cachedProfiles);
const formerPrincipals = ref(cachedFormer);
const searchQuery = ref('');
const showScrollTop = ref(false);

const fallbackPrincipalMessage = `Dengan memanjatkan puji syukur ke hadirat Allah SWT dan melantunkan salawat kepada teladan kita, Nabi Muhammad SAW, kami menegaskan keyakinan bahwa pendidikan adalah pilar utama peradaban sebuah bangsa. Kami memandang bahwa puncak tertinggi dari penguasaan ilmu pengetahuan dan keterampilan adalah terwujudnya akhlak mulia (al-akhlaq al-karimah) yang terpatri sebagai karakter utama. Inilah fondasi yang menuntun setiap langkah dan tujuan pendidikan di lingkungan SMK YASMU Gresik.\n\nBerlandaskan prinsip tersebut, SMK YASMU Gresik, yang telah mengabdi selama lebih dari dua dekade, mendedikasikan diri untuk membekali generasi muda dengan kompetensi kejuruan yang unggul sekaligus karakter yang tangguh dan berintegritas. Kami mempersiapkan para siswa tidak hanya agar siap menghadapi dunia kerja, tetapi juga untuk menjadi calon pemimpin di masa depan. Oleh karena itu, kami mengajak segenap orang tua/wali murid dan pemangku kepentingan untuk bersinergi bersama kami dalam menjalankan amanah mulia ini. Dengan bertawakal kepada Allah SWT, semoga ikhtiar kita bersama senantiasa diberkahi.`;

const loadData = async () => {
  // If we have cached data, don't show blank/spinner, just update silently
  if (teachers.value.length === 0) {
    loading.value = true;
  }
  
  try {
    const [teachersRes, profilesRes, formerRes] = await Promise.all([
      publicApi.getTeachers({ per_page: 100 }),
      publicApi.getSchoolProfiles(),
      publicApi.getFormerPrincipals()
    ]);
    
    const newTeachers = teachersRes.data.data || teachersRes.data;
    teachers.value = newTeachers;
    localStorage.setItem('cached_teachers', JSON.stringify(newTeachers));
    
    // Former principals
    const newFormer = formerRes.data;
    formerPrincipals.value = newFormer;
    localStorage.setItem('cached_former', JSON.stringify(newFormer));
    
    // Process profiles to key-value object
    const pData = {};
    const pRaw = profilesRes.data.data || profilesRes.data;
    if (Array.isArray(pRaw)) {
      pRaw.forEach(p => { pData[p.key] = p; });
    }
    profiles.value = pData;
    localStorage.setItem('cached_profiles', JSON.stringify(pData));
  } catch (error) {
    console.error('Error loading data:', error);
  } finally {
    loading.value = false;
  }
};

const principal = computed(() => {
  const p = teachers.value.find(t => t.position === 'Kepala Sekolah');
  if (p) return p;
  // Fallback initial state matching screenshot if database empty
  return {
    name: 'Dra. SRI ANGGRAHITANINGSIH, M.M.',
    position: 'Kepala Sekolah',
    photo: '/images/principal.png'
  };
});

const principalMessageParagraphs = computed(() => {
  const message = profiles.value.principal_message?.content || fallbackPrincipalMessage;
  return message.split(/[\n\r]+/).filter(p => p.trim().length > 0);
});

const principalPhoto = computed(() => {
  if (principal.value?.photo) return getImageUrl(principal.value.photo);
  return '/images/principal.png'; // Should be uploaded or provided
});

// Wakil Kepala Sekolah - filter by position containing 'Waka' or 'Wakil'
const wakilKepsek = computed(() => {
  return teachers.value.filter(t => 
    t.position && (
      t.position.toLowerCase().includes('waka') || 
      t.position.toLowerCase().includes('wakil')
    )
  );
});

const filteredStaff = computed(() => {
  // Exclude Kepala Sekolah and Wakil Kepala Sekolah (they have their own sections)
  let result = teachers.value.filter(t => {
    const pos = (t.position || '').toLowerCase();
    return pos !== 'kepala sekolah' && 
           !pos.includes('waka') && 
           !pos.includes('wakil');
  });
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(t => 
      t.name.toLowerCase().includes(query) || 
      (t.subject && t.subject.toLowerCase().includes(query)) ||
      (t.position && t.position.toLowerCase().includes(query))
    );
  }
  
  return result;
});

const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http') || path.startsWith('/')) return path;
  return `http://localhost:8000/storage/${path}`;
};

const handleImageError = (e) => {
  e.target.src = '/LOGO BULAT PNG.png';
};

const handleFormerImageError = (e) => {
  e.target.src = '/LOGO BULAT PNG.png';
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const handleScroll = () => {
  showScrollTop.value = window.scrollY > 400;
};

onMounted(() => {
  loadData();
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
.staff-page {
  min-height: 100vh;
  background: #fff;
  color: #1e293b;
}

.container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 2rem;
}

/* Principal Section */
.principal-section {
  padding: 100px 0 60px;
  background: #f8fafc;
}

.back-circle-btn {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: #155d27;
  color: #fff;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(21, 93, 39, 0.2);
}

.back-circle-btn:hover {
  background: #1e7e34;
  transform: translateX(-5px);
}

.principal-grid {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: 4rem;
  align-items: center;
}

.section-label {
  display: block;
  font-weight: 700;
  color: #155d27;
  letter-spacing: 2px;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}

.principal-name {
  font-size: 2.5rem;
  font-weight: 800;
  color: #064e3b;
  line-height: 1.2;
  margin-bottom: 2rem;
}

.principal-message {
  font-size: 1.05rem;
  line-height: 1.8;
  color: #475569;
}

.principal-message p {
  margin-bottom: 1.5rem;
}

.principal-photo-wrapper {
  display: flex;
  justify-content: center;
}

.photo-frame {
  width: 100%;
  max-width: 450px;
  aspect-ratio: 4/5;
  border-radius: 30px;
  overflow: hidden;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
  border: 4px solid #fff;
  background: #e2e8f0;
}

.photo-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* Former Principals Section */
.former-principals-section {
  padding: 80px 0;
  background: #fff;
  position: relative;
}

.former-grid-layout {
  display: grid;
  grid-template-columns: 350px 1fr;
  gap: 4rem;
  align-items: center;
}

.former-title-side h2 {
  font-size: 2.2rem;
  font-weight: 800;
  color: #155d27;
  line-height: 1.3;
}

.former-cards-side {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

.former-card {
  position: relative;
  background: #1e293b;
  border-radius: 40px 10px 40px 10px;
  overflow: hidden;
  height: 420px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  border: 4px solid #fff;
}

.former-card:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
}

.former-photo {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
}

.former-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to top, rgba(15, 23, 42, 1) 0%, rgba(15, 23, 42, 0.85) 25%, rgba(15, 23, 42, 0.4) 50%, transparent 100%);
  z-index: 2;
}

.former-info {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 2rem 1.5rem;
  color: #fff;
  text-align: center;
  z-index: 3;
  background: linear-gradient(to top, rgba(21, 93, 39, 0.9) 0%, rgba(21, 93, 39, 0.7) 50%, transparent 100%);
}

.former-name {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: #ffffff;
  text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8), 0 1px 3px rgba(0, 0, 0, 0.6);
  line-height: 1.3;
}

.former-period-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #fef08a;
  letter-spacing: 1px;
  text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
}

.former-years {
  font-size: 1rem;
  font-weight: 600;
  margin-top: 0.25rem;
  color: #ffffff;
  text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
}

/* Wakil Kepala Sekolah Section */
.wakakepsek-section {
  padding: 80px 0;
  background: #fff;
}

.section-title-left {
  margin-bottom: 3rem;
}

.section-title-left h2 {
  font-size: 2rem;
  font-weight: 800;
  color: #155d27;
  line-height: 1.3;
}

.wakakepsek-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.waka-card {
  background: #fff;
  border-radius: 30px 10px 30px 10px;
  overflow: hidden;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  border: 3px solid #155d27;
}

.waka-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
}

.waka-photo {
  width: 100%;
  aspect-ratio: 3/4;
  overflow: hidden;
  background: #f1f5f9;
}

.waka-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.waka-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  background: #f8fafc;
}

.waka-info {
  padding: 1.25rem;
  text-align: center;
  background: #fff;
}

.waka-name {
  font-size: 1rem;
  font-weight: 700;
  color: #155d27;
  margin-bottom: 0.4rem;
  line-height: 1.3;
}

.waka-position {
  font-size: 0.85rem;
  color: #64748b;
  font-style: italic;
}

/* Staff List Section */
.staff-list-section {
  padding: 80px 0 120px;
  background: #fdfdfd;
}

.staff-filters {
  margin-bottom: 3rem;
}

.search-box {
  width: 100%;
  max-width: 500px;
  position: relative;
  display: flex;
  align-items: center;
}

.search-box svg {
  position: absolute;
  left: 1.2rem;
  color: #94a3b8;
}

.search-box input {
  width: 100%;
  padding: 0.9rem 1rem 0.9rem 3.5rem;
  border-radius: 50px;
  border: 2px solid #e2e8f0;
  background: #fff;
  font-size: 0.95rem;
  outline: none;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
}

.search-box input:focus {
  border-color: #155d27;
  box-shadow: 0 10px 25px rgba(21, 93, 39, 0.1);
}

.staff-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.staff-card {
  background: #fff;
  border-radius: 30px 10px 30px 10px;
  overflow: hidden;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  border: 3px solid #155d27;
}

.staff-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
}

/* Skeleton & Fade Animation */
.skeleton {
  cursor: default;
  pointer-events: none;
}

.skeleton-img {
  background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
}

.skeleton-text {
  height: 1rem;
  margin-bottom: 0.5rem;
  border-radius: 4px;
  background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
}

.skeleton-text.name { width: 80%; margin: 0 auto 0.5rem; }
.skeleton-text.position { width: 50%; margin: 0 auto; }

@keyframes skeleton-loading {
  0% { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

.fade-in-item {
  animation: fadeInItem 0.5s ease forwards;
}

@keyframes fadeInItem {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.staff-photo-wrapper {
  width: 100%;
  aspect-ratio: 3/4;
  overflow: hidden;
  background: #f1f5f9;
}

.staff-photo-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.staff-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  background: #f8fafc;
}

.staff-info {
  padding: 1.25rem;
  text-align: center;
  background: #fff;
}

.staff-name {
  font-size: 1rem;
  font-weight: 700;
  color: #155d27;
  margin-bottom: 0.4rem;
  line-height: 1.3;
}

.staff-position {
  font-size: 0.85rem;
  color: #64748b;
  font-style: italic;
}

/* Scroll Top Button */
.scroll-top-btn {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #155d27;
  color: #fff;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  z-index: 100;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
}

.scroll-top-btn.show {
  opacity: 1;
  visibility: visible;
}

.scroll-top-btn:hover {
  background: #064e3b;
  transform: translateY(-5px);
}

/* Loading & Empty states */
.loading-state, .empty-state {
  padding: 4rem 0;
  text-align: center;
  color: #64748b;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #155d27;
  border-radius: 50%;
  margin: 0 auto 1.5rem;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.empty-icon {
  margin-bottom: 1.5rem;
  color: #cbd5e1;
}

@media (max-width: 1024px) {
  .principal-grid {
    grid-template-columns: 1fr;
    gap: 3rem;
  }
  
  .principal-info {
    order: 1;
    text-align: center;
  }
  
  .principal-photo-wrapper {
    order: 0;
  }
  
  .former-grid-layout {
    grid-template-columns: 1fr;
    text-align: center;
  }
  
  .former-cards-side {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .wakakepsek-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .staff-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .container { padding: 0 1.5rem; }
  .principal-name { font-size: 1.8rem; }
  .former-title-side h2 { font-size: 1.8rem; }
  .section-title-left h2 { font-size: 1.6rem; }
  
  .former-cards-side {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .former-card {
    height: 360px;
  }
  
  .former-name {
    font-size: 1.1rem;
  }
  
  .wakakepsek-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }
  
  .waka-name {
    font-size: 0.9rem;
  }
  
  .waka-position {
    font-size: 0.75rem;
  }
  
  .staff-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }
  
  .staff-name {
    font-size: 0.9rem;
  }
  
  .staff-position {
    font-size: 0.75rem;
  }
}
</style>
