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

      <!-- Profile Content Section -->
      <section class="profile-content">
        <div class="profile-grid">
          <div class="profile-text">
            <p class="profile-intro">
              Sebagai institusi pendidikan vokasi yang terkemuka di bawah naungan Yayasan Mu'allimin Mu'allimat (YASMU) Manyar Gresik, <strong>Sekolah Menengah Kejuruan Yasmu (SMK YASMU)</strong> telah memantapkan posisinya sebagai pilar utama dalam penyiapan sumber daya manusia yang andal. Sejak didirikan secara resmi pada tahun 1999, SMK YASMU mengembangkan misi mulia untuk melahirkan generasi profesional yang tidak hanya terampil secara teknis, tetapi juga memiliki daya saing tinggi untuk menjawab tantangan dunia industri.
            </p>
            <p class="profile-intro" :class="{ 'show-more': showMore }">
              Perjalanan bersejarah ini diawali dengan pembukaan dua kompetensi keahlian yang menjadi fondasi awal, yakni <strong>Teknik Pemesinan</strong> dan <strong>Teknik Otomotif</strong>. Visi ini merupakan jawaban strategis terhadap kebutuhan tenaga kerja di sektor manufaktur dan otomotif.
            </p>
            <p class="profile-intro" :class="{ 'show-more': showMore }">
              Seiring dengan perkembangan zaman dan tuntutan industri yang semakin beragam, SMK YASMU terus berinovasi melalui penambahan program keahlian baru. Pada tahun berikutnya, sekolah membuka kompetensi <strong>Desain Komunikasi Visual (DKV)</strong>, yang menjawab kebutuhan industri kreatif yang tengah berkembang pesat. Langkah progresif ini dilanjutkan dengan pembukaan <strong>Teknik Kimia Industri</strong>, <strong>Manajemen Perkantoran</strong>, hingga yang terbaru <strong>Teknik Logistik</strong>, menjadikan SMK YASMU sebagai institusi yang adaptif dan responsif terhadap dinamika pasar kerja.
            </p>
            <button class="toggle-btn" @click="showMore = !showMore">
              {{ showMore ? 'Sembunyikan' : 'Selengkapnya' }}
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="{ rotated: showMore }">
                <path d="M6 9l6 6 6-6"/>
              </svg>
            </button>
          </div>
          <div class="profile-image">
            <img src="/LOGO BULAT PNG.png" alt="SMK YASMU Building" />
          </div>
        </div>
      </section>

      <!-- Vision & Mission Section -->
      <section class="vision-mission-section">
        <h2 class="section-title">VISI DAN MISI</h2>
        
        <div class="vm-grid">
          <div class="vm-card vision-card">
            <div class="vm-header">
              <div class="vm-icon">👁️</div>
              <h3>VISI KAMI</h3>
            </div>
            <p class="vm-content">
              "Menjadi Lembaga Pendidikan Kejuruan yang Unggul, Berkarakter, Berbudaya Lingkungan, dan Berdaya Saing Global pada tahun 2030."
            </p>
          </div>

          <div class="vm-card mission-card">
            <div class="vm-header">
              <div class="vm-icon">🎯</div>
              <h3>MISI KAMI</h3>
            </div>
            <ul class="vm-list">
              <li>Menyelenggarakan pendidikan kejuruan berbasis kompetensi dan standar industri</li>
              <li>Mengembangkan karakter peserta didik yang berakhlak mulia dan mandiri</li>
              <li>Membangun kemitraan strategis dengan dunia usaha dan industri</li>
              <li>Menerapkan pembelajaran berbasis teknologi dan inovasi</li>
              <li>Menciptakan lingkungan sekolah yang kondusif dan berwawasan lingkungan</li>
            </ul>
          </div>
        </div>
      </section>
    </main>

    <PublicFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';

const router = useRouter();
const showMore = ref(false);
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

