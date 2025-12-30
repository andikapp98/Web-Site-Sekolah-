<template>
  <div class="page-wrapper">
    <PublicNavbar />
    <main class="content-main">
      <!-- Header -->
      <div class="galeri-header">
        <button class="back-btn" @click="goBack">
          <ArrowLeft :size="24" />
        </button>
        <h1 class="galeri-title">GALERI MEDIA</h1>
      </div>

      <!-- Filter Tabs -->
      <div class="filter-tabs">
        <button 
          v-for="tab in tabs" 
          :key="tab.value"
          :class="['filter-tab', { active: activeTab === tab.value }]"
          @click="activeTab = tab.value"
        >
          <component :is="tab.icon" :size="18" />
          <span>{{ tab.label }}</span>
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="loading-state">
        <span class="loader"></span>
        <p>Memuat galeri...</p>
      </div>

      <!-- Photo Gallery -->
      <section v-else-if="activeTab === 'photo'" class="gallery-section">
        <div class="photo-grid">
          <div 
            v-for="(photo, index) in photos" 
            :key="photo.id" 
            class="photo-item"
            @click="openLightbox(index)"
          >
            <img :src="getImageUrl(photo.image)" :alt="photo.title" />
            <div class="photo-overlay">
              <h4>{{ photo.title }}</h4>
            </div>
          </div>
          <div v-if="photos.length === 0" class="empty-state">
            <Camera :size="48" />
            <p>Belum ada foto</p>
          </div>
        </div>
      </section>

      <!-- Reels Section -->
      <section v-else-if="activeTab === 'reels'" class="gallery-section">
        <div class="reels-grid">
          <div 
            v-for="reel in reels" 
            :key="reel.id" 
            class="reel-item"
            @click="openReelModal(reel)"
          >
            <div class="reel-thumbnail">
              <img v-if="reel.thumbnail" :src="getImageUrl(reel.thumbnail)" :alt="reel.title" />
              <div v-else class="reel-placeholder">
                <Film :size="32" />
              </div>
              <div class="play-overlay">
                <Play :size="32" />
              </div>
            </div>
            <h4>{{ reel.title }}</h4>
          </div>
          <div v-if="reels.length === 0" class="empty-state">
            <Film :size="48" />
            <p>Belum ada reels</p>
          </div>
        </div>
      </section>

      <!-- YouTube Section -->
      <section v-else-if="activeTab === 'youtube'" class="gallery-section">
        <div class="youtube-grid">
          <div 
            v-for="video in youtubeVideos" 
            :key="video.id" 
            class="youtube-item"
          >
            <div class="youtube-embed">
              <iframe 
                :src="getYoutubeEmbedUrl(video.video_url)" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
              ></iframe>
            </div>
            <h4>{{ video.title }}</h4>
            <p v-if="video.description">{{ video.description }}</p>
          </div>
          <div v-if="youtubeVideos.length === 0" class="empty-state">
            <Youtube :size="48" />
            <p>Belum ada video YouTube</p>
          </div>
        </div>
      </section>

      <!-- Lightbox Modal -->
      <div v-if="lightboxOpen" class="lightbox-overlay" @click="closeLightbox">
        <button class="lightbox-close" @click="closeLightbox">&times;</button>
        <button class="lightbox-nav lightbox-prev" @click.stop="prevPhoto"><ChevronLeft :size="32" /></button>
        <div class="lightbox-content" @click.stop>
          <img :src="getImageUrl(photos[currentPhotoIndex]?.image)" :alt="photos[currentPhotoIndex]?.title" />
          <div class="lightbox-caption">
            <h3>{{ photos[currentPhotoIndex]?.title }}</h3>
            <p v-if="photos[currentPhotoIndex]?.description">{{ photos[currentPhotoIndex]?.description }}</p>
          </div>
        </div>
        <button class="lightbox-nav lightbox-next" @click.stop="nextPhoto"><ChevronRight :size="32" /></button>
      </div>

      <!-- Reels Modal -->
      <div v-if="reelModalOpen" class="reel-modal-overlay" @click="closeReelModal">
        <div class="reel-modal" @click.stop>
          <button class="reel-modal-close" @click="closeReelModal">&times;</button>
          <div class="reel-modal-content">
            <iframe 
              :src="currentReelEmbedUrl" 
              frameborder="0" 
              allowfullscreen
            ></iframe>
          </div>
          <div class="reel-modal-info">
            <h3>{{ currentReel?.title }}</h3>
            <p v-if="currentReel?.description">{{ currentReel.description }}</p>
          </div>
        </div>
      </div>
    </main>
    <PublicFooter />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { ArrowLeft, Camera, Film, Play, Youtube, ChevronLeft, ChevronRight, Image as ImageIcon } from 'lucide-vue-next';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';
