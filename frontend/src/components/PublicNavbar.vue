<template>
  <header class="header">
    <nav class="navbar">
      <div class="nav-container">
        <router-link to="/" class="logo">
          <img src="/LOGO BULAT PNG.png" alt="Logo SMK Yasmu Gresik" class="logo-img">
          <span class="logo-text">SMKS YASMU GRESIK</span>
        </router-link>
        <ul class="nav-menu">
          <li><router-link to="/" active-class="nav-active">Home</router-link></li>
          <li class="nav-dropdown">
            <router-link to="/tentang" active-class="nav-active">Tentang Kami <span class="arrow">▼</span></router-link>
            <div class="dropdown-menu">
              <router-link to="/profil">Profil & Sejarah</router-link>
              <router-link to="/staff">Staff & Tenaga Pendidik</router-link>
              <router-link to="/berita">Berita Terkini</router-link>
              <router-link to="/galeri">Galeri Foto & Video</router-link>
            </div>
          </li>
          <li class="nav-dropdown">
            <router-link to="/jurusan" active-class="nav-active">Jurusan <span class="arrow">▼</span></router-link>
            <div class="dropdown-menu">
              <router-link to="/jurusan#tpm">Teknik Pemesinan</router-link>
              <router-link to="/jurusan#tkr">Teknik Kendaraan Ringan</router-link>
              <router-link to="/jurusan#dkv">Desain Komunikasi Visual</router-link>
              <router-link to="/jurusan#tapl">Analisis Pengujian Lab</router-link>
              <router-link to="/jurusan#mpk">Manajemen Perkantoran</router-link>
              <router-link to="/jurusan#logistik">Teknik Logistik</router-link>
            </div>
          </li>
          <li class="nav-dropdown">
            <router-link to="/pembelajaran" active-class="nav-active">Pembelajaran <span class="arrow">▼</span></router-link>
            <div class="dropdown-menu">
              <router-link to="/pembelajaran#program">Program Keahlian</router-link>
              <router-link to="/pembelajaran#kegiatan">Kegiatan Siswa</router-link>
            </div>
          </li>
          <li><router-link to="/alumni" active-class="nav-active">Alumni</router-link></li>
        </ul>
        <div class="nav-actions">
          <div class="search-wrapper">
            <button class="search-btn" @click="toggleSearch">
              <Search v-if="!searchOpen" :size="18" />
              <X v-else :size="18" />
            </button>
            <div v-if="searchOpen" class="search-form">
              <input type="text" v-model="searchQuery" placeholder="Cari..." class="search-input" @keyup.enter="doSearch">
              <button @click="doSearch" class="search-submit">Cari</button>
            </div>
          </div>
          <router-link to="/#kontak" class="contact-btn">Hubungi Kami</router-link>
          <button class="mobile-menu-btn" @click="toggleMobileMenu">
            <Menu :size="24" />
          </button>
        </div>
      </div>
      <!-- Mobile Menu -->
      <div v-if="mobileMenuOpen" class="mobile-menu">
        <ul>
          <li><router-link to="/" @click="toggleMobileMenu">Home</router-link></li>
          <li><router-link to="/tentang" @click="toggleMobileMenu">Tentang Kami</router-link></li>
          <li><router-link to="/jurusan" @click="toggleMobileMenu">Jurusan</router-link></li>
          <li><router-link to="/pembelajaran" @click="toggleMobileMenu">Pembelajaran</router-link></li>
          <li><router-link to="/alumni" @click="toggleMobileMenu">Alumni</router-link></li>
          <li><router-link to="/#kontak" @click="toggleMobileMenu" class="contact-link">Hubungi Kami</router-link></li>
        </ul>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref } from 'vue';
import { Search, X, Menu } from 'lucide-vue-next';

const mobileMenuOpen = ref(false);
const searchOpen = ref(false);
const searchQuery = ref('');

