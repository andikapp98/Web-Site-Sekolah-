<template>
  <div class="page-wrapper">
    <PublicNavbar />
    
    <main class="content-main">
      <div class="page-header">
        <h1>PROGRAM KEAHLIAN</h1>
        <p>Pilih jurusan yang sesuai dengan minat dan bakatmu</p>
      </div>

      <div class="jurusan-grid">
        <div v-for="(prog, i) in programs" :key="i" :id="prog.id" class="jurusan-card">
          <div class="jurusan-image">
            <img :src="prog.image || '/slide-1.jpg'" :alt="prog.name" />
            <div class="jurusan-badge">{{ prog.tag }}</div>
          </div>
          <div class="jurusan-content">
            <h2>{{ prog.name }}</h2>
            <p>{{ prog.desc }}</p>
            <ul class="jurusan-features">
              <li v-for="(feat, fi) in prog.features" :key="fi">✅ {{ feat }}</li>
            </ul>
            <a href="#kontak" class="daftar-btn">Daftar Sekarang</a>
          </div>
        </div>
      </div>
    </main>

    <PublicFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';

const programs = ref([
  { id: 'tpm', name: 'TEKNIK PEMESINAN', tag: 'TPM', image: '/slide-1.jpg', desc: 'Mempersiapkan tenaga ahli dalam pengoperasian mesin industri, CAD/CAM, dan perawatan mesin.', features: ['CNC Machining', 'Bubut & Frais', 'CAD Designing'] },
  { id: 'tkr', name: 'TEKNIK KENDARAAN RINGAN', tag: 'TKR', image: '/slide-2.jpg', desc: 'Mempelajari sistem otomotif modern, perbaikan mesin, dan manajemen bengkel.', features: ['Engine Overhaul', 'Chassis Tuning', 'Auto Electrical'] },
  { id: 'dkv', name: 'DESAIN KOMUNIKASI VISUAL', tag: 'DKV', image: '/slide-3.jpg', desc: 'Mengembangkan kreativitas dalam desain grafis, fotografi, videografi, dan branding.', features: ['Graphic Design', 'Video Editing', 'Photography'] },
  { id: 'tapl', name: 'ANALISIS PENGUJIAN LAB', tag: 'APL', image: '/slide-1.jpg', desc: 'Fokus pada pengujian laboratorium kimia, analisis produk industri, dan kontrol kualitas.', features: ['Chemical Analysis', 'Lab Instruments', 'QC Inspection'] },
  { id: 'mpk', name: 'MANAJEMEN PERKANTORAN', tag: 'MP', image: '/slide-2.jpg', desc: 'Keahlian dalam administrasi bisnis, manajemen kearsipan, dan komunikasi perkantoran.', features: ['Secretarial Skills', 'E-Filling', 'Business Comms'] },
  { id: 'logistik', name: 'TEKNIK LOGISTIK', tag: 'LOG', image: '/slide-3.jpg', desc: 'Manajemen rantai pasok, pergudangan, distribusi, dan manajemen transportasi.', features: ['Warehouse Mgmt', 'Supply Chain', 'Inventory Control'] }
]);
</script>

<style scoped>
.page-wrapper { min-height: 100vh; background: #fff; }
.content-main { margin-top: 80px; padding: 4rem 2rem; max-width: 1400px; margin-left: auto; margin-right: auto; }

.page-header { text-align: center; margin-bottom: 5rem; }
.page-header h1 { font-size: 3.5rem; color: #155d27; font-weight: 900; }
.page-header p { color: #64748b; font-size: 1.2rem; }

.jurusan-grid { display: flex; flex-direction: column; gap: 6rem; }

.jurusan-card { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; border-radius: 40px; padding: 2rem; transition: background 0.3s; }
.jurusan-card:nth-child(even) { direction: rtl; }
.jurusan-card:nth-child(even) .jurusan-content { direction: ltr; }
.jurusan-card:hover { background: #f8faf9; }

.jurusan-image { position: relative; border-radius: 30px; overflow: hidden; height: 450px; box-shadow: 0 20px 50px rgba(0,0,0,0.1); }
.jurusan-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.jurusan-card:hover img { transform: scale(1.05); }
.jurusan-badge { position: absolute; top: 2rem; left: 2rem; background: #155d27; color: #fff; padding: 0.5rem 1.5rem; border-radius: 50px; font-weight: 700; font-size: 0.9rem; }

.jurusan-content h2 { font-size: 2.2rem; color: #155d27; margin-bottom: 1.5rem; font-weight: 800; }
.jurusan-content p { font-size: 1.1rem; color: #475569; line-height: 1.8; margin-bottom: 2rem; }

.jurusan-features { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 3rem; }
.jurusan-features li { color: #334155; font-weight: 500; font-size: 1rem; }

.daftar-btn { background: #155d27; color: #fff; padding: 1rem 2.5rem; border-radius: 50px; text-decoration: none; font-weight: 700; transition: all 0.3s; display: inline-block; }
.daftar-btn:hover { background: #0d4f1f; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(21, 93, 39, 0.2); }

@media (max-width: 968px) {
  .jurusan-card { grid-template-columns: 1fr; gap: 2rem; text-align: center; }
  .jurusan-card:nth-child(even) { direction: ltr; }
  .jurusan-features { grid-template-columns: 1fr; justify-items: center; }
  .jurusan-image { height: 300px; }
  .page-header h1 { font-size: 2.5rem; }
}
</style>
