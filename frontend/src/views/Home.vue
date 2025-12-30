<template>
  <div class="home-wrapper">
    <PublicNavbar />

    <main>
      <!-- Hero Header Section -->
      <section class="hero-header">
        <div class="hero-header-content">
          <div class="hero-title-section">
            <h1 class="main-title">SMK YASMU GRESIK</h1>
            <h2 class="main-subtitle">Disiplin Kunci Sukses & Berprestasi</h2>
          </div>
          <a href="#spmb" class="spmb-btn">SPMB 2025</a>
        </div>
      </section>

      <!-- Hero Slider Section -->
      <section class="hero-slider" id="home">
        <Swiper :modules="[Pagination, Autoplay]" :pagination="{ clickable: true }" :autoplay="{ delay: 5000, disableOnInteraction: false }" :loop="slides.length >= 3" class="hero-swiper">
          <SwiperSlide v-for="(slide, index) in slides" :key="index">
            <div class="slide-container">
              <img :src="slide.image" :alt="slide.title || 'Hero Image'" class="slide-image" />
              <div v-if="slide.title" class="slide-overlay"></div>
              <div v-if="slide.title" class="slide-content">
                <h2>{{ slide.title }}</h2>
                <p>{{ slide.description }}</p>
                <a href="#" class="btn-selengkapnya">Selengkapnya</a>
              </div>
            </div>
          </SwiperSlide>
        </Swiper>
      </section>

      <!-- Statistics Banner -->
      <section class="stats-banner">
        <div class="stats-container">
          <div class="stat-item">
            <span class="stat-number">1999</span>
            <span class="stat-label">Tahun Berdiri</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">818</span>
            <span class="stat-label">Jumlah Siswa</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">50</span>
            <span class="stat-label">Guru & Staff</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">4309</span>
            <span class="stat-label">Lulusan</span>
          </div>
        </div>
      </section>

      <!-- Why Section -->
      <section class="why-section">
        <div class="why-container">
          <div class="why-header">
            <div class="why-header-left">
              <h2 class="why-title">KENAPA HARUS SMK YASMU?</h2>
              <p class="why-desc">SMK YASMU Manyar Gresik merupakan sekolah kejuruan yang terus berkembang sejak 1999, dengan enam kompetensi keahlian dan lebih dari 4.000 lulusan hingga tahun 2025.</p>
            </div>
            <a href="#profil" class="profil-link">
              Profil Sekolah
              <ArrowUpRight :size="18" />
            </a>
          </div>
          
          <div class="why-cards-wrapper">
            <div 
              v-for="(item, index) in whyItems" 
              :key="index"
              class="why-card"
              :class="{ 'why-card-active': activeWhyCard === index }"
              @click="activeWhyCard = index"
            >
              <h3>{{ item.title }}</h3>
              <div v-if="activeWhyCard === index" class="why-card-content">
                <p>{{ item.desc }}</p>
                <div class="why-card-icon">
                  <component :is="item.icon" :size="60" stroke-width="1.5" />
                </div>
              </div>
              <div v-else class="why-card-circle"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- News Section - Jejak Gemilang -->
      <section class="news-section" id="berita">
        <div class="news-container">
          <div class="news-header">
            <div class="news-title-section">
              <h2>JEJAK GEMILANG</h2>
              <h3>SISWA BERPRESTASI</h3>
            </div>
            <div class="news-trophy">
              <Trophy :size="48" stroke-width="1.5" />
            </div>
          </div>
          
          <div class="news-slider-wrapper">
            <button class="news-nav news-prev" @click="prevNews"><ChevronLeft :size="24" /></button>
            <div class="news-cards">
              <div class="news-card" v-for="(news, i) in newsItems" :key="i">
                <span class="news-badge" :class="news.category?.toLowerCase()">{{ news.category }}</span>
                <h4>{{ news.title }}</h4>
                <p>{{ news.excerpt }}</p>
                <div class="news-image-wrapper">
                  <img :src="news.image || '/slide-1.jpg'" :alt="news.title" />
                  <a href="#" class="news-btn">Selengkapnya</a>
                </div>
              </div>
            </div>
            <button class="news-nav news-next" @click="nextNews"><ChevronRight :size="24" /></button>
          </div>
        </div>
      </section>

      <!-- Programs Section - Sekilas Program Keahlian -->
      <section class="programs-section" id="jurusan">
        <div class="programs-container">
          <div class="programs-header">
            <div class="programs-title-section">
              <h2>SEKILAS</h2>
              <h3>PROGRAM KEAHLIAN</h3>
            </div>
            <div class="programs-icon">🏫</div>
          </div>
          
          <div class="programs-slider-wrapper">
            <div class="programs-nav-vertical">
              <button class="programs-nav programs-prev" @click="prevProgramPage"><ChevronUp :size="24" /></button>
              <button class="programs-nav programs-next" @click="nextProgramPage"><ChevronDown :size="24" /></button>
            </div>
            
            <div class="programs-cards">
              <div 
                v-for="(prog, i) in visiblePrograms" 
                :key="programPage * 3 + i"
                class="program-card"
                :class="{ 'program-card-active': activeProgramCard === i }"
                @click="activeProgramCard = activeProgramCard === i ? -1 : i"
              >
                <div class="program-image">
                  <img :src="prog.image || '/slide-1.jpg'" :alt="prog.name" />
                </div>
                
                <!-- Title overlay - always visible at bottom -->
                <div class="program-title-bar" :class="{ 'title-hidden': activeProgramCard === i }">
                  <h4>{{ prog.name }}</h4>
                  <span class="program-arrow">↑</span>
                </div>
                
                <!-- Detail panel - slides up when active -->
                <transition name="slide-up-detail">
                  <div v-if="activeProgramCard === i" class="program-detail-panel">
                    <h4>{{ prog.name }}</h4>
                    <p>{{ prog.desc }}</p>
                    <a href="#" class="program-btn">Selengkapnya →</a>
                  </div>
                </transition>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Partners Section - Kemitraan Strategis Dengan Dunia Industri -->
      <section class="partners-section">
        <div class="partners-container">
          <div class="partners-header">
            <h2>KEMITRAAN STRATEGIS DENGAN</h2>
            <h3>DUNIA INDUSTRI</h3>
            <p class="partners-desc">SMK Yasmu menjalin kerjasama erat dengan berbagai perusahaan dan instansi terkemuka untuk memastikan siswa mendapatkan pengalaman nyata di dunia kerja. Melalui program magang, pelatihan, dan rekrutmen langsung, kami membekali siswa dengan keterampilan sesuai kebutuhan industri masa kini.</p>
          </div>
          
          <div class="partners-marquee-wrapper">
            <div class="partners-marquee">
              <div class="partners-marquee-content">
                <div class="partner-logo" v-for="(p, i) in partners" :key="'first-' + i">
                  <img :src="p.logo" :alt="p.name" @error="(e) => e.target.style.display = 'none'" />
                  <span class="partner-name">{{ p.name }}</span>
                </div>
              </div>
              <!-- Duplicate for seamless loop -->
              <div class="partners-marquee-content" aria-hidden="true">
                <div class="partner-logo" v-for="(p, i) in partners" :key="'second-' + i">
                  <img :src="p.logo" :alt="p.name" @error="(e) => e.target.style.display = 'none'" />
                  <span class="partner-name">{{ p.name }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Testimonials Section - Apa Kata Mereka -->
      <section class="testimonials-section">
        <div class="testimonials-container">
          <div class="testimonials-header">
            <h2>"APA KATA MEREKA"</h2>
            <h3>TESTIMONI ALUMNI SMK YASMU</h3>
          </div>
          
          <!-- Row 1 - Moving Right to Left -->
          <div class="testimonial-marquee-wrapper">
            <div class="testimonial-marquee">
              <div class="testimonial-marquee-content">
                <div class="testimonial-card" v-for="(t, i) in testimonialRow1" :key="'row1-first-' + i">
                  <div class="testimonial-photo">
                    <img :src="t.photo" :alt="t.name" @error="(e) => e.target.src = '/default-avatar.png'" />
                  </div>
                  <div class="testimonial-info">
                    <h4>{{ t.name }}</h4>
                    <span class="testimonial-jurusan">{{ t.jurusan }}</span>
                  </div>
                  <div class="testimonial-quote-icon">❝</div>
                  <p class="testimonial-quote">{{ t.quote }}</p>
                </div>
              </div>
              <!-- Duplicate for seamless loop -->
              <div class="testimonial-marquee-content" aria-hidden="true">
                <div class="testimonial-card" v-for="(t, i) in testimonialRow1" :key="'row1-second-' + i">
                  <div class="testimonial-photo">
                    <img :src="t.photo" :alt="t.name" @error="(e) => e.target.src = '/default-avatar.png'" />
                  </div>
                  <div class="testimonial-info">
                    <h4>{{ t.name }}</h4>
                    <span class="testimonial-jurusan">{{ t.jurusan }}</span>
                  </div>
                  <div class="testimonial-quote-icon">❝</div>
                  <p class="testimonial-quote">{{ t.quote }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Row 2 - Moving Left to Right (Reverse) -->
          <div class="testimonial-marquee-wrapper">
            <div class="testimonial-marquee testimonial-marquee-reverse">
              <div class="testimonial-marquee-content">
                <div class="testimonial-card" v-for="(t, i) in testimonialRow2" :key="'row2-first-' + i">
                  <div class="testimonial-photo">
                    <img :src="t.photo" :alt="t.name" @error="(e) => e.target.src = '/default-avatar.png'" />
                  </div>
                  <div class="testimonial-info">
                    <h4>{{ t.name }}</h4>
                    <span class="testimonial-jurusan">{{ t.jurusan }}</span>
                  </div>
                  <div class="testimonial-quote-icon">❝</div>
                  <p class="testimonial-quote">{{ t.quote }}</p>
                </div>
              </div>
              <!-- Duplicate for seamless loop -->
              <div class="testimonial-marquee-content" aria-hidden="true">
                <div class="testimonial-card" v-for="(t, i) in testimonialRow2" :key="'row2-second-' + i">
                  <div class="testimonial-photo">
                    <img :src="t.photo" :alt="t.name" @error="(e) => e.target.src = '/default-avatar.png'" />
                  </div>
                  <div class="testimonial-info">
                    <h4>{{ t.name }}</h4>
                    <span class="testimonial-jurusan">{{ t.jurusan }}</span>
                  </div>
                  <div class="testimonial-quote-icon">❝</div>
                  <p class="testimonial-quote">{{ t.quote }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <PublicFooter />
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Autoplay } from 'swiper/modules';
import { ArrowUpRight, ChevronLeft, ChevronRight, ChevronUp, ChevronDown, Award, Building2, Lightbulb, Trophy } from 'lucide-vue-next';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';
import { publicApi } from '../services/api';
import 'swiper/css';
import 'swiper/css/pagination';

const mobileMenuOpen = ref(false);
const searchOpen = ref(false);
const searchQuery = ref('');
const activeWhyCard = ref(2);
const dataLoading = ref(true);

const toggleMobileMenu = () => { mobileMenuOpen.value = !mobileMenuOpen.value; };
const toggleSearch = () => { searchOpen.value = !searchOpen.value; };
const doSearch = () => { 
  if (searchQuery.value.trim()) {
    alert('Mencari: ' + searchQuery.value);
  }
};
const prevNews = () => { /* Navigate previous */ };
const nextNews = () => { /* Navigate next */ };
const activeProgramCard = ref(1);
const programPage = ref(0);
const visiblePrograms = computed(() => {
  const start = programPage.value * 3;
  return programs.value.slice(start, start + 3);
});
const prevProgramPage = () => { 
  if (programPage.value > 0) {
    programPage.value--;
    activeProgramCard.value = 1;
  }
};
const nextProgramPage = () => { 
  if ((programPage.value + 1) * 3 < programs.value.length) {
    programPage.value++;
    activeProgramCard.value = 1;
  }
};

// Static data (not from API)
const whyItems = ref([
  { title: 'Sekolah Rujukan Berstandar Nasional', desc: 'SMK Yasmu menjadi SMK Pusat Keunggulan dan rujukan bagi sekolah swasta lain di Gresik, dengan standar manajemen dan fasilitas nasional.', icon: Award },
  { title: 'Fasilitas Lengkap & Pembelajaran Efektif', desc: 'Didukung lahan luas dan alat praktik memadai, pembelajaran berjalan optimal dengan guru profesional.', icon: Building2 },
  { title: 'Penguatan Karakter & Jiwa Wirausaha', desc: 'Menguatkan akhlak lewat pendidikan agama dan membekali siswa dengan jiwa wirausaha berbasis inovasi.', icon: Lightbulb }
]);

// Dynamic data from API
const slides = ref([]);
const newsItems = ref([]);
const programs = ref([]);
const partners = ref([]);
const testimonialRow1 = ref([]);
const testimonialRow2 = ref([]);

// Fallback static data
const fallbackSlides = [
  { image: '/hero/Frame 313.png', title: '', description: '' },
  { image: '/hero/Frame 313.png', title: 'BEKALI SISWA HADAPI DUNIA INDUSTRI', description: 'SMK Yasmu gelar pembekalan PKL untuk siswa kelas XI.' },
  { image: '/hero/Frame 313.png', title: 'INOVASI HIJAU: UBAH SAMPAH JADI PUPUK', description: 'Program lingkungan mengolah sampah organik menjadi pupuk.' },
  { image: '/hero/Frame 313.png', title: 'PRESTASI GEMILANG SISWA SMK YASMU', description: 'Meraih berbagai prestasi tingkat regional dan nasional.' }
];

const fallbackPrograms = [
  { name: 'TEKNIK PEMESINAN', desc: 'Teknik Pemesinan mempersiapkan siswa untuk menjadi tenaga ahli yang profesional dan terampil dalam mengoperasikan mesin-mesin industri.', image: '/slide-1.jpg' },
  { name: 'TEKNIK OTOMOTIF', desc: 'Teknik Otomotif mempersiapkan siswa untuk menjadi tenaga mekanik yang profesional dan terampil dalam menangani kendaraan ringan.', image: '/slide-2.jpg' },
  { name: 'DESAIN KOMUNIKASI VISUAL', desc: 'Desain Komunikasi Visual mempersiapkan siswa untuk menjadi tenaga kreatif yang profesional dalam bidang desain grafis.', image: '/slide-3.jpg' },
  { name: 'ANALISIS PENGUJIAN LAB', desc: 'Analisis Pengujian Lab mempersiapkan siswa untuk menjadi tenaga analis yang profesional dalam laboratorium industri.', image: '/slide-1.jpg' },
  { name: 'MANAJEMEN PERKANTORAN', desc: 'Manajemen Perkantoran mempersiapkan siswa untuk menjadi tenaga administrasi profesional.', image: '/slide-2.jpg' },
  { name: 'TEKNIK LOGISTIK', desc: 'Teknik Logistik mempersiapkan siswa untuk menjadi tenaga ahli dalam manajemen rantai pasokan.', image: '/slide-3.jpg' }
];

// Helper function to get image URL
const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http') || path.startsWith('/')) return path;
  return `http://localhost:8000/storage/${path}`;
};

// Load data from API
const loadData = async () => {
  dataLoading.value = true;
  
  try {
    // Load hero slides
    const slidesRes = await publicApi.getHeroSlides();
    if (slidesRes.data && slidesRes.data.length > 0) {
      slides.value = slidesRes.data.map(s => ({
        ...s,
        image: getImageUrl(s.image)
      }));
    } else {
      slides.value = fallbackSlides;
    }
  } catch (e) {
    console.log('Using fallback slides');
    slides.value = fallbackSlides;
  }
  
  try {
    // Load articles for Jejak Gemilang (only Prestasi)
    const articlesRes = await publicApi.getArticles(20);
    const articlesData = articlesRes.data?.data || articlesRes.data || [];
    // Filter only Prestasi category
    const prestasiArticles = articlesData.filter(a => 
      a.category?.toLowerCase() === 'prestasi'
    ).slice(0, 6);
    if (prestasiArticles.length > 0) {
      newsItems.value = prestasiArticles.map(a => ({
        category: a.category || 'Prestasi',
        title: a.title,
        excerpt: a.excerpt || '',
        image: getImageUrl(a.image) || '/slide-1.jpg'
      }));
    }
  } catch (e) {
    console.log('Error loading articles:', e);
  }
  
  try {
    // Load programs
    const programsRes = await publicApi.getPrograms();
    if (programsRes.data && programsRes.data.length > 0) {
      programs.value = programsRes.data.map(p => ({
        name: p.name.toUpperCase(),
        desc: p.description,
        image: getImageUrl(p.image) || '/slide-1.jpg'
      }));
    } else {
      programs.value = fallbackPrograms;
    }
  } catch (e) {
    console.log('Using fallback programs');
    programs.value = fallbackPrograms;
  }
  
  try {
    // Load partners
    const partnersRes = await publicApi.getPartners();
    if (partnersRes.data && partnersRes.data.length > 0) {
      partners.value = partnersRes.data.map(p => ({
        name: p.name,
        logo: getImageUrl(p.logo)
      }));
    }
  } catch (e) {
    console.log('Error loading partners:', e);
  }
  
  try {
    // Load testimonials
    const testimonialsRes = await publicApi.getTestimonials();
    if (testimonialsRes.data && testimonialsRes.data.length > 0) {
      const allTestimonials = testimonialsRes.data.map(t => ({
        name: t.name,
        jurusan: t.jurusan,
        photo: getImageUrl(t.photo) || '/default-avatar.png',
        quote: t.quote
      }));
      // Split into two rows
      const mid = Math.ceil(allTestimonials.length / 2);
      testimonialRow1.value = allTestimonials.slice(0, mid);
      testimonialRow2.value = allTestimonials.slice(mid);
    }
  } catch (e) {
    console.log('Error loading testimonials:', e);
  }
  
  dataLoading.value = false;
};

onMounted(() => {
  loadData();
});
</script>

<style scoped>
* { margin: 0; padding: 0; box-sizing: border-box; }
.home-wrapper { width: 100%; background: #fff; }

/* Hero Header */
.hero-header { margin-top: 80px; padding: 2rem 3rem; background: #fff; }
.hero-header-content { display: flex; justify-content: space-between; align-items: center; max-width: 1400px; margin: 0 auto; }
.main-title { font-size: 2.8rem; font-weight: 900; color: #155d27; letter-spacing: 1px; }
.main-subtitle { font-size: 1.4rem; font-weight: 600; color: #d4a017; margin-top: 0.25rem; }
.spmb-btn { background: #d4a017; color: #2c3e50; padding: 0.9rem 2.5rem; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 1rem; transition: all 0.3s; box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3); }
.spmb-btn:hover { background: #b8860b; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 160, 23, 0.4); }

/* Hero Slider */
.hero-slider { margin: 0 3rem 2rem 3rem; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.15); }
.hero-swiper { width: 100%; height: 450px; }
.slide-container { position: relative; width: 100%; height: 100%; }
.slide-image { width: 100%; height: 100%; object-fit: cover; }
.slide-overlay { position: absolute; bottom: 0; left: 0; right: 0; height: 60%; background: linear-gradient(to top, rgba(0,0,0,0.75), transparent); }
.slide-content { position: absolute; bottom: 3rem; left: 3rem; right: 3rem; max-width: 650px; color: #fff; z-index: 5; }
.slide-content h2 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem; line-height: 1.3; }
.slide-content p { font-size: 0.95rem; margin-bottom: 1rem; color: rgba(255,255,255,0.9); line-height: 1.5; }
.btn-selengkapnya { display: inline-block; padding: 0.6rem 1.5rem; border: 2px solid #d4a017; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.9rem; transition: all 0.3s; }
.btn-selengkapnya:hover { background: #d4a017; color: #2c3e50; }
:deep(.swiper-pagination) { bottom: 1.5rem !important; }
:deep(.swiper-pagination-bullet) { width: 10px; height: 10px; background: rgba(255,255,255,0.5); opacity: 1; margin: 0 5px !important; }
:deep(.swiper-pagination-bullet-active) { background: #fff; }

/* Stats Banner */
.stats-banner { background: #155d27; padding: 2rem 0; }
.stats-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-around; flex-wrap: wrap; gap: 1rem; }
.stat-item { text-align: center; color: #fff; }
.stat-number { display: block; font-size: 2.5rem; font-weight: 800; }
.stat-label { font-size: 0.95rem; opacity: 0.9; }

/* Why Section */
.why-section { padding: 4rem 3rem; background: #f8faf9; }
.why-container { max-width: 1400px; margin: 0 auto; }
.why-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; gap: 2rem; }
.why-header-left { max-width: 600px; }
.why-title { font-size: 1.8rem; font-weight: 800; color: #155d27; margin-bottom: 0.75rem; }
.why-desc { color: #64748b; line-height: 1.6; font-size: 0.95rem; }
.profil-link { display: flex; align-items: center; gap: 0.5rem; color: #155d27; text-decoration: none; font-weight: 600; padding: 0.75rem 1.5rem; border: 2px solid #155d27; border-radius: 8px; transition: all 0.3s; white-space: nowrap; }
.profil-link:hover { background: #155d27; color: #fff; }

/* Cards Wrapper - Flex Expand Effect */
.why-cards-wrapper { display: flex; gap: 1rem; margin-top: 1.5rem; }
.why-card { flex: 1; background: #fff; padding: 1.5rem; min-height: 280px; cursor: pointer; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden; border: 2px solid #e5e7eb; display: flex; flex-direction: column; border-radius: 30px 5px 30px 5px; }
.why-card h3 { color: #155d27; font-size: 1.4rem; font-weight: 700; line-height: 1.3; margin-bottom: 1rem; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
.why-card-circle { width: 120px; height: 120px; background: linear-gradient(135deg, #d4a017 0%, #f5d78e 100%); border-radius: 50%; position: absolute; bottom: -20px; left: -20px; opacity: 0.5; transition: all 0.5s; }
.why-card:hover:not(.why-card-active) { border-color: #d4a017; }
.why-card:hover:not(.why-card-active) .why-card-circle { opacity: 0.7; transform: scale(1.1); }

/* Active Card - Expanded */
.why-card-active { flex: 2.5; background: linear-gradient(135deg, #e5e7eb 0%, #f3f4f6 100%); border-color: #d1d5db; }
.why-card-active h3 { color: #155d27; font-size: 2rem; }
.why-card-content { flex: 1; display: flex; justify-content: space-between; align-items: flex-end; gap: 1.5rem; animation: fadeIn 0.5s ease; }
.why-card-content p { color: #155d27; font-size: 1.05rem; line-height: 1.7; max-width: 55%; opacity: 1; transition: opacity 0.4s ease, transform 0.4s ease; }
.why-card-icon { width: 120px; height: 120px; background: transparent; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: transform 0.4s ease; overflow: hidden; }
.why-card-active:hover .why-card-icon { transform: scale(1.05); }
.why-icon-img { width: 100%; height: 100%; object-fit: contain; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* News Section - Jejak Gemilang */
.news-section { padding: 4rem 3rem; background: #fff; }
.news-container { max-width: 1400px; margin: 0 auto; }
.news-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.news-title-section h2 { font-size: 1.8rem; font-weight: 800; color: #155d27; margin: 0; }
.news-title-section h3 { font-size: 1.5rem; font-weight: 700; color: #155d27; margin: 0; }
.news-trophy { color: #155d27; }
.news-slider-wrapper { display: flex; align-items: center; gap: 1rem; position: relative; }
.news-nav { width: 45px; height: 45px; border-radius: 50%; border: 2px solid #155d27; background: #fff; color: #155d27; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; flex-shrink: 0; }
.news-nav:hover { background: #155d27; color: #fff; }
.news-cards { display: flex; gap: 1.5rem; flex: 1; overflow: hidden; }
.news-card { flex: 1; min-width: 300px; background: #f8faf9; border-radius: 30px 5px 30px 5px; padding: 1.5rem; display: flex; flex-direction: column; }
.news-badge { 
  display: inline-block; 
  background: #e2e8f0; 
  color: #475569; 
  padding: 0.4rem 1rem; 
  border-radius: 50px; 
  font-size: 0.75rem; 
  font-weight: 700; 
  margin-bottom: 1rem; 
  width: fit-content; 
  text-transform: uppercase;
  letter-spacing: 0.5px;
}
.news-badge.prestasi { background: #f59e0b; color: #fff; border: none; }
.news-badge.kegiatan { background: #3b82f6; color: #fff; border: none; }
.news-badge.pengumuman { background: #ef4444; color: #fff; border: none; }
.news-badge.berita { background: #0ea5e9; color: #fff; border: none; }
.news-card h4 { color: #155d27; font-size: 1.1rem; font-weight: 700; line-height: 1.4; margin-bottom: 0.75rem; }
.news-card p { color: #64748b; font-size: 0.85rem; line-height: 1.5; margin-bottom: 1rem; flex: 1; }
.news-image-wrapper { position: relative; border-radius: 20px; overflow: hidden; height: 180px; }
.news-image-wrapper img { width: 100%; height: 100%; object-fit: cover; }
.news-btn { position: absolute; bottom: 1rem; left: 1rem; background: #0e7490; color: #fff; padding: 0.5rem 1.2rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; text-decoration: none; transition: all 0.3s; }
.news-btn:hover { background: #0c5f75; }

/* Programs - Sekilas Program Keahlian */
.programs-section { padding: 5rem 3rem; background: #fff; }
.programs-container { max-width: 1400px; margin: 0 auto; position: relative; padding-right: 80px; }
.programs-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem; }
.programs-title-section h2 { font-size: 1.6rem; font-weight: 700; color: #155d27; margin: 0; font-style: italic; opacity: 0.8; }
.programs-title-section h3 { font-size: 2.2rem; font-weight: 900; color: #155d27; margin: 0; }
.programs-icon { font-size: 5rem; opacity: 0.1; position: absolute; right: 0; top: -10px; }
.programs-slider-wrapper { display: flex; align-items: center; gap: 2rem; position: relative; }
.programs-nav { width: 50px; height: 50px; border-radius: 15px; border: none; background: #f0fdf4; color: #155d27; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); flex-shrink: 0; box-shadow: 0 4px 12px rgba(21, 93, 39, 0.1); }
.programs-nav:hover { background: #155d27; color: #fff; transform: scale(1.1) rotate(5deg); box-shadow: 0 8px 20px rgba(21, 93, 39, 0.2); }
.programs-nav-vertical { 
  display: flex; 
  flex-direction: column; 
  gap: 1rem; 
  position: absolute;
  right: -60px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
}
.programs-cards { 
  display: flex; 
  gap: 2rem; 
  flex: 1; 
  position: relative; 
  min-height: 480px;
  padding: 1rem 0;
  justify-content: center;
  flex-wrap: wrap;
}

/* Transition for sliding cards up */
.slide-up-cards-enter-active,
.slide-up-cards-leave-active {
  transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-up-cards-enter-from {
  opacity: 0;
  transform: translateY(150px) scale(0.9);
}
.slide-up-cards-leave-to {
  opacity: 0;
  transform: translateY(-150px) scale(0.9);
  position: absolute;
}
.slide-up-cards-move {
  transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.program-card {
  flex: 1;
  min-width: 280px;
  max-width: 400px;
  height: 450px;
  cursor: pointer;
  transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  border-radius: 24px;
  overflow: hidden;
  position: relative;
  background: #1a1a2e;
}

.program-card:hover {
  transform: translateY(-12px);
  box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
}

.program-image {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 100%;
  z-index: 1;
  transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.program-card-active .program-image {
  height: 45%;
  border-radius: 24px 24px 0 0;
}

.program-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.program-card:hover .program-image img {
  transform: scale(1.1);
}

/* Title bar at bottom - visible when not active */
.program-title-bar {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 5;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
  padding: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.program-title-bar h4 {
  color: #fff;
  font-size: 1.1rem;
  font-weight: 700;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.program-arrow {
  color: #fff;
  font-size: 1.5rem;
  animation: bounceUp 1.5s ease-in-out infinite;
}

@keyframes bounceUp {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-8px); }
}

.program-title-bar.title-hidden {
  transform: translateY(100%);
  opacity: 0;
}

/* Detail panel - slides up when active */
.program-detail-panel {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  top: 45%;
  z-index: 10;
  background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  border-radius: 0;
  box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.1);
}

.program-detail-panel h4 {
  color: #155d27;
  font-size: 1.5rem;
  font-weight: 900;
  margin-bottom: 1rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.program-detail-panel p {
  color: #374151;
  font-size: 0.95rem;
  line-height: 1.7;
  flex: 1;
  margin-bottom: 1.5rem;
  overflow-y: auto;
}

.program-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: #155d27;
  color: #fff;
  padding: 0.9rem 2rem;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 700;
  text-decoration: none;
  width: fit-content;
  transition: all 0.3s;
  box-shadow: 0 8px 20px rgba(21, 93, 39, 0.3);
}

.program-btn:hover {
  background: #0d4f1f;
  transform: scale(1.05);
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
}

/* Slide up transition for detail panel */
.slide-up-detail-enter-active {
  transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide-up-detail-leave-active {
  transition: all 0.4s cubic-bezier(0.5, 0, 0.75, 0);
}

.slide-up-detail-enter-from,
.slide-up-detail-leave-to {
  transform: translateY(100%);
  opacity: 0;
}

/* Active card state */
.program-card-active {
  box-shadow: 0 35px 70px -15px rgba(21, 93, 39, 0.4);
}

/* Partners Section - Marquee Animation */
.partners-section { 
  padding: 4rem 2rem; 
  background: #fff; 
  overflow: hidden;
}

.partners-container { 
  max-width: 1400px; 
  margin: 0 auto; 
}

.partners-header { 
  text-align: center; 
  margin-bottom: 3rem; 
}

.partners-header h2 { 
  font-size: 1.8rem; 
  font-weight: 800; 
  color: #155d27; 
  margin: 0; 
}

.partners-header h3 { 
  font-size: 2rem; 
  font-weight: 900; 
  color: #155d27; 
  margin: 0.25rem 0 1rem;
}

.partners-desc { 
  color: #64748b; 
  font-size: 0.95rem; 
  line-height: 1.7; 
  max-width: 800px; 
  margin: 0 auto; 
}

.partners-marquee-wrapper { 
  width: 100%; 
  overflow: hidden; 
  padding: 2rem 0;
  position: relative;
}

/* Gradient fade on edges */
.partners-marquee-wrapper::before,
.partners-marquee-wrapper::after {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  width: 100px;
  z-index: 10;
  pointer-events: none;
}

.partners-marquee-wrapper::before {
  left: 0;
  background: linear-gradient(to right, #fff 0%, transparent 100%);
}

.partners-marquee-wrapper::after {
  right: 0;
  background: linear-gradient(to left, #fff 0%, transparent 100%);
}

.partners-marquee { 
  display: flex; 
  animation: marquee 20s linear infinite;
  width: fit-content;
}

.partners-marquee:hover {
  animation-play-state: paused;
}

.partners-marquee-content { 
  display: flex; 
  gap: 3rem; 
  padding: 0 1.5rem;
  flex-shrink: 0;
}

.partner-logo { 
  background: #fff; 
  padding: 2rem 2.5rem; 
  border-radius: 16px; 
  transition: all 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 200px;
  height: 140px;
}

.partner-logo:hover {
  transform: scale(1.08);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.partner-logo img { 
  max-height: 90px; 
  max-width: 180px; 
  object-fit: contain;
  filter: grayscale(0%);
  transition: all 0.3s;
}

.partner-name {
  font-size: 0.9rem;
  font-weight: 700;
  color: #155d27;
  text-align: center;
  display: none;
}

/* Show name when image is hidden */
.partner-logo img[style*="display: none"] + .partner-name {
  display: block;
}

/* If no image loaded successfully, fallback styling */
.partner-logo:has(img[style*="display: none"]) {
  background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
  border: 2px solid #d1fae5;
}

/* Marquee animation - right to left */
@keyframes marquee {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

/* Testimonials Section - Marquee Animation */
.testimonials-section { 
  padding: 4rem 0; 
  background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
  overflow: hidden;
}

.testimonials-container { 
  max-width: 100%;
  overflow: hidden;
}

.testimonials-header { 
  text-align: center; 
  margin-bottom: 3rem;
  padding: 0 2rem;
}

.testimonials-header h2 { 
  font-size: 1.2rem; 
  font-weight: 600; 
  color: #155d27; 
  margin: 0;
  letter-spacing: 1px;
}

.testimonials-header h3 { 
  font-size: 2rem; 
  font-weight: 900; 
  color: #155d27; 
  margin: 0.5rem 0 0;
  letter-spacing: 2px;
}

.testimonial-marquee-wrapper { 
  width: 100%; 
  overflow: hidden; 
  padding: 1rem 0;
  position: relative;
}

.testimonial-marquee { 
  display: flex; 
  animation: testimonialMarquee 35s linear infinite;
  width: fit-content;
}

.testimonial-marquee:hover {
  animation-play-state: paused;
}

/* Reverse direction for Row 2 */
.testimonial-marquee-reverse {
  animation: testimonialMarqueeReverse 35s linear infinite;
}

.testimonial-marquee-content { 
  display: flex; 
  gap: 1.5rem; 
  padding: 0 0.75rem;
  flex-shrink: 0;
}

.testimonial-card { 
  background: #fff;
  padding: 1.5rem;
  border-radius: 20px;
  min-width: 320px;
  max-width: 380px;
  position: relative;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 30px rgba(21, 93, 39, 0.15);
}

.testimonial-photo {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  overflow: hidden;
  border: 3px solid #155d27;
  flex-shrink: 0;
}

.testimonial-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
}

.testimonial-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.testimonial-card h4 { 
  color: #155d27; 
  font-size: 1rem;
  font-weight: 800;
  margin: 0;
  letter-spacing: 0.5px;
}

.testimonial-jurusan {
  color: #64748b;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.testimonial-quote-icon {
  font-size: 2.5rem;
  color: #155d27;
  opacity: 0.3;
  line-height: 1;
  margin-top: 0.5rem;
}

.testimonial-quote { 
  color: #374151; 
  font-size: 0.9rem;
  line-height: 1.6;
  margin: 0;
  flex: 1;
}

/* Marquee animation - Right to Left */
@keyframes testimonialMarquee {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

/* Marquee animation - Left to Right (Reverse) */
@keyframes testimonialMarqueeReverse {
  0% {
    transform: translateX(-50%);
  }
  100% {
    transform: translateX(0);
  }
}

/* Responsive */
@media (max-width: 768px) {
  .hero { height: auto; min-height: 400px; }
  .main-title { font-size: 1.6rem; }
  .main-subtitle { font-size: 1rem; }
  .spmb-btn { padding: 0.5rem 1rem; font-size: 0.8rem; }
  .slide-content h2 { font-size: 1.1rem; }
  .section-title { font-size: 1.8rem; }
  .stat-number { font-size: 1.8rem; }
}
</style>
