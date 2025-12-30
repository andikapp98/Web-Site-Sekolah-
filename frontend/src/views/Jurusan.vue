<template>
  <div class="page-wrapper">
    <PublicNavbar />
    
    <main class="content-main">
      <div v-for="(prog, i) in programs" :key="i" :id="prog.id" class="jurusan-section">
        <!-- Background Media -->
        <div class="section-bg">
          <div class="overlay"></div>
          <video 
            v-if="prog.video" 
            :src="getVideoUrl(prog.video)" 
            autoplay 
            loop 
            muted 
            playsinline
            class="section-media"
          ></video>
          <img v-else :src="getImageUrl(prog.image)" :alt="prog.name" class="section-media" />
        </div>

        <!-- Content Card -->
        <div class="section-content">
          <div class="content-card glass-panel">
            <h2 class="program-title">{{ prog.name }}</h2>
            <div class="program-body">
              <p><strong>Jurusan {{ prog.name }}</strong> {{ prog.desc }}</p>
              <ul class="features-list">
                <li v-for="(feat, fi) in prog.features" :key="fi">✅ {{ feat }}</li>
              </ul>
              <router-link :to="'/jurusan/' + prog.id" class="detail-btn">
                Info Lengkap <span class="arrow">→</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Program Details Cards Section -->
      <section class="program-cards-section">
        <div class="container">
          <div class="cards-grid">
            <!-- Peluang Karir Card -->
            <div class="info-card career-card">
              <div class="card-content">
                <h3 class="card-title">PELUANG KARIR</h3>
                <p class="card-description">
                  Peluang karir bagi lulusan sangat luas dan tersebar di berbagai sektor industri manufaktur. 
                  Mereka dapat berkarir sebagai Operator Mesin Bubut, Frais, Gerinda, maupun Operator CNC yang sangat 
                  dibutuhkan di pabrik otomotif, alat berat, dan komponen presisi. Posisi lain yang dapat diisi meliputi Teknisi 
                  Permesinan, Juru Gambar Teknik (Drafter), Quality Control (QC) Inspector yang memastikan kualitas produk, hingga 
                  menjadi seorang Toolmaker atau membuka usaha bengkel permesinan mandiri.
                </p>
              </div>
              <div class="card-image">
                <img src="/images/career-person.jpg" alt="Peluang Karir" />
              </div>
            </div>

            <!-- Kompetensi Lulusan Card -->
            <div class="info-card competency-card">
              <div class="card-header">
                <div class="card-icon">
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L13.09 8.26L20 9L13.09 9.74L12 16L10.91 9.74L4 9L10.91 8.26L12 2Z" fill="currentColor"/>
                  </svg>
                </div>
                <h3 class="card-title">KOMPETENSI LULUSAN</h3>
              </div>
              <div class="card-content">
                <p class="card-description">
                  Lulusan memiliki kompetensi dalam pengoperasian mesin konvensional dan CNC, 
                  pemahaman gambar teknik, serta kemampuan quality control yang memadai untuk industri manufaktur.
                </p>
              </div>
            </div>

            <!-- Ruang Praktek Card -->
            <div class="info-card facilities-card">
              <h3 class="card-title">RUANG PRAKTEK</h3>
              <div class="facilities-list">
                <div class="facility-item">1. Ruang Praktek Kerja Bangku</div>
                <div class="facility-item">2. Ruang Praktek Mesin BPS</div>
                <div class="facility-item">3. Ruang Praktek CNC</div>
                <div class="facility-item">4. Ruang Praktek Gambar Manufaktur</div>
                <div class="facility-item">5. Ruang Toolman</div>
              </div>
            </div>

            <!-- Alat Praktek Card -->
            <div class="info-card equipment-card">
              <h3 class="card-title">ALAT PRAKTEK</h3>
              <div class="equipment-list">
                <div class="equipment-item">1. Mesin BPS</div>
                <div class="equipment-item">2. Mesin Bubut, Mesin Frais, Mesin LAS</div>
                <div class="equipment-item">3. Mesin gerinda datar (surface grinding)</div>
                <div class="equipment-item">4. Mesin Bubut Konvensional dan CNC</div>
                <div class="equipment-item">5. Mesin Frais Konvensional dan CNC</div>
              </div>
            </div>

            <!-- Workshop Image Card -->
            <div class="workshop-image-card">
              <img src="/images/workshop-students.jpg" alt="Ruang Praktek Siswa" />
            </div>
          </div>
        </div>
      </section>
    </main>

    <PublicFooter />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';
import { publicApi } from '../services/api';

