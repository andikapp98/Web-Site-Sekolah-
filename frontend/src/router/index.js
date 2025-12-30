import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  // ============ PUBLIC ROUTES ============
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue'),
  },
  {
    path: '/tentang',
    name: 'Tentang',
    component: () => import('../views/Tentang.vue'),
  },
  {
    path: '/profil',
    name: 'Profil',
    component: () => import('../views/Profil.vue'),
  },
  {
    path: '/staff',
    name: 'Staff',
    component: () => import('../views/Staff.vue'),
  },
  {
    path: '/sejarah',
    name: 'Sejarah',
    component: () => import('../views/Sejarah.vue'),
  },
  {
    path: '/berita',
    name: 'Berita',
    component: () => import('../views/Berita.vue'),
  },
  {
    path: '/galeri',
    name: 'Galeri',
    component: () => import('../views/Galeri.vue'),
  },
  {
    path: '/jurusan',
    name: 'Jurusan',
    component: () => import('../views/Jurusan.vue'),
  },
  {
    path: '/jurusan/:slug',
    name: 'JurusanDetail',
    component: () => import('../views/JurusanDetail.vue'),
  },
  {
    path: '/pembelajaran',
    name: 'Pembelajaran',
    component: () => import('../views/Pembelajaran.vue'),
  },
  {
    path: '/alumni',
    name: 'Alumni',
    component: () => import('../views/Alumni.vue'),
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/LoginView.vue'),
  },
  
  // ============ ADMIN ROUTES ============
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: () => import('../views/admin/AdminDashboard.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/analytics',
    name: 'AdminAnalytics',
    component: () => import('../views/admin/AdminAnalytics.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/hero-slides',
    name: 'AdminHeroSlides',
    component: () => import('../views/admin/AdminHeroSlides.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/achievements',
    redirect: '/admin/articles',
  },
  {
    path: '/admin/programs',
    name: 'AdminPrograms',
    component: () => import('../views/admin/AdminPrograms.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/partners',
    name: 'AdminPartners',
    component: () => import('../views/admin/AdminPartners.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/testimonials',
    name: 'AdminTestimonials',
    component: () => import('../views/admin/AdminTestimonials.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/school-profiles',
    name: 'AdminSchoolProfiles',
    component: () => import('../views/admin/AdminSchoolProfiles.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/teachers',
    name: 'AdminTeachers',
    component: () => import('../views/admin/AdminTeachers.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/schedules',
    name: 'AdminSchedules',
    component: () => import('../views/admin/AdminSchedules.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/former-principals',
    name: 'AdminFormerPrincipals',
    component: () => import('../views/admin/AdminFormerPrincipals.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/articles',
    name: 'AdminArticles',
    component: () => import('../views/admin/AdminArticles.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  {
    path: '/admin/gallery',
    name: 'AdminGallery',
    component: () => import('../views/admin/AdminGallery.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
  
  // Legacy routes (redirect to new admin)
  {
    path: '/dashboard',
    redirect: '/admin',
  },
  {
    path: '/admin/news/create',
    redirect: '/admin/articles',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('authToken');
  const userStr = localStorage.getItem('user');
  const isAuthenticated = !!token;
  const user = userStr ? JSON.parse(userStr) : null;
  const isAdmin = user?.is_admin === true || user?.is_admin === 1;

  if (to.meta.requiresAuth && !isAuthenticated) {
    // Needs auth, but user is not logged in
    next('/login');
  } else if (to.meta.isAdmin && !isAdmin) {
    // Needs admin, but user is not an admin
    alert('Anda tidak memiliki akses ke halaman ini.');
    next('/');
  } else {
    // All good
    next();
  }
});

export default router;