import { publicApi } from '../services/api';

const router = useRouter();
const loading = ref(true);
const activeTab = ref('photo');

const photos = ref([]);
const reels = ref([]);
const youtubeVideos = ref([]);

// Lightbox state
const lightboxOpen = ref(false);
const currentPhotoIndex = ref(0);

// Reel modal state
const reelModalOpen = ref(false);
const currentReel = ref(null);
const currentReelEmbedUrl = ref('');

const tabs = [
  { value: 'photo', label: 'Foto', icon: Camera },
  { value: 'reels', label: 'Reels IG', icon: Film },
  { value: 'youtube', label: 'YouTube', icon: Youtube },
];

const goBack = () => router.push('/');

const loadData = async () => {
  loading.value = true;
  try {
    const [photosRes, reelsRes, youtubeRes] = await Promise.all([
      publicApi.getPhotos(),
      publicApi.getReels(),
      publicApi.getYoutubeVideos()
    ]);
    photos.value = photosRes.data || [];
    reels.value = reelsRes.data || [];
    youtubeVideos.value = youtubeRes.data || [];
  } catch (e) {
    console.error('Error loading gallery:', e);
  } finally {
    loading.value = false;
  }
};

const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http')) return path;
  return `http://localhost:8000/storage/${path}`;
};

const getYoutubeEmbedUrl = (url) => {
  if (!url) return '';
  const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
  if (match && match[1]) {
    return `https://www.youtube.com/embed/${match[1]}`;
  }
  return '';
};

const getInstagramEmbedUrl = (url) => {
  // Convert Instagram URL to embed format
  if (!url) return '';
  // Format: https://www.instagram.com/reel/XXX/ -> https://www.instagram.com/reel/XXX/embed/
  return url.replace(/\/$/, '') + '/embed/';
};

// Lightbox functions
const openLightbox = (index) => {
  currentPhotoIndex.value = index;
  lightboxOpen.value = true;
  document.body.style.overflow = 'hidden';
};

const closeLightbox = () => {
  lightboxOpen.value = false;
  document.body.style.overflow = '';
};

const prevPhoto = () => {
  currentPhotoIndex.value = (currentPhotoIndex.value - 1 + photos.value.length) % photos.value.length;
};

const nextPhoto = () => {
  currentPhotoIndex.value = (currentPhotoIndex.value + 1) % photos.value.length;
};

// Reel modal functions
const openReelModal = (reel) => {
  currentReel.value = reel;
  currentReelEmbedUrl.value = getInstagramEmbedUrl(reel.video_url);
  reelModalOpen.value = true;
  document.body.style.overflow = 'hidden';
};

const closeReelModal = () => {
  reelModalOpen.value = false;
  currentReel.value = null;
  currentReelEmbedUrl.value = '';
  document.body.style.overflow = '';
};

onMounted(loadData);
</script>

<style scoped>
.page-wrapper { min-height: 100vh; background: #fff; }
.content-main { margin-top: 80px; padding: 2rem 3rem 4rem; max-width: 1400px; margin-left: auto; margin-right: auto; }

/* Header */
.galeri-header {
  display: flex;
  align-items: center;
  margin-bottom: 2rem;
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

.galeri-title {
  font-size: 2.5rem;
  font-weight: 900;
  color: #155d27;
  flex: 1;
  text-align: center;
}

/* Filter Tabs */
.filter-tabs {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-bottom: 3rem;
  flex-wrap: wrap;
}

.filter-tab {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: transparent;
  color: #64748b;
  border: 2px solid #e5e7eb;
  padding: 0.75rem 1.5rem;
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

/* Loading */
.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 300px;
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
  animation: rotation 1s linear infinite;
}

@keyframes rotation {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Photo Grid */
.photo-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.photo-item {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer;
  aspect-ratio: 4/3;
  background: #f1f5f9;
}

.photo-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s;
}

.photo-item:hover img {
  transform: scale(1.1);
}

.photo-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 1.5rem;
  background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
  opacity: 0;
  transition: opacity 0.3s;
}

.photo-item:hover .photo-overlay {
  opacity: 1;
}

.photo-overlay h4 {
  color: #fff;
  font-size: 1rem;
  font-weight: 600;
}

/* Reels Grid */
.reels-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1.5rem;
}