const programs = ref([]);
const loading = ref(true);
const selectedProgram = ref(null);

const loadData = async () => {
  loading.value = true;
  try {
    const response = await publicApi.getPrograms();
    if (response.data) {
      const data = Array.isArray(response.data) ? response.data : response.data.data;
      programs.value = data.map(p => ({
        id: p.slug || p.id,
        name: p.name,
        tag: p.short_name || p.name.substring(0, 3).toUpperCase(),
        image: p.image,
        video: p.video,
        desc: p.description,
        features: extractFeatures(p.full_description),
        careerOpportunities: getCareerOpportunities(p.short_name),
        competencies: getCompetencies(p.short_name),
        facilities: getFacilities(p.short_name),
        equipment: getEquipment(p.short_name)
      }));
      
      // Set program pertama sebagai default
      if (programs.value.length > 0) {
        selectedProgram.value = programs.value[0];
      }
    }
  } catch (error) {
    console.error('Error loading programs:', error);
  } finally {
    loading.value = false;
  }
};

const extractFeatures = (desc) => {
  if (!desc) return ['Kompetensi Keahlian', 'Praktek Industri', 'Sertifikasi'];
  return ['Kompetensi Keahlian', 'Praktek Industri', 'Sertifikasi'];
};

const getCareerOpportunities = (shortName) => {
  const careers = {
    'TPM': 'Peluang karir bagi lulusan sangat luas dan tersebar di berbagai sektor industri manufaktur. Mereka dapat berkarir sebagai Operator Mesin Bubut, Frais, Gerinda, maupun Operator CNC yang sangat dibutuhkan di pabrik otomotif, alat berat, dan komponen presisi. Posisi lain yang dapat diisi meliputi Teknisi Permesinan, Juru Gambar Teknik (Drafter), Quality Control (QC) Inspector yang memastikan kualitas produk, hingga menjadi seorang Toolmaker atau membuka usaha bengkel permesinan mandiri.',
    'TKR': 'Lulusan dapat bekerja sebagai Teknisi Otomotif, Mekanik Kendaraan, Service Advisor, Parts Specialist, atau membuka bengkel otomotif sendiri. Peluang karir juga terbuka di dealer resmi, bengkel umum, dan industri otomotif.',
    'DKV': 'Lulusan dapat berkarir sebagai Graphic Designer, Web Designer, Animator, Fotografer, Videographer, Content Creator, atau membuka studio kreatif sendiri. Peluang kerja tersedia di agency periklanan, media, dan industri kreatif.',
    'APL': 'Lulusan dapat bekerja sebagai Analis Laboratorium, Quality Control Analyst, Research Assistant, atau Teknisi Laboratorium di berbagai industri seperti farmasi, makanan, kimia, dan lingkungan.',
    'MPLB': 'Lulusan dapat berkarir di bidang manajemen perkantoran, administrasi bisnis, customer service, atau membuka usaha sendiri dengan bekal kemampuan manajemen yang kuat.',
    'LOG': 'Lulusan dapat bekerja di perusahaan logistik, ekspedisi, pelabuhan, bandara, atau membuka usaha jasa logistik dengan pemahaman supply chain management yang baik.'
  };
  return careers[shortName] || 'Peluang karir yang luas di berbagai sektor industri sesuai dengan kompetensi keahlian yang dimiliki.';
};

const getCompetencies = (shortName) => {
  const competencies = {
    'TPM': 'Lulusan memiliki kompetensi dalam pengoperasian mesin konvensional dan CNC, pemahaman gambar teknik, serta kemampuan quality control yang memadai untuk industri manufaktur.',
    'TKR': 'Lulusan memiliki kompetensi dalam perawatan dan perbaikan kendaraan bermotor, diagnosa kerusakan, serta pemahaman sistem kelistrikan otomotif.',
    'DKV': 'Lulusan memiliki kompetensi dalam desain grafis, fotografi, videografi, animasi, dan pemahaman branding serta komunikasi visual.',
    'APL': 'Lulusan memiliki kompetensi dalam analisis kimia, mikrobiologi, instrumentasi laboratorium, dan manajemen kualitas produk.',
    'MPLB': 'Lulusan memiliki kompetensi dalam administrasi perkantoran, manajemen bisnis, komunikasi bisnis, dan teknologi informasi perkantoran.',
    'LOG': 'Lulusan memiliki kompetensi dalam manajemen rantai pasok, operasional gudang, distribusi, dan sistem informasi logistik.'
  };
  return competencies[shortName] || 'Lulusan memiliki kompetensi sesuai dengan bidang keahlian yang dipelajari.';
};

