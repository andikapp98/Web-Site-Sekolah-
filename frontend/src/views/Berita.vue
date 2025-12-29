<template>
  <div class="berita-wrapper">
    <PublicNavbar />

    <main class="berita-main">
      <!-- Back Button & Title -->
      <div class="berita-header">
        <button class="back-btn" @click="goBack">
          <ArrowLeft :size="24" />
        </button>
        <h1 class="berita-title">BERITA TERKINI</h1>
      </div>

      <!-- News Carousel -->
      <section class="news-carousel">
        <Swiper
          :modules="[Pagination, Autoplay]"
          :pagination="{ clickable: true }"
          :autoplay="{ delay: 5000 }"
          :loop="true"
          class="news-swiper"
        >
          <SwiperSlide v-for="(news, index) in filteredNews" :key="index">
            <div class="news-card">
              <div class="news-content">
                <h2>{{ news.title }}</h2>
                <p>{{ news.content }}</p>
                <button class="news-btn">Selengkapnya</button>
              </div>
              <div class="news-image">
                <img :src="news.image || '/slide-1.jpg'" :alt="news.title" />
              </div>
            </div>
          </SwiperSlide>
        </Swiper>
      </section>

      <!-- Filter Tabs -->
      <div class="filter-tabs">
        <button 
          :class="['filter-tab', { active: activeFilter === 'all' }]"
          @click="activeFilter = 'all'"
        >
          Semua Artikel
        </button>
        <button 
          :class="['filter-tab', { active: activeFilter === 'prestasi' }]"
          @click="activeFilter = 'prestasi'"
        >
          Prestasi
        </button>
        <button 
          :class="['filter-tab', { active: activeFilter === 'kegiatan' }]"
          @click="activeFilter = 'kegiatan'"
        >
          Kegiatan
        </button>
      </div>

      <!-- Scroll to Top Button -->
      <button class="scroll-top" @click="scrollToTop" v-if="showScrollTop">
        <ArrowUp :size="24" />
      </button>
    </main>

    <PublicFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Autoplay } from 'swiper/modules';
import { ArrowLeft, ArrowUp } from 'lucide-vue-next';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';
import 'swiper/css';
import 'swiper/css/pagination';

const router = useRouter();
const searchOpen = ref(false);
const searchQuery = ref('');
const activeFilter = ref('all');
const showScrollTop = ref(false);

const toggleSearch = () => { searchOpen.value = !searchOpen.value; };
const doSearch = () => { 
  if (searchQuery.value.trim()) {
    alert('Mencari: ' + searchQuery.value);
  }
};

