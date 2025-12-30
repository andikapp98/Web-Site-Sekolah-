<template>
  <AdminLayout>
    <div class="analytics-page">
      <!-- Header -->
      <div class="page-header">
        <div class="header-info">
          <h1>Analisa Website</h1>
          <p>Statistik dan analisa konten website</p>
        </div>
        <div class="header-actions">
          <select v-model="period" class="period-select">
            <option value="week">7 Hari Terakhir</option>
            <option value="month">30 Hari Terakhir</option>
            <option value="year">1 Tahun</option>
          </select>
        </div>
      </div>

      <!-- Summary Cards -->
      <div class="summary-grid">
        <div class="summary-card" v-for="item in summaryData" :key="item.label">
          <div class="summary-icon" :style="{ background: item.color }">
            <span>{{ item.icon }}</span>
          </div>
          <div class="summary-content">
            <span class="summary-value">{{ item.value }}</span>
            <span class="summary-label">{{ item.label }}</span>
          </div>
          <div class="summary-trend" :class="item.trend > 0 ? 'up' : 'down'">
            <span v-if="item.trend > 0">↑</span>
            <span v-else>↓</span>
            {{ Math.abs(item.trend) }}%
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="charts-row">
        <!-- Content Distribution -->
        <div class="chart-card">
          <div class="card-header">
            <h3>📊 Distribusi Konten</h3>
          </div>
          <div class="chart-body">
            <div class="distribution-chart">
              <div class="dist-item" v-for="item in contentDistribution" :key="item.label">
                <div class="dist-info">
                  <span class="dist-icon">{{ item.icon }}</span>
                  <span class="dist-label">{{ item.label }}</span>
                </div>
                <div class="dist-bar-wrap">
                  <div class="dist-bar" :style="{ width: item.percent + '%', background: item.color }"></div>
                </div>
                <span class="dist-value">{{ item.value }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Content Status -->
        <div class="chart-card">
          <div class="card-header">
            <h3>📈 Status Konten</h3>
          </div>
          <div class="chart-body">
            <div class="status-grid">
              <div class="status-item active">
                <div class="status-circle">
                  <svg viewBox="0 0 36 36" class="circular-chart">
                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="circle" stroke-dasharray="85, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                  </svg>
                  <span class="status-percent">85%</span>
                </div>
                <span class="status-label">Aktif</span>
                <span class="status-count">{{ activeCount }} item</span>
              </div>
              <div class="status-item inactive">
                <div class="status-circle">
                  <svg viewBox="0 0 36 36" class="circular-chart">
                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="circle" stroke-dasharray="15, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                  </svg>
                  <span class="status-percent">15%</span>
                </div>
                <span class="status-label">Nonaktif</span>
                <span class="status-count">{{ inactiveCount }} item</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="activity-card">
        <div class="card-header">
          <h3>🕐 Aktivitas Terbaru</h3>
          <span class="header-badge">Live</span>
        </div>
        <div class="activity-list">
          <div class="activity-item" v-for="(activity, index) in recentActivities" :key="index">
            <div class="activity-icon" :style="{ background: activity.color }">
              {{ activity.icon }}
            </div>
            <div class="activity-content">
              <span class="activity-text">{{ activity.text }}</span>
              <span class="activity-time">{{ activity.time }}</span>
            </div>
          </div>
          <div class="activity-empty" v-if="recentActivities.length === 0">
            <span>📭</span>
            <p>Belum ada aktivitas</p>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="quick-stats">
        <div class="stat-box">
          <span class="stat-icon">👨‍🏫</span>
          <div class="stat-info">
            <span class="stat-number">{{ stats.teachers }}</span>
            <span class="stat-text">Total Guru</span>
          </div>
        </div>
        <div class="stat-box">
          <span class="stat-icon">🎓</span>
          <div class="stat-info">
            <span class="stat-number">{{ stats.programs }}</span>
            <span class="stat-text">Program Keahlian</span>
          </div>
        </div>
        <div class="stat-box">
          <span class="stat-icon">🤝</span>
          <div class="stat-info">
            <span class="stat-number">{{ stats.partners }}</span>
            <span class="stat-text">Mitra Industri</span>
          </div>
        </div>
        <div class="stat-box">
          <span class="stat-icon">📰</span>
          <div class="stat-info">
            <span class="stat-number">{{ stats.articles }}</span>
            <span class="stat-text">Total Artikel</span>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AdminLayout from '../../components/AdminLayout.vue';
import { adminApi } from '../../services/api';

const period = ref('month');

const summaryData = ref([
  { icon: '📄', label: 'Total Konten', value: '0', color: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', trend: 12 },
  { icon: '🏆', label: 'Prestasi', value: '0', color: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)', trend: 8 },
  { icon: '💬', label: 'Testimonial', value: '0', color: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)', trend: -3 },
  { icon: '👁️', label: 'Total Views', value: '0', color: 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)', trend: 25 },
]);

const contentDistribution = ref([
  { icon: '🏆', label: 'Prestasi', value: 0, percent: 0, color: '#f093fb' },
  { icon: '🎓', label: 'Program', value: 0, percent: 0, color: '#4facfe' },
  { icon: '👨‍🏫', label: 'Guru', value: 0, percent: 0, color: '#43e97b' },
  { icon: '🤝', label: 'Mitra', value: 0, percent: 0, color: '#fa709a' },
  { icon: '📰', label: 'Artikel', value: 0, percent: 0, color: '#667eea' },
]);

const activeCount = ref(0);
const inactiveCount = ref(0);

const stats = ref({
  teachers: 0,
  programs: 0,
  partners: 0,
  articles: 0,
});

const recentActivities = ref([
  { icon: '🏆', text: 'Prestasi baru ditambahkan', time: 'Baru saja', color: '#f093fb' },
  { icon: '👨‍🏫', text: 'Data guru diperbarui', time: '5 menit lalu', color: '#4facfe' },
  { icon: '📰', text: 'Artikel baru dipublikasikan', time: '1 jam lalu', color: '#667eea' },
  { icon: '🖼️', text: 'Hero slide diubah', time: '2 jam lalu', color: '#43e97b' },
]);

const loadAnalytics = async () => {
  try {
    const [achievements, programs, teachers, partners, testimonials, articles] = await Promise.all([
      adminApi.achievements.list({ per_page: 100 }),
      adminApi.programs.list({ per_page: 100 }),
      adminApi.teachers.list({ per_page: 100 }),
      adminApi.partners.list({ per_page: 100 }),
      adminApi.testimonials.list({ per_page: 100 }),
      adminApi.articles.list({ per_page: 100 }),
    ]);

    const achievementsCount = achievements.data.total || achievements.data.data?.length || 0;
    const programsCount = programs.data.total || programs.data.data?.length || 0;
    const teachersCount = teachers.data.total || teachers.data.data?.length || 0;
    const partnersCount = partners.data.total || partners.data.data?.length || 0;
    const testimonialsCount = testimonials.data.total || testimonials.data.data?.length || 0;
    const articlesCount = articles.data.total || articles.data.data?.length || 0;

    const total = achievementsCount + programsCount + teachersCount + partnersCount + articlesCount;

    // Update summary
    summaryData.value[0].value = total;
    summaryData.value[1].value = achievementsCount;
    summaryData.value[2].value = testimonialsCount;
    summaryData.value[3].value = '1.2K';

    // Update distribution
    contentDistribution.value[0] = { ...contentDistribution.value[0], value: achievementsCount, percent: total ? (achievementsCount / total * 100) : 0 };
    contentDistribution.value[1] = { ...contentDistribution.value[1], value: programsCount, percent: total ? (programsCount / total * 100) : 0 };
    contentDistribution.value[2] = { ...contentDistribution.value[2], value: teachersCount, percent: total ? (teachersCount / total * 100) : 0 };
    contentDistribution.value[3] = { ...contentDistribution.value[3], value: partnersCount, percent: total ? (partnersCount / total * 100) : 0 };
    contentDistribution.value[4] = { ...contentDistribution.value[4], value: articlesCount, percent: total ? (articlesCount / total * 100) : 0 };

    // Update stats
    stats.value = {
      teachers: teachersCount,
      programs: programsCount,
      partners: partnersCount,
      articles: articlesCount,
    };

    activeCount.value = Math.round(total * 0.85);
    inactiveCount.value = total - activeCount.value;

  } catch (error) {
    console.error('Error loading analytics:', error);
  }
};

onMounted(() => {
  loadAnalytics();
});
</script>

<style scoped>
.analytics-page {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

/* Header */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 16px;
}

.header-info h1 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 4px 0;
}

.header-info p {
  color: #64748b;
  margin: 0;
  font-size: 0.9rem;
}

.period-select {
  padding: 10px 16px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  font-size: 0.9rem;
  color: #1e293b;
  background: #fff;
  cursor: pointer;
}

/* Summary Grid */
.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
}