const getFacilities = (shortName) => {
  const facilities = {
    'TPM': [
      'Ruang Praktek Kerja Bangku',
      'Ruang Praktek Mesin BPS', 
      'Ruang Praktek CNC',
      'Ruang Praktek Gambar Manufaktur',
      'Ruang Toolman'
    ],
    'TKR': [
      'Ruang Praktek Mesin Otomotif',
      'Ruang Praktek Kelistrikan',
      'Ruang Praktek Chasis',
      'Ruang Praktek Engine',
      'Bengkel Otomotif'
    ],
    'DKV': [
      'Studio Desain Grafis',
      'Studio Fotografi',
      'Studio Video',
      'Lab Komputer Multimedia',
      'Ruang Pameran Karya'
    ],
    'APL': [
      'Laboratorium Kimia Analitik',
      'Laboratorium Mikrobiologi',
      'Laboratorium Instrumentasi',
      'Ruang Preparasi Sampel',
      'Ruang Quality Control'
    ],
    'MPLB': [
      'Ruang Praktek Perkantoran',
      'Lab Komputer Bisnis',
      'Ruang Simulasi Kantor',
      'Ruang Presentasi',
      'Ruang Arsip dan Dokumentasi'
    ],
    'LOG': [
      'Ruang Praktek Logistik',
      'Lab Sistem Informasi',
      'Ruang Simulasi Gudang',
      'Ruang Manajemen Distribusi',
      'Lab Supply Chain'
    ]
  };
  return facilities[shortName] || [
    'Ruang Praktek Utama',
    'Laboratorium Komputer',
    'Ruang Teori',
    'Perpustakaan Jurusan',
    'Ruang Konsultasi'
  ];
};

const getEquipment = (shortName) => {
  const equipment = {
    'TPM': [
      'Mesin BPS',
      'Mesin Bubut, Mesin Frais, Mesin LAS',
      'Mesin gerinda datar (surface grinding)',
      'Mesin Bubut Konvensional dan CNC',
      'Mesin Frais Konvensional dan CNC'
    ],
    'TKR': [
      'Engine Stand',
      'Diagnostic Tools',
      'Welding Equipment',
      'Hydraulic Lift',
      'Electrical Test Equipment'
    ],
    'DKV': [
      'Komputer Desain High-End',
      'Kamera DSLR Professional',
      'Video Camera 4K',
      'Lighting Studio Equipment',
      'Printer Large Format'
    ],
    'APL': [
      'Spektrofotometer',
      'Mikroskop Digital',
      'pH Meter Digital',
      'Timbangan Analitik',
      'Autoclave'
    ],
    'MPLB': [
      'Komputer Perkantoran',
      'Printer Multifungsi',
      'Mesin Fotocopy',
      'Telepon Kantor',
      'Filling Cabinet'
    ],
    'LOG': [
      'Sistem WMS (Warehouse Management)',
      'Barcode Scanner',
      'Forklift Simulator',
      'GPS Tracking System',
      'Inventory Management Software'
    ]
  };
  return equipment[shortName] || [
    'Peralatan Praktek Standar',
    'Komputer dan Software',
    'Tools dan Instruments',
    'Safety Equipment',
    'Measuring Tools'
  ];
};

const getImageUrl = (path) => {
  if (!path) return '/slide-1.jpg';
  if (path.startsWith('http') || path.startsWith('/')) return path;
  return `http://localhost:8000/storage/${path}`;
};

const getVideoUrl = (path) => {
  if (!path) return null;
  if (path.startsWith('http') || path.startsWith('/')) return path;
  return `http://localhost:8000/storage/${path}`;
};

// Computed property untuk data yang akan ditampilkan di cards
const currentProgramData = computed(() => {
  return selectedProgram.value || (programs.value.length > 0 ? programs.value[0] : null);
});

// Function untuk mengubah program yang dipilih
const selectProgram = (program) => {
  selectedProgram.value = program;
};

onMounted(loadData);
</script>

<style scoped>
.page-wrapper {
  background: #fff;
}

.content-main {
  /* Remove top margin/padding because sections are full screen */
  margin-top: 0; 
  padding: 0;
  width: 100%;
}

.jurusan-section {
  position: relative;
  height: 100vh; /* Full screen height */
  width: 100%;
  overflow: hidden;
  display: flex;
  align-items: center;
  /* Alternating Alignment */
  justify-content: flex-start; 
}

/* Alternate alignment for even sections */
.jurusan-section:nth-child(even) {
  justify-content: flex-end;
}

.section-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 0;
}