.reel-item {
  cursor: pointer;
  transition: transform 0.3s;
}

.reel-item:hover {
  transform: translateY(-8px);
}

.reel-thumbnail {
  position: relative;
  aspect-ratio: 9/16;
  border-radius: 16px;
  overflow: hidden;
  background: linear-gradient(135deg, #833AB4 0%, #FD1D1D 50%, #FCAF45 100%);
  margin-bottom: 0.75rem;
}

.reel-thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.reel-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: rgba(255,255,255,0.8);
}

.play-overlay {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 60px;
  height: 60px;
  background: rgba(255,255,255,0.9);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #833AB4;
  transition: all 0.3s;
}

.reel-item:hover .play-overlay {
  transform: translate(-50%, -50%) scale(1.1);
  background: #fff;
}

.reel-item h4 {
  font-size: 0.9rem;
  font-weight: 600;
  color: #1e293b;
  text-align: center;
}

/* YouTube Grid */
.youtube-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 2rem;
}

.youtube-item {
  background: #f8fafc;
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.3s;
}

.youtube-item:hover {
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
  transform: translateY(-4px);
}

.youtube-embed {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%;
}

.youtube-embed iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.youtube-item h4 {
  padding: 1rem 1rem 0.5rem;
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
}

.youtube-item p {
  padding: 0 1rem 1rem;
  font-size: 0.85rem;
  color: #64748b;
  line-height: 1.5;
}

/* Empty State */
.empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 4rem 2rem;
  color: #94a3b8;
}

.empty-state p {
  margin-top: 1rem;
  font-size: 1rem;
}

/* Lightbox */
.lightbox-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.95);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.lightbox-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: none;
  border: none;
  color: #fff;
  font-size: 2.5rem;
  cursor: pointer;
  z-index: 1001;
}

.lightbox-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255,255,255,0.1);
  border: none;
  color: #fff;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;
}

.lightbox-nav:hover {
  background: rgba(255,255,255,0.2);
}

.lightbox-prev { left: 1rem; }
.lightbox-next { right: 1rem; }

.lightbox-content {
  max-width: 90vw;
  max-height: 90vh;
  text-align: center;
}

.lightbox-content img {
  max-width: 100%;
  max-height: 80vh;
  object-fit: contain;
  border-radius: 8px;
}

.lightbox-caption {
  margin-top: 1rem;
  color: #fff;
}

.lightbox-caption h3 {
  font-size: 1.25rem;
  font-weight: 600;
}

.lightbox-caption p {
  font-size: 0.9rem;
  opacity: 0.8;
  margin-top: 0.5rem;
}

/* Reel Modal */
.reel-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.9);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.reel-modal {
  background: #fff;
  border-radius: 16px;
  overflow: hidden;
  max-width: 400px;
  width: 100%;
  position: relative;
}

.reel-modal-close {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background: rgba(0,0,0,0.5);
  border: none;
  color: #fff;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  font-size: 1.5rem;
  cursor: pointer;
  z-index: 10;
}

.reel-modal-content {
  aspect-ratio: 9/16;
  max-height: 70vh;
}

.reel-modal-content iframe {
  width: 100%;
  height: 100%;
}

.reel-modal-info {
  padding: 1rem;
}

.reel-modal-info h3 {
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.5rem;
}

.reel-modal-info p {
  font-size: 0.85rem;
  color: #64748b;
}

/* Responsive */
@media (max-width: 768px) {
  .content-main {
    padding: 1.5rem;
  }
  
  .galeri-title {
    font-size: 1.8rem;
  }
  
  .photo-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }
  
  .reels-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .youtube-grid {
    grid-template-columns: 1fr;
  }
  
  .filter-tab span {
    display: none;
  }
  
  .filter-tab {
    padding: 0.75rem 1rem;
  }
}
</style>
