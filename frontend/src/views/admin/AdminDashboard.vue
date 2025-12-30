<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- Compact Welcome Banner -->
      <div class="welcome-banner">
        <div class="welcome-left">
          <h1>👋 Halo, {{ userName }}!</h1>
          <p>{{ currentDate }}</p>
        </div>
        <router-link to="/" target="_blank" class="view-site-btn">
          <Globe :size="16" /> Lihat Website
        </router-link>
      </div>

      <!-- Stats Cards (Clickable) -->
      <div class="stats-grid">
        <router-link 
          v-for="stat in stats" 
          :key="stat.title" 
          :to="stat.link"
          class="stat-card"
        >
          <div class="stat-icon" :style="{ background: stat.gradient }">
            {{ stat.icon }}
          </div>
          <div class="stat-info">
            <span class="stat-value">{{ stat.value }}</span>
            <span class="stat-label">{{ stat.title }}</span>
          </div>
          <span class="stat-arrow">→</span>
        </router-link>
      </div>

      <!-- Quick Actions (Actual Actions) -->
      <div class="section-card">
        <div class="section-header">
          <h2><Zap :size="20" class="inline-icon" /> Aksi Cepat</h2>
        </div>
        <div class="actions-grid">
          <router-link to="/admin/articles" class="action-btn primary">
            <Pencil :size="20" class="action-icon-lucide" />
            <span>Tambah Artikel</span>
          </router-link>
          <router-link to="/admin/achievements" class="action-btn">
            <Trophy :size="20" class="action-icon-lucide" />
            <span>Tambah Prestasi</span>
          </router-link>
          <router-link to="/admin/teachers" class="action-btn">
            <Users :size="20" class="action-icon-lucide" />
            <span>Tambah Guru</span>
          </router-link>
          <router-link to="/admin/hero-slides" class="action-btn">
            <Image :size="20" class="action-icon-lucide" />
            <span>Ubah Slider</span>
          </router-link>
        </div>
      </div>

      <!-- Content Grid -->
      <div class="content-grid">
        <!-- Recent Achievements -->
        <div class="content-card">
          <div class="card-header">
            <h3>🏆 Prestasi Terbaru</h3>
            <router-link to="/admin/achievements" class="view-link">Lihat Semua →</router-link>
          </div>
          <div class="card-body">
            <div v-if="recentAchievements.length > 0" class="list-items">
              <div class="list-item" v-for="item in recentAchievements" :key="item.id">
                <span class="item-badge">{{ item.category || 'PRESTASI' }}</span>
                <span class="item-title">{{ item.title }}</span>
              </div>
            </div>
            <div v-else class="empty-state">
              <span class="empty-icon">📭</span>
              <span class="empty-text">Belum ada data prestasi</span>
              <router-link to="/admin/achievements" class="empty-action">
                + Tambah Prestasi Pertama
              </router-link>
            </div>
          </div>
        </div>

        <!-- Recent Teachers -->
        <div class="content-card">
          <div class="card-header">
            <h3>👨‍🏫 Guru Terbaru</h3>
            <router-link to="/admin/teachers" class="view-link">Lihat Semua →</router-link>
          </div>
          <div class="card-body">
            <div v-if="recentTeachers.length > 0" class="list-items">
              <div class="list-item" v-for="teacher in recentTeachers" :key="teacher.id">
                <div class="avatar">{{ getInitials(teacher.name) }}</div>
                <div class="item-info">
                  <span class="item-title">{{ teacher.name }}</span>
                  <span class="item-sub">{{ teacher.position || 'Guru' }}</span>
                </div>
              </div>
            </div>
            <div v-else class="empty-state">
              <span class="empty-icon">👥</span>
              <span class="empty-text">Belum ada data guru</span>
              <p class="empty-tip">Mulai dengan menambahkan data guru terlebih dahulu</p>
              <router-link to="/admin/teachers" class="empty-action">
                + Tambah Guru Pertama
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Zap, Pencil, Trophy, Users, Image, Globe } from 'lucide-vue-next';
import AdminLayout from '../../components/AdminLayout.vue';
import { adminApi } from '../../services/api';

