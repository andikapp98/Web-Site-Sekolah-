import { createRouter, createWebHistory } from 'vue-router';


const routes = [
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
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../views/Dashboard.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/news/create',
    name: 'AdminNewsCreate',
    component: () => import('../views/AdminNewsCreate.vue'),
    meta: { requiresAuth: true, isAdmin: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('authToken');
  const user = localStorage.getItem('user');
  const isAdmin = user ? JSON.parse(user).is_admin : false;

  if (to.meta.requiresAuth && !isAuthenticated) {
    // Needs auth, but user is not logged in
    next('/login');
  } else if (to.meta.isAdmin && !isAdmin) {
    // Needs admin, but user is not an admin
    alert('You do not have permission to access this page.');
    next('/dashboard'); // Redirect non-admins away from admin pages
  } else {
    // All good
    next();
  }
});

export default router;
