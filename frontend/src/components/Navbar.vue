<template>
  <header class="bg-white shadow-sm fixed top-0 w-full z-50">
    <nav class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">
      <!-- Logo + Nama Sekolah -->
      <div class="flex items-center space-x-3">
        <img src="/LOGO BULAT PNG.png" alt="Logo SMK YASMU GRESIK" class="w-10 h-10 object-contain" />
        <span class="text-green-800 font-extrabold text-lg">SMK YASMU GRESIK</span>
      </div>

      <!-- Menu Navigasi Desktop -->
      <ul class="hidden md:flex space-x-6 text-gray-800 font-medium">
        <li><router-link to="/" class="hover:text-green-700 transition">Home</router-link></li>

        <li class="relative group">
          <a href="#" class="hover:text-green-700 transition">Tentang Kami</a>
          <!-- Dropdown -->
          <ul class="absolute hidden group-hover:block bg-white shadow-md rounded-md mt-2 py-2 w-40 text-sm">
            <li><a href="#" class="block px-4 py-2 hover:bg-green-50 hover:text-green-700">Profil Sekolah</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-green-50 hover:text-green-700">Visi & Misi</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-green-50 hover:text-green-700">Struktur Organisasi</a></li>
          </ul>
        </li>

        <li class="relative group">
          <a href="#" class="hover:text-green-700 transition">Jurusan</a>
          <ul class="absolute hidden group-hover:block bg-white shadow-md rounded-md mt-2 py-2 w-48 text-sm">
            <li><a href="#" class="block px-4 py-2 hover:bg-green-50 hover:text-green-700">Teknik Logistik</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-green-50 hover:text-green-700">Akuntansi & Keuangan</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-green-50 hover:text-green-700">Rekayasa Perangkat Lunak</a></li>
          </ul>
        </li>

        <li class="relative group">
          <a href="#" class="hover:text-green-700 transition">Pembelajaran</a>
          <ul class="absolute hidden group-hover:block bg-white shadow-md rounded-md mt-2 py-2 w-48 text-sm">
            <li><a href="#" class="block px-4 py-2 hover:bg-green-50 hover:text-green-700">Program Keahlian</a></li>
            <li><a href="#" class="block px-4 py-2 hover:bg-green-50 hover:text-green-700">Kegiatan Siswa</a></li>
          </ul>
        </li>

        <li><a href="#" class="hover:text-green-700 transition">Alumni</a></li>

        <!-- Admin Menu (jika authenticated dan admin) -->
        <li v-if="isAdmin">
          <router-link to="/admin" class="hover:text-green-700 transition">Admin Panel</router-link>
        </li>
      </ul>

      <!-- Tombol kanan -->
      <div class="hidden md:flex items-center space-x-3">
        <!-- Icon search -->
        <button class="border border-green-700 p-2 rounded-full hover:bg-green-700 hover:text-white transition group">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-700 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
          </svg>
        </button>

        <!-- User info atau Hubungi Kami -->
        <div v-if="isAuthenticated" class="flex items-center space-x-2">
          <span class="text-gray-700 text-sm">{{ userName }}</span>
          <button @click="handleLogout" class="bg-red-600 text-white px-4 py-2 rounded-full font-semibold hover:bg-red-700 transition">
            Logout
          </button>
        </div>
        <a v-else href="#" class="bg-green-700 text-white px-5 py-2 rounded-full font-semibold hover:bg-green-800 transition">
          Hubungi Kami
        </a>
      </div>

      <!-- Menu mobile toggle -->
      <button class="md:hidden text-green-700 focus:outline-none" @click="toggleMenu">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </nav>

    <!-- Menu mobile dropdown -->
    <div v-if="isOpen" class="md:hidden bg-white border-t border-gray-200">
      <ul class="flex flex-col text-gray-800 font-medium px-4 py-2 space-y-2">
        <li><router-link to="/" class="block py-2" @click="toggleMenu">Home</router-link></li>
        <li><a href="#" class="block py-2">Tentang Kami</a></li>
        <li><a href="#" class="block py-2">Jurusan</a></li>
        <li><a href="#" class="block py-2">Pembelajaran</a></li>
        <li><a href="#" class="block py-2">Alumni</a></li>
        <li v-if="isAdmin"><router-link to="/admin" class="block py-2 text-green-700" @click="toggleMenu">Admin Panel</router-link></li>
        <li v-if="isAuthenticated" class="border-t pt-2">
          <span class="block py-2 text-sm text-gray-600">{{ userName }}</span>
          <button @click="handleLogout" class="block w-full text-left py-2 text-red-600 font-semibold">Logout</button>
        </li>
        <li v-else><a href="#" class="block py-2 text-green-700 font-semibold">Hubungi Kami</a></li>
      </ul>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { logout, isAuthenticated as checkAuth, getUser } from '../services/auth';

const router = useRouter();
const isOpen = ref(false);
const isAuthenticated = ref(checkAuth());
const user = ref(getUser());
const isAdmin = computed(() => user.value ? user.value.is_admin : false);

const userName = computed(() => user.value ? user.value.name : 'Guest');

const toggleMenu = () => {
  isOpen.value = !isOpen.value;
};

const handleLogout = async () => {
  await logout();
  isAuthenticated.value = false;
  user.value = null;
  isOpen.value = false;
  router.push('/login');
};

const updateAuthState = () => {
  isAuthenticated.value = checkAuth();
  user.value = getUser();
};

onMounted(() => {
  window.addEventListener('storage', updateAuthState);
});

onUnmounted(() => {
  window.removeEventListener('storage', updateAuthState);
});
</script>

<style scoped>
/* Styling untuk dropdown hover */
.group:hover .group-hover\:block {
  display: block;
}

/* Smooth transition untuk hover effects */
a, button {
  transition: all 0.3s ease;
}

/* Fix untuk z-index dropdown */
.relative {
  position: relative;
}

.absolute {
  position: absolute;
}
</style>