const userName = computed(() => {
  const user = localStorage.getItem('user');
  if (user) {
    const parsed = JSON.parse(user);
    // Get first name only
    return parsed.name.split(' ')[0];
  }
  return 'Admin';
});

const currentDate = computed(() => {
  const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
  return new Date().toLocaleDateString('id-ID', options);
});

// Stats with consistent naming and links
const stats = ref([
  { icon: '🏆', title: 'Prestasi', value: '0', gradient: 'linear-gradient(135deg, #667eea, #764ba2)', link: '/admin/achievements' },
  { icon: '🎓', title: 'Jurusan', value: '0', gradient: 'linear-gradient(135deg, #f093fb, #f5576c)', link: '/admin/programs' },
  { icon: '👨‍🏫', title: 'Guru', value: '0', gradient: 'linear-gradient(135deg, #4facfe, #00f2fe)', link: '/admin/teachers' },
  { icon: '🤝', title: 'Mitra', value: '0', gradient: 'linear-gradient(135deg, #43e97b, #38f9d7)', link: '/admin/partners' },
  { icon: '💬', title: 'Testimonial', value: '0', gradient: 'linear-gradient(135deg, #fa709a, #fee140)', link: '/admin/testimonials' },
  { icon: '📰', title: 'Artikel', value: '0', gradient: 'linear-gradient(135deg, #a18cd1, #fbc2eb)', link: '/admin/articles' },
]);

const recentAchievements = ref([]);
const recentTeachers = ref([]);

const getInitials = (name) => {
  if (!name) return 'U';
  return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
};

const loadStats = async () => {
  try {
    const [achievements, programs, teachers, partners, testimonials, articles] = await Promise.all([
      adminApi.achievements.list({ per_page: 4 }),
      adminApi.programs.list({ per_page: 100 }),
      adminApi.teachers.list({ per_page: 4 }),
      adminApi.partners.list({ per_page: 100 }),
      adminApi.testimonials.list({ per_page: 100 }),
      adminApi.articles.list({ per_page: 100 }),
    ]);

    stats.value[0].value = achievements.data.total || achievements.data.data?.length || 0;
    stats.value[1].value = programs.data.total || programs.data.data?.length || 0;
    stats.value[2].value = teachers.data.total || teachers.data.data?.length || 0;
    stats.value[3].value = partners.data.total || partners.data.data?.length || 0;
    stats.value[4].value = testimonials.data.total || testimonials.data.data?.length || 0;
    stats.value[5].value = articles.data.total || articles.data.data?.length || 0;

    recentAchievements.value = achievements.data.data?.slice(0, 3) || [];
    recentTeachers.value = teachers.data.data?.slice(0, 3) || [];
  } catch (error) {
    console.error('Error loading stats:', error);
  }
};

onMounted(() => {
  loadStats();
});
</script>

<style scoped>
.dashboard {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Welcome Banner - Compact */
.welcome-banner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
  padding: 16px 24px;
  border-radius: 12px;
  color: #fff;
}

.welcome-left h1 {
  font-size: 1.25rem;
  font-weight: 700;
  margin: 0 0 2px 0;
  color: #fff;
}

.welcome-left p {
  font-size: 0.85rem;
  margin: 0;
  opacity: 0.9;
  color: #fff;
}

.view-site-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: rgba(255,255,255,0.2);
  border-radius: 8px;
  color: #fff;
  font-size: 0.85rem;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s;
}

.view-site-btn:hover {
  background: rgba(255,255,255,0.3);
}

/* Stats Grid - Clickable Cards */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 12px;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 12px;
  background: #fff;
  padding: 16px;
  border-radius: 12px;
  text-decoration: none;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  border: 2px solid transparent;
  transition: all 0.2s ease;
  cursor: pointer;
}