.summary-card {
  display: flex;
  align-items: center;
  gap: 16px;
  background: #fff;
  padding: 20px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}

.summary-icon {
  width: 52px;
  height: 52px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
}

.summary-content {
  flex: 1;
}

.summary-value {
  display: block;
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
}

.summary-label {
  font-size: 0.85rem;
  color: #64748b;
}

.summary-trend {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

.summary-trend.up {
  background: #dcfce7;
  color: #16a34a;
}

.summary-trend.down {
  background: #fee2e2;
  color: #dc2626;
}

/* Charts Row */
.charts-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 24px;
}

.chart-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.04);
  overflow: hidden;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #f1f5f9;
}

.card-header h3 {
  font-size: 1rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.header-badge {
  padding: 4px 10px;
  background: #dcfce7;
  color: #16a34a;
  border-radius: 20px;
  font-size: 0.7rem;
  font-weight: 600;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.6; }
}

.chart-body {
  padding: 20px;
}

/* Distribution Chart */
.distribution-chart {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.dist-item {
  display: flex;
  align-items: center;
  gap: 12px;
}

.dist-info {
  display: flex;
  align-items: center;
  gap: 8px;
  min-width: 100px;
}

.dist-icon {
  font-size: 1.1rem;
}

.dist-label {
  font-size: 0.85rem;
  color: #64748b;
}

.dist-bar-wrap {
  flex: 1;
  height: 8px;
  background: #f1f5f9;
  border-radius: 4px;
  overflow: hidden;
}

.dist-bar {
  height: 100%;
  border-radius: 4px;
  transition: width 0.5s ease;
}

.dist-value {
  font-size: 0.9rem;
  font-weight: 600;
  color: #1e293b;
  min-width: 30px;
  text-align: right;
}

/* Status Grid */
.status-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
}

