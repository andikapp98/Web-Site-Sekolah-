<template>
  <div class="page-wrapper">
    <PublicNavbar />
    <main class="content-main">
      <div class="page-header">
        <h1>STAFF & TENAGA PENDIDIK</h1>
        <p>Para pendidik profesional dibalik kesuksesan para siswa</p>
      </div>
      <section class="container">
        <div class="grid">
          <div v-for="i in 6" :key="i" class="staff-card">
            <div class="photo">👤</div>
            <h3>Nama Guru, S.Pd</h3>
            <p>Guru Produktif</p>
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
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 2rem; }
.staff-card { text-align: center; padding: 2rem; background: #f8faf9; border-radius: 20px; }
.photo { font-size: 4rem; margin-bottom: 1rem; }
.staff-card h3 { color: #155d27; font-size: 1.1rem; }
.staff-card p { color: #64748b; font-size: 0.9rem; }
</style>