.stat-card:hover {
  border-color: #22c55e;
  box-shadow: 0 4px 16px rgba(34,197,94,0.15);
  transform: translateY(-2px);
}

.stat-card:hover .stat-arrow {
  opacity: 1;
  transform: translateX(0);
}

.stat-icon {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  flex-shrink: 0;
}

.stat-info {
  flex: 1;
  min-width: 0;
}

.stat-value {
  display: block;
  font-size: 1.5rem;
  font-weight: 800;
  color: #1e293b;
  line-height: 1.1;
}

.stat-label {
  display: block;
  font-size: 0.8rem;
  color: #64748b;
  margin-top: 2px;
}

.stat-arrow {
  font-size: 1rem;
  color: #22c55e;
  opacity: 0;
  transform: translateX(-4px);
  transition: all 0.2s;
}

/* Section Card */
.section-card {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.section-header {
  padding: 16px 20px;
  border-bottom: 1px solid #f1f5f9;
}

.section-header h2 {
  font-size: 1rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}

/* Actions Grid */
.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 12px;
  padding: 16px 20px;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background: #f8fafc;
  border-radius: 10px;
  text-decoration: none;
  color: #475569;
  font-size: 0.85rem;
  font-weight: 500;
  transition: all 0.2s;
  border: 1px solid #e2e8f0;
}

.action-btn:hover {
  background: #22c55e;
  color: #fff;
  border-color: #22c55e;
}

.action-btn.primary {
  background: #22c55e;
  color: #fff;
  border-color: #22c55e;
}

.action-btn.primary:hover {
  background: #16a34a;
  border-color: #16a34a;
}

.action-icon {
  font-size: 1.1rem;
}

.action-icon-lucide {
  flex-shrink: 0;
}

.inline-icon {
  vertical-align: middle;
  margin-right: 4px;
}

/* Content Grid */
.content-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.content-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  overflow: hidden;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 20px;
  border-bottom: 1px solid #f1f5f9;
}

.card-header h3 {
  font-size: 0.95rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}

.view-link {
  font-size: 0.8rem;
  color: #22c55e;
  text-decoration: none;
  font-weight: 500;
}

.view-link:hover {
  text-decoration: underline;
}

.card-body {
  padding: 16px 20px;
}

/* List Items */
.list-items {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.list-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  background: #f8fafc;
  border-radius: 8px;
}

.item-badge {
  background: linear-gradient(135deg, #22c55e, #16a34a);
  color: #fff;
  padding: 3px 8px;
  border-radius: 4px;
  font-size: 0.65rem;
  font-weight: 700;
  text-transform: uppercase;
  white-space: nowrap;
}

.avatar {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  background: linear-gradient(135deg, #667eea, #764ba2);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  font-weight: 700;
  flex-shrink: 0;
}

.item-info {
  flex: 1;
  min-width: 0;
}

.item-title {
  display: block;
  font-size: 0.9rem;
  font-weight: 600;
  color: #1e293b;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.item-sub {
  display: block;
  font-size: 0.75rem;
  color: #64748b;
}

/* Empty State */
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 24px 16px;
}

.empty-icon {
  font-size: 2.5rem;
  margin-bottom: 8px;
}

.empty-text {
  font-size: 0.9rem;
  color: #64748b;
  margin-bottom: 4px;
}

.empty-tip {
  font-size: 0.8rem;
  color: #94a3b8;
  margin: 0 0 12px 0;
}

.empty-action {
  display: inline-flex;
  align-items: center;
  padding: 8px 16px;
  background: #22c55e;
  color: #fff;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
}

.empty-action:hover {
  background: #16a34a;
}

/* Responsive */
@media (max-width: 768px) {
  .welcome-banner {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 16px;
  }
  
  .welcome-left h1 {
    font-size: 1.1rem;
  }
  
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .content-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr 1fr;
  }
  
  .stat-card {
    padding: 12px;
  }
  
  .stat-value {
    font-size: 1.25rem;
  }
}
</style>
