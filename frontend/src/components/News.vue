<template>
  <div class="news-section my-5">
    <h2 class="text-center mb-4">Berita Sekolah</h2>
    <div class="row">
      <div class="col-md-4 mb-4" v-for="item in news" :key="item.id">
        <div class="card h-100">
          <img :src="item.imageUrl" class="card-img-top" :alt="item.title">
          <div class="card-body">
            <h5 class="card-title">{{ item.title }}</h5>
            <p class="card-text">{{ item.summary }}</p>
            <a href="#" class="btn btn-primary">Baca Selengkapnya</a>
          </div>
          <div class="card-footer">
            <small class="text-muted">{{ item.date }}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const news = ref([
  {
    id: 1,
    title: 'Penerimaan Peserta Didik Baru 2026/2027',
    summary: 'SMK Yasmu Gresik telah membuka pendaftaran untuk peserta didik baru tahun ajaran 2026/2027. Segera daftarkan diri anda!',
    date: '22 Oktober 2025',
    imageUrl: 'https://via.placeholder.com/400x250.png?text=PPDB+2026/2027'
  },
  {
    id: 2,
    title: 'Juara 1 Lomba Kompetensi Siswa Tingkat Nasional',
    summary: 'Siswa kami berhasil meraih juara 1 dalam Lomba Kompetensi Siswa (LKS) tingkat nasional bidang Teknik Komputer dan Jaringan.',
    date: '15 Oktober 2025',
    imageUrl: 'https://via.placeholder.com/400x250.png?text=Juara+LKS'
  },
  {
    id: 3,
    title: 'Kegiatan Class Meeting Semester Ganjil',
    summary: 'Class meeting semester ini akan diisi dengan berbagai lomba antar kelas yang menarik dan edukatif. Jangan sampai ketinggalan!',
    date: '10 Oktober 2025',
    imageUrl: 'https://via.placeholder.com/400x250.png?text=Class+Meeting'
  }
]);
</script>

<style scoped>
.news-section {
  padding: 2rem 0;
}
.card {
  transition: transform 0.2s;
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}
.card-title {
  color: var(--primary-color);
}
</style>