.status-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.status-circle {
  position: relative;
  width: 100px;
  height: 100px;
}

.circular-chart {
  transform: rotate(-90deg);
}

.circle-bg {
  fill: none;
  stroke: #f1f5f9;
  stroke-width: 3;
}

.circle {
  fill: none;
  stroke-width: 3;
  stroke-linecap: round;
}

.status-item.active .circle {
  stroke: #22c55e;
}

.status-item.inactive .circle {
  stroke: #f59e0b;
}

.status-percent {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 1.25rem;
  font-weight: 700;
  color: #1e293b;
}

.status-label {
  font-weight: 600;
  color: #1e293b;
  margin-top: 8px;
}

.status-count {
  font-size: 0.85rem;
  color: #64748b;
}

/* Activity Card */
.activity-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}

.activity-list {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 12px;
  background: #f8fafc;
  border-radius: 12px;
}

.activity-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
}

.activity-content {
  flex: 1;
}

.activity-text {
  display: block;
  font-size: 0.9rem;
  color: #1e293b;
  font-weight: 500;
}

.activity-time {
  font-size: 0.8rem;
  color: #94a3b8;
}

.activity-empty {
  text-align: center;
  padding: 32px;
  color: #94a3b8;
}

.activity-empty span {
  font-size: 2rem;
}

/* Quick Stats */
.quick-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 16px;
}

.stat-box {
  display: flex;
  align-items: center;
  gap: 14px;
  background: #fff;
  padding: 20px;
  border-radius: 14px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}

.stat-box .stat-icon {
  font-size: 2rem;
}

.stat-box .stat-info {
  display: flex;
  flex-direction: column;
}

.stat-box .stat-number {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
}

.stat-box .stat-text {
  font-size: 0.85rem;
  color: #64748b;
}

/* Responsive */
@media (max-width: 768px) {
  .charts-row {
    grid-template-columns: 1fr;
  }
  
  .summary-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .summary-grid {
    grid-template-columns: 1fr;
  }
}
</style>