/* Profile Content */
.profile-content {
  margin-bottom: 4rem;
}

.profile-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  align-items: start;
}

.profile-text {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.profile-intro {
  color: #334155;
  font-size: 1rem;
  line-height: 1.8;
  text-align: justify;
  transition: all 0.3s;
}

.profile-intro:not(:first-child) {
  max-height: 0;
  overflow: hidden;
  opacity: 0;
  margin: 0;
}

.profile-intro.show-more {
  max-height: 1000px;
  opacity: 1;
  margin-top: 1.5rem;
}

.profile-intro strong {
  color: #155d27;
  font-weight: 700;
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
  margin-top: 0.5rem;
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

.profile-image {
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  height: 450px;
}

.profile-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s;
}

.profile-image:hover img {
  transform: scale(1.05);
}

/* Vision & Mission Section */
.vision-mission-section {
  padding: 4rem 0;
}

.section-title {
  font-size: 2rem;
  font-weight: 800;
  color: #155d27;
  text-align: center;
  margin-bottom: 3rem;
}

.vm-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 3rem;
}

.vm-card {
  background: #fff;
  border-radius: 24px;
  padding: 2.5rem;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
  transition: all 0.3s;
}

.vm-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
}

.vision-card {
  background: linear-gradient(135deg, #155d27 0%, #1e7e34 100%);
  color: #fff;
}

.mission-card {
  border: 2px solid #e5e7eb;
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

/* Responsive - Tablet */
@media (max-width: 1024px) {
  .content-main {
    padding: 2.5rem 1.5rem 5rem;
  }

  .profile-grid {
    gap: 3rem;
  }

  .profile-intro {
    font-size: 0.95rem;
  }

  .vm-card {
    padding: 2rem;
  }

  .vm-content {
    font-size: 1.1rem;
  }
}

/* Responsive - Small Tablet */
@media (max-width: 968px) {
  .profile-header {
    gap: 1rem;
  }

  .profile-title {
    font-size: 1.8rem;
  }

  .profile-grid {
    grid-template-columns: 1fr;
    gap: 2rem;
  }

  .profile-text {
    order: 2;
  }

  .profile-image {
    order: 1;
    height: 280px;
  }

  .vm-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .vm-header h3 {
    font-size: 1.3rem;
  }

  .vm-icon {
    width: 50px;
    height: 50px;
    font-size: 2rem;
  }
}

/* Responsive - Mobile */
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

  .back-btn svg {
    width: 20px;
    height: 20px;
  }

  .profile-image {
    height: 220px;
    border-radius: 16px;
  }

  .profile-intro {
    font-size: 0.9rem;
    line-height: 1.7;
    text-align: left;
  }

  .toggle-btn {
    font-size: 0.9rem;
  }

  .vision-mission-section {
    padding: 2rem 0;
  }

  .section-title {
    font-size: 1.4rem;
    margin-bottom: 2rem;
  }

  .vm-card {
    padding: 1.5rem;
    border-radius: 16px;
  }

  .vm-header {
    gap: 0.75rem;
    margin-bottom: 1rem;
  }

  .vm-icon {
    width: 45px;
    height: 45px;
    font-size: 1.5rem;
  }

  .vm-header h3 {
    font-size: 1.1rem;
  }

  .vm-content {
    font-size: 1rem;
    line-height: 1.6;
  }

  .vm-list {
    gap: 0.75rem;
  }

  .vm-list li {
    font-size: 0.9rem;
    padding-left: 1.5rem;
  }

  .vm-list li::before {
    font-size: 1rem;
  }
}

/* Responsive - Extra Small */
@media (max-width: 400px) {
  .profile-title {
    font-size: 1.1rem;
  }

  .back-btn {
    width: 36px;
    height: 36px;
  }

  .profile-image {
    height: 180px;
  }

  .section-title {
    font-size: 1.2rem;
  }

  .vm-card {
    padding: 1.25rem;
  }
}
</style>
