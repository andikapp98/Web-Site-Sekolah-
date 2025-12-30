<template>
  <div class="admin-layout">
    <!-- Sidebar -->
    <aside class="sidebar" :class="{ 'collapsed': sidebarCollapsed }">
      <!-- Logo Section -->
      <div class="sidebar-brand">
        <img src="/LOGO BULAT PNG.png" alt="Logo" class="brand-logo" />
        <div v-if="!sidebarCollapsed" class="brand-text">
          <span class="brand-name">SMK YASMU</span>
          <span class="brand-subtitle">Admin Panel</span>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="sidebar-nav">
        <router-link to="/admin" class="nav-item" exact-active-class="active">
          <LayoutDashboard class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Dashboard</span>
        </router-link>
        <router-link to="/admin/analytics" class="nav-item" active-class="active">
          <BarChart3 class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Analisa</span>
        </router-link>
        
        <div class="nav-divider" v-if="!sidebarCollapsed"></div>
        <span class="nav-group-title" v-if="!sidebarCollapsed">KONTEN</span>
        
        <router-link to="/admin/hero-slides" class="nav-item" active-class="active">
          <Image class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Hero Slides</span>
        </router-link>
        <router-link to="/admin/articles" class="nav-item" active-class="active">
          <Newspaper class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Artikel & Berita</span>
        </router-link>
        <router-link to="/admin/gallery" class="nav-item" active-class="active">
          <Images class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Galeri Media</span>
        </router-link>
        <router-link to="/admin/programs" class="nav-item" active-class="active">
          <GraduationCap class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Jurusan</span>
        </router-link>
        
        <div class="nav-divider" v-if="!sidebarCollapsed"></div>
        <span class="nav-group-title" v-if="!sidebarCollapsed">SEKOLAH</span>
        
        <router-link to="/admin/teachers" class="nav-item" active-class="active">
          <Users class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Guru</span>
        </router-link>
        <router-link to="/admin/former-principals" class="nav-item" active-class="active">
          <History class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Kepsek Terdahulu</span>
        </router-link>
        <router-link to="/admin/partners" class="nav-item" active-class="active">
          <Handshake class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Mitra</span>
        </router-link>
        <router-link to="/admin/testimonials" class="nav-item" active-class="active">
          <MessageSquareQuote class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Testimonial</span>
        </router-link>
        <router-link to="/admin/school-profiles" class="nav-item" active-class="active">
          <School class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Profil</span>
        </router-link>
        <router-link to="/admin/schedules" class="nav-item" active-class="active">
          <CalendarDays class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed" class="nav-label">Jadwal</span>
        </router-link>
      </nav>

      <!-- Sidebar Footer -->
      <div class="sidebar-footer">
        <a href="/" target="_blank" class="footer-link">
          <Globe class="nav-icon-lucide" :size="20" />
          <span v-if="!sidebarCollapsed">Lihat Website</span>
        </a>
        <button @click="toggleSidebar" class="collapse-btn">
          <ChevronRight v-if="sidebarCollapsed" :size="18" />
          <ChevronLeft v-else :size="18" />
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="main-container" :class="{ 'sidebar-collapsed': sidebarCollapsed }">
      <!-- Top Header -->
      <header class="top-bar">
        <div class="topbar-left">
          <button @click="toggleSidebar" class="menu-btn">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </button>
          <h1 class="page-title">{{ pageTitle }}</h1>
        </div>
        <div class="topbar-right">
          <div class="user-box">
            <div class="user-avatar">{{ userInitials }}</div>
            <span class="user-name">{{ userName }}</span>
          </div>
          <button @click="logout" class="logout-btn" title="Logout">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
          </button>
        </div>
      </header>

      <!-- Page Content -->
      <main class="content-area">
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { LayoutDashboard, BarChart3, Image, Images, Trophy, GraduationCap, Newspaper, Users, Handshake, MessageSquareQuote, School, CalendarDays, Globe, ChevronLeft, ChevronRight, LogOut, Menu, History } from 'lucide-vue-next';

const router = useRouter();
const route = useRoute();

const sidebarCollapsed = ref(false);

const userName = computed(() => {
  const user = localStorage.getItem('user');
  return user ? JSON.parse(user).name : 'Admin';
});

const userInitials = computed(() => {
  const name = userName.value;
  return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
});

const pageTitle = computed(() => {
  const titles = {
    '/admin': 'Dashboard',
    '/admin/analytics': 'Analisa',
    '/admin/hero-slides': 'Hero Slides',
    '/admin/programs': 'Jurusan',
    '/admin/partners': 'Mitra',
    '/admin/testimonials': 'Testimonial',
    '/admin/school-profiles': 'Profil Sekolah',
    '/admin/teachers': 'Guru',
    '/admin/schedules': 'Jadwal',
    '/admin/articles': 'Artikel & Berita',
    '/admin/gallery': 'Galeri Media',
    '/admin/former-principals': 'Kepsek Terdahulu',
  };
  return titles[route.path] || 'Admin';
});

