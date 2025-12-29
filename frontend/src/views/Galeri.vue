<template>
  <div class="page-wrapper">
    <PublicNavbar />
    <main class="content-main">
      <div class="page-header">
        <h1>GALERI FOTO & VIDEO</h1>
        <p>Dokumentasi kegiatan dan fasilitas SMK YASMU Gresik</p>
      </div>
      <section class="container">
        <div class="grid">
          <div v-for="i in 8" :key="i" class="galeri-item">
            <div class="img-placeholder">📸 Foto Kegiatan {{ i }}</div>
          </div>
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
.container { max-width: 1200px; margin: 0 auto; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; }
.galeri-item { height: 200px; background: #f1f5f9; border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-weight: 600; }
</style>
