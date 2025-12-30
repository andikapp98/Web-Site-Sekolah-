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
          :autoplay="{ delay: 5000, disableOnInteraction: false }"
          :loop="filteredNews.length >= 3"
          class="news-swiper"
        >
          <SwiperSlide v-for="(news, index) in filteredNews" :key="index">
            <div class="news-card">
              <div class="news-content">
                <span class="news-category-badge" :class="news.category?.toLowerCase()">
                  {{ news.category || 'Berita' }}
                </span>
                <h2>{{ news.title }}</h2>
                <p>{{ news.excerpt || news.content }}</p>
                <button class="news-btn">Selengkapnya</button>
              </div>
              <div class="news-image">
                <img :src="getImageUrl(news.image)" :alt="news.title" />
              </div>
            </div>
          </SwiperSlide>
          <div v-if="loading" class="news-loading">
            <span class="loader"></span>
            <p>Memuat berita...</p>
          </div>
          <div v-else-if="filteredNews.length === 0" class="news-empty">
            <p>Tidak ada berita ditemukan untuk kategori ini.</p>
          </div>
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

      <!-- Article Cards Grid -->
      <section class="articles-grid">
        <div 
          v-for="(article, i) in filteredNews" 
          :key="i" 
          class="article-card"
        >
          <div class="article-image">
            <img :src="getImageUrl(article.image)" :alt="article.title" />
          </div>
          <div class="article-body">
            <span class="article-badge" :class="article.category?.toLowerCase()">
              {{ article.category || 'Berita' }}
            </span>
            <h3>{{ article.title }}</h3>
            <p>{{ article.excerpt || (article.content?.substring(0, 100) + '...') }}</p>
            <button class="article-btn">Selengkapnya</button>
          </div>
        </div>
        <div v-if="filteredNews.length === 0 && !loading" class="no-articles">
          <p>Tidak ada artikel ditemukan.</p>
        </div>
      </section>

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
import { publicApi } from '../services/api';
import 'swiper/css';
import 'swiper/css/pagination';

const router = useRouter();
const searchOpen = ref(false);
const searchQuery = ref('');
const activeFilter = ref('all');
const showScrollTop = ref(false);
const newsData = ref([]);
const loading = ref(true);

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

const getImageUrl = (path) => {
  if (!path) return '/slide-1.jpg';
  if (path.startsWith('http')) return path;
  return `http://localhost:8000/storage/${path}`;
};

const loadData = async () => {
  loading.value = true;
  try {
    const res = await publicApi.getArticles(50);
    newsData.value = res.data?.data || res.data || [];
  } catch (e) {
    console.error('Error loading articles:', e);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
  loadData();
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const filteredNews = computed(() => {
  if (activeFilter.value === 'all') {
    return newsData.value;
  }
  return newsData.value.filter(news => news.category?.toLowerCase() === activeFilter.value.toLowerCase());
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

.news-category-badge {
  display: inline-block;
  padding: 0.5rem 1.25rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 1.5rem;
  background: #e2e8f0;
  color: #475569;
}

.news-category-badge.prestasi {
  background: #f59e0b;
  color: #fff;
  border: none;
}

.news-category-badge.kegiatan {
  background: #3b82f6;
  color: #fff;
  border: none;
}

.news-category-badge.pengumuman {
  background: #ef4444;
  color: #fff;
  border: none;
}

.news-category-badge.berita {
  background: #0ea5e9;
  color: #fff;
  border: none;
}

.news-loading, .news-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 300px;
  width: 100%;
  color: #64748b;
  gap: 1.5rem;
}

.loader {
  width: 48px;
  height: 48px;
  border: 5px solid #155d27;
  border-bottom-color: transparent;
  border-radius: 50%;
  display: inline-block;
  box-sizing: border-box;
  animation: rotation 1s linear infinite;
}

@keyframes rotation {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
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
  
  .articles-grid {
    grid-template-columns: 1fr;
  }
}

/* Article Cards Grid */
.articles-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  margin-top: 3rem;
}

.article-card {
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  transition: all 0.3s;
}

.article-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.article-image {
  height: 200px;
  overflow: hidden;
}

.article-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s;
}

.article-card:hover .article-image img {
  transform: scale(1.1);
}

.article-body {
  padding: 1.5rem;
}

.article-badge {
  display: inline-block;
  padding: 0.4rem 1rem;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 1rem;
  background: #e2e8f0;
  color: #475569;
}

/* Badge Colors - Prestasi = Yellow with white text, Kegiatan = Blue with white text */
.article-badge.prestasi {
  background: #f59e0b;
  color: #fff;
}

.article-badge.kegiatan {
  background: #3b82f6;
  color: #fff;
}

.article-badge.pengumuman {
  background: #fee2e2;
  color: #991b1b;
}

.article-badge.berita {
  background: #e0f2fe;
  color: #075985;
}

.article-body h3 {
  font-size: 1rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.75rem;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.article-body p {
  font-size: 0.9rem;
  color: #64748b;
  line-height: 1.6;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.no-articles {
  grid-column: 1 / -1;
  text-align: center;
  padding: 3rem;
  color: #64748b;
}

.article-btn {
  background: #155d27;
  color: #fff;
  border: none;
  padding: 0.6rem 1.5rem;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.3s;
  margin-top: 1rem;
}

.article-btn:hover {
  background: #0d4f1f;
  transform: translateY(-2px);
}

@media (max-width: 1024px) {
  .articles-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