const goBack = () => {
  router.push('/');
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Handle scroll for scroll-to-top button
const handleScroll = () => {
  showScrollTop.value = window.scrollY > 300;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const newsData = ref([
  {
    category: 'kegiatan',
    title: 'Menjelajahi Industri Gula: Kunjungan SMK Yasmu Gresik ke PT Madukismo',
    content: 'Dalam upaya berkelanjutan untuk memperkaya pengalaman belajar dan mendekatkan siswa dengan dunia industri secara nyata, SMK Yasmu Gresik kembali menghelat program kunjungan industri berskala besar. Pada tanggal 28 Januari 2025, sebanyak 287 siswa dari berbagai kompetensi keahlian, didampingi oleh 50 guru pembimbing, bertolak menuju Daerah Istimewa Yogyakarta',
    image: '/slide-1.jpg'
  },
  {
    category: 'prestasi',
    title: 'BRIGPASMU SMK YASMU Gresik Torehkan Prestasi Gemilang, Juarai LKBB GAPURA 4.0',
    content: 'GRESIK – Prestasi membanggakan kembali diukir oleh siswa-siswi SMK YASMU Manyar, Kabupaten Gresik. Kali ini, tim Paskibra sekolah yang dikenal dengan nama BRIGPASMU (Brigadir Pasukan SMK YASMU) berhasil meraih gelar juara dalam kompetisi Lomba Keterampilan Baris-Berbaris (LKBB) GAPURA 4.0.',
    image: '/slide-2.jpg'
  },
  {
    category: 'prestasi',
    title: 'Lanjutkan Tren Juara, BRIGPASMU SMK YASMU Sabet Juara Caraka 2 di LKBB Tingkat Jawa Timur',
    content: 'GRESIK – Konsistensi dalam berprestasi kembali dibuktikan oleh tim Paskibra SMK YASMU Manyar. Usai meraih sukses di tingkat kabupaten, tim BRIGPASMU (Brigadir Pasukan SMK YASMU) kembali mengharumkan nama sekolah dengan meraih juara Caraka 2 pada kompetisi LKBB tingkat Provinsi Jawa Timur.',
    image: '/slide-3.jpg'
  },
  {
    category: 'kegiatan',
    title: 'SMK Yasmu Gelar Workshop Teknologi Industry 4.0',
    content: 'SMK Yasmu mengadakan workshop bertema "Persiapan Menghadapi Era Industry 4.0" yang diikuti oleh seluruh siswa kelas XI dan XII. Workshop ini menghadirkan narasumber dari berbagai perusahaan teknologi terkemuka untuk memberikan wawasan tentang perkembangan teknologi terkini.',
    image: '/slide-1.jpg'
  }
]);

const filteredNews = computed(() => {
  if (activeFilter.value === 'all') {
    return newsData.value;
  }
  return newsData.value.filter(news => news.category === activeFilter.value);
});
</script>

<style scoped>
* { margin: 0; padding: 0; box-sizing: border-box; }
.berita-wrapper { width: 100%; background: #fff; min-height: 100vh; }

/* Main Content */
.berita-main { 
  margin-top: 80px; 
  padding: 2rem 3rem 4rem; 
  max-width: 1400px; 
  margin-left: auto; 
  margin-right: auto;
}

/* Header Section */
.berita-header {
  display: flex;
  align-items: center;
  margin-bottom: 3rem;
  gap: 2rem;
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

.berita-title {
  font-size: 2.5rem;
  font-weight: 900;
  color: #155d27;
  text-align: center;
  flex: 1;
}

/* News Carousel */
.news-carousel {
  margin-bottom: 3rem;
}

.news-swiper {
  width: 100%;
  border-radius: 20px;
  background: #f8faf9;
  padding: 2rem;
}

.news-card {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  align-items: center;
  min-height: 400px;
}

.news-content {
  padding: 2rem;
}

.news-content h2 {
  font-size: 1.8rem;
  font-weight: 800;
  color: #155d27;
  margin-bottom: 1.5rem;
  line-height: 1.3;
}

.news-content p {
  color: #374151;
  line-height: 1.7;
  font-size: 1rem;
  margin-bottom: 2rem;
}

.news-btn {
  background: #155d27;
  color: #fff;
  border: none;
  padding: 0.9rem 2.5rem;
  border-radius: 50px;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(21, 93, 39, 0.3);
}

.news-btn:hover {
  background: #0d4f1f;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(21, 93, 39, 0.4);
}

.news-image {
  border-radius: 16px;
  overflow: hidden;
  height: 400px;
}

.news-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

:deep(.swiper-pagination) {
  bottom: -2rem !important;
}

:deep(.swiper-pagination-bullet) {
  width: 12px;
  height: 12px;
  background: #cbd5e0;
  opacity: 1;
}

:deep(.swiper-pagination-bullet-active) {
  background: #155d27;
  width: 32px;
  border-radius: 6px;
}

/* Filter Tabs */
.filter-tabs {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-top: 3rem;
}

.filter-tab {
  background: transparent;
  color: #64748b;
  border: 2px solid #e5e7eb;
  padding: 0.75rem 2rem;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s;
}

.filter-tab.active {
  background: #155d27;
  color: #fff;
  border-color: #155d27;
}

.filter-tab:hover:not(.active) {
  border-color: #155d27;
  color: #155d27;
  background: #f0f9f4;
}

/* Scroll to Top Button */
.scroll-top {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
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
  box-shadow: 0 4px 15px rgba(21, 93, 39, 0.3);
  z-index: 999;
}

.scroll-top:hover {
  background: #0d4f1f;
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(21, 93, 39, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
  .berita-main {
    padding: 1.5rem;
  }
  
  .berita-title {
    font-size: 1.8rem;
  }
  
  .news-card {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .news-image {
    height: 250px;
  }
  
  .filter-tabs {
    flex-wrap: wrap;
  }
}
</style>
