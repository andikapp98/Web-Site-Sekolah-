<template>
  <div class="page-wrapper">
    <PublicNavbar />
    <main class="content-main">
      <div class="page-header">
        <h1>PEMBELAJARAN</h1>
        <p>Sistem pendidikan berkualitas untuk mencetak lulusan unggul</p>
      </div>
      <section class="container">
        <div id="program" class="section">
          <h2>Program Unggulan</h2>
          <p>Kami menerapkan sistem pembelajaran berbasis Tefa (Teaching Factory) dan Link and Match dengan Industri.</p>
        </div>
        <div id="kegiatan" class="section">
          <h2>Kegiatan Siswa</h2>
          <p>Berbagai ekstrakurikuler (Paskibra, OSIS, Olahraga, Seni) tersedia untuk mengembangkan bakat siswa.</p>
        </div>
      </section>
    </main>
    <PublicFooter />
  </div>
</template>

<script setup>
import PublicNavbar from '../components/PublicNavbar.vue';
import PublicFooter from '../components/PublicFooter.vue';
</script>

<style scoped>
.page-wrapper { min-height: 100vh; background: #fff; }
.content-main { margin-top: 80px; padding: 4rem 2rem; }
.page-header { text-align: center; margin-bottom: 4rem; }
.page-header h1 { font-size: 3rem; color: #155d27; font-weight: 800; }
.container { max-width: 1000px; margin: 0 auto; }
.section { margin-bottom: 4rem; padding: 2rem; background: #f8faf9; border-radius: 20px; }
.section h2 { color: #155d27; margin-bottom: 1rem; }
.section p { color: #475569; line-height: 1.7; }
</style>