const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value;
};

const logout = () => {
  localStorage.removeItem('authToken');
  localStorage.removeItem('user');
  router.push('/login');
};

const checkMobile = () => {
  if (window.innerWidth < 1024) {
    sidebarCollapsed.value = true;
  }
};

onMounted(() => {
  checkMobile();
  window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile);
});
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  background: #f4f6f8;
}

/* Sidebar */
.sidebar {
  width: 220px;
  background: #1e293b;
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100vh;
  z-index: 100;
  transition: width 0.25s ease;
}

.sidebar.collapsed {
  width: 64px;
}

/* Brand */
.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 16px;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}

.brand-logo {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  object-fit: contain;
  flex-shrink: 0;
}

.brand-text {
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.brand-name {
  font-size: 0.95rem;
  font-weight: 700;
  color: #fff;
  white-space: nowrap;
}

.brand-subtitle {
  font-size: 0.65rem;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Navigation */
.sidebar-nav {
  flex: 1;
  padding: 12px 8px;
  overflow-y: auto;
  overflow-x: hidden;
}

.nav-divider {
  height: 1px;
  background: rgba(255,255,255,0.06);
  margin: 12px 8px;
}

.nav-group-title {
  display: block;
  padding: 8px 12px 4px;
  font-size: 0.6rem;
  font-weight: 600;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  color: #94a3b8;
  text-decoration: none;
  border-radius: 8px;
  margin-bottom: 2px;
  transition: all 0.2s ease;
}

.nav-item:hover {
  background: rgba(255,255,255,0.05);
  color: #fff;
}

.nav-item.active {
  background: linear-gradient(135deg, rgba(34,197,94,0.2) 0%, rgba(34,197,94,0.1) 100%);
  color: #22c55e;
}

.nav-icon {
  font-size: 1.1rem;
  width: 24px;
  text-align: center;
  flex-shrink: 0;
}

.nav-icon-lucide {
  flex-shrink: 0;
}

.nav-label {
  font-size: 0.85rem;
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Sidebar Footer */
.sidebar-footer {
  padding: 12px 8px;
  border-top: 1px solid rgba(255,255,255,0.06);
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.footer-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 12px;
  color: #64748b;
  text-decoration: none;
  font-size: 0.8rem;
  border-radius: 6px;
  transition: all 0.2s;
}

.footer-link:hover {
  background: rgba(255,255,255,0.05);
  color: #94a3b8;
}

.collapse-btn {
  width: 100%;
  padding: 8px;
  background: rgba(255,255,255,0.03);
  border: none;
  border-radius: 6px;
  color: #64748b;
  cursor: pointer;
  font-size: 0.9rem;
  transition: all 0.2s;
}

.collapse-btn:hover {
  background: rgba(255,255,255,0.08);
  color: #94a3b8;
}

/* Main Container */
.main-container {
  flex: 1;
  margin-left: 220px;
  transition: margin-left 0.25s ease;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-container.sidebar-collapsed {
  margin-left: 64px;
}

/* Top Bar */
.top-bar {
  background: #fff;
  padding: 0 20px;
  height: 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 2px rgba(0,0,0,0.04);
  position: sticky;
  top: 0;
  z-index: 50;
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.menu-btn {
  display: none;
  padding: 8px;
  background: none;
  border: none;
  cursor: pointer;
  color: #64748b;
  border-radius: 6px;
}

.menu-btn:hover {
  background: #f1f5f9;
}

.page-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-box {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px 10px;
  background: #f8fafc;
  border-radius: 8px;
}

.user-avatar {
  width: 32px;
  height: 32px;
  border-radius: 6px;
  background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
}

.user-name {
  font-size: 0.85rem;
  font-weight: 500;
  color: #1e293b;
}

.logout-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  background: #fee2e2;
  border: none;
  border-radius: 8px;
  color: #dc2626;
  cursor: pointer;
  transition: all 0.2s;
}

.logout-btn:hover {
  background: #dc2626;
  color: #fff;
}

/* Content Area */
.content-area {
  flex: 1;
  padding: 20px;
}

/* Responsive */
@media (max-width: 1024px) {
  .sidebar {
    transform: translateX(-100%);
  }
  
  .sidebar:not(.collapsed) {
    transform: translateX(0);
  }
  
  .main-container {
    margin-left: 0 !important;
  }
  
  .menu-btn {
    display: flex;
  }
  
  .user-name {
    display: none;
  }
}

@media (max-width: 640px) {
  .content-area {
    padding: 12px;
  }
  
  .top-bar {
    padding: 0 12px;
  }
}
</style>