const toggleMobileMenu = () => { mobileMenuOpen.value = !mobileMenuOpen.value; };
const toggleSearch = () => { searchOpen.value = !searchOpen.value; };
const doSearch = () => { 
  if (searchQuery.value.trim()) {
    alert('Mencari: ' + searchQuery.value);
  }
};
</script>

<style scoped>
.header { position: fixed; top: 0; left: 0; width: 100%; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); z-index: 1000; }
.navbar { padding: 1rem 0; }
.nav-container { width: 100%; padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; }
.logo-img { width: 45px; height: 45px; }
.logo { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
.logo-text { font-size: 1.1rem; font-weight: 700; color: #155d27; text-transform: uppercase; letter-spacing: 0.5px; }
.nav-menu { display: flex; list-style: none; gap: 1.5rem; }
.nav-menu > li > a { text-decoration: none; color: #2c3e50; font-weight: 500; font-size: 0.95rem; padding: 0.6rem 1rem; border-radius: 4px; display: flex; align-items: center; gap: 0.3rem; transition: all 0.3s; }
.nav-menu > li > a:hover { color: #155d27; background: #f0f9f4; }
.nav-menu > li > a.nav-active { color: #155d27; font-weight: 700; background: #f0f9f4; }
.arrow { font-size: 0.6rem; transition: transform 0.3s; }
.nav-dropdown:hover .arrow { transform: rotate(180deg); }
.nav-dropdown { position: relative; }
.dropdown-menu { 
  position: absolute; 
  top: calc(100% + 10px); 
  left: 0; 
  background: #fff; 
  border-radius: 12px; 
  padding: 0.75rem 0; 
  min-width: 220px; 
  opacity: 0; 
  visibility: hidden; 
  transform: translateY(-10px); 
  transition: all 0.3s ease;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  border: 1px solid #e5e7eb;
  z-index: 1001;
}
.dropdown-menu::before {
  content: '';
  position: absolute;
  top: -8px;
  left: 30px;
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;
  border-bottom: 8px solid #fff;
  z-index: 1002;
}
.nav-dropdown:hover .dropdown-menu { 
  opacity: 1 !important; 
  visibility: visible !important; 
  transform: translateY(0) !important; 
  display: block !important;
}
.dropdown-menu a { 
  display: block; 
  padding: 0.75rem 1.25rem; 
  color: #374151; 
  text-decoration: none; 
  font-size: 0.95rem; 
  font-weight: 500;
  transition: all 0.2s;
  border-left: 3px solid transparent;
}
.dropdown-menu a:hover { 
  background: #f0fdf4; 
  color: #155d27;
  border-left-color: #155d27;
}
.nav-actions { display: flex; align-items: center; gap: 0.75rem; }
.search-wrapper { position: relative; }
.search-btn { background: #155d27; border: none; color: #fff; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s; }
.search-btn:hover { background: #0d4f1f; transform: scale(1.05); }
.search-form { position: absolute; top: calc(100% + 15px); right: 0; background: #fff; padding: 1rem; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 0.5rem; z-index: 1000; }
.search-input { padding: 0.6rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.9rem; width: 220px; outline: none; transition: all 0.3s; }
.search-input:focus { border-color: #155d27; box-shadow: 0 0 0 3px rgba(21, 93, 39, 0.1); }
.search-submit { background: #155d27; color: #fff; border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: all 0.3s; }
.search-submit:hover { background: #0d4f1f; }
.contact-btn { background: #155d27; color: #fff; padding: 0.6rem 1.2rem; border-radius: 24px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.3s; }
.contact-btn:hover { background: #0d4f1f; }

.mobile-menu-btn { display: none; background: none; border: none; color: #155d27; cursor: pointer; }
.mobile-menu { display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

@media (max-width: 768px) {
  .nav-menu { display: none; }
  .mobile-menu-btn { display: block; }
  .mobile-menu { display: block; }
  .contact-btn { display: none; }
}
</style>