.section-media {
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

/* Content */
.section-content {
  position: relative;
  z-index: 10;
  padding: 0 5%;
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
  display: flex;
  pointer-events: none; /* Let clicks pass through container */
}

.jurusan-section:nth-child(even) .section-content {
  justify-content: flex-end;
}

.glass-panel {
  pointer-events: auto; /* Re-enable clicks on card */
  background: rgba(255, 255, 255, 0.65); /* Less transparent for readability */
  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);
  padding: 3rem;
  border-radius: 20px;
  width: 100%;
  max-width: 600px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.4);
  /* Animation entry */
  opacity: 0;
  transform: translateY(30px);
  animation: fadeInUp 0.8s forwards 0.3s;
}

@keyframes fadeInUp {
  to { opacity: 1; transform: translateY(0); }
}

.program-title {
  font-size: 3rem;
  font-weight: 900;
  color: #155d27;
  margin-bottom: 1rem;
  line-height: 1.1;
  text-transform: uppercase;
}

.program-body p {
  font-size: 1.05rem;
  line-height: 1.6;
  color: #1e293b;
  margin-bottom: 1.5rem;
}

.features-list {
  list-style: none;
  margin-bottom: 2rem;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.5rem;
}

.features-list li {
  font-size: 0.95rem;
  color: #334155;
  font-weight: 500;
}

.detail-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: #155d27;
  color: white;
  padding: 0.8rem 2rem;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 700;
  transition: all 0.3s;
}

.detail-btn:hover {
  background: #0d4f1f;
  transform: translateX(5px);
}

.arrow { transition: transform 0.3s; }
.detail-btn:hover .arrow { transform: translateX(3px); }

/* Responsive adjustments */
@media (max-width: 968px) {
  .jurusan-section {
    height: auto;
    min-height: 100vh;
    padding: 6rem 0; /* Space for navbar */
    justify-content: center !important;
  }
  
  .section-content {
    justify-content: center !important;
  }
  
  .glass-panel {
    padding: 2rem;
    margin: 0 1rem;
  }
  
  .program-title {
    font-size: 2rem;
  }
}

/* Program Cards Section */
.program-cards-section {
  padding: 4rem 0;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
}

.cards-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  grid-template-rows: auto auto auto;
  gap: 1.5rem;
  height: auto;
}

/* Info Cards Base Styling */
.info-card {
  background: white;
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.info-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.card-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: #155d27;
  margin-bottom: 1rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.card-description {
  font-size: 0.95rem;
  line-height: 1.6;
  color: #475569;
  text-align: justify;
}

/* Career Card - Spans 2 columns */
.career-card {
  grid-column: 1 / -1;
  display: flex;
  align-items: center;
  gap: 2rem;
  background: linear-gradient(135deg, #e8f5e8 0%, #f0f9ff 100%);
}

.career-card .card-content {
  flex: 2;
}

.career-card .card-image {
  flex: 1;
  text-align: center;
}

.career-card .card-image img {
  width: 100%;
  max-width: 300px;
  height: auto;
  border-radius: 12px;
  object-fit: cover;
}

/* Competency Card */
.competency-card {
  background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
}

.card-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.card-icon {
  width: 48px;
  height: 48px;
  background: #155d27;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.card-icon svg {
  width: 24px;
  height: 24px;
}

/* Facilities Card */
.facilities-card {
  background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%);
}

.facilities-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.facility-item {
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.7);
  border-radius: 8px;
  font-size: 0.9rem;
  font-weight: 500;
  color: #374151;
  border-left: 4px solid #f59e0b;
}

/* Equipment Card */
.equipment-card {
  background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
}

.equipment-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.equipment-item {
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.7);
  border-radius: 8px;
  font-size: 0.9rem;
  font-weight: 500;
  color: #374151;
  border-left: 4px solid #0ea5e9;
}

/* Workshop Image Card */
.workshop-image-card {
  grid-column: 2;
  grid-row: 2 / 4;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.workshop-image-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.workshop-image-card:hover img {
  transform: scale(1.05);
}

/* Responsive Design for Cards */
@media (max-width: 968px) {
  .cards-grid {
    grid-template-columns: 1fr;
    grid-template-rows: auto;
  }
  
  .career-card {
    grid-column: 1;
    flex-direction: column;
    text-align: center;
  }
  
  .workshop-image-card {
    grid-column: 1;
    grid-row: auto;
    height: 250px;
  }
  
  .container {
    padding: 0 1rem;
  }
  
  .info-card {
    padding: 1.5rem;
  }
  
  .card-title {
    font-size: 1.25rem;
  }
}
</style>
