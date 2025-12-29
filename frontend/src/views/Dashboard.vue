<template>
  <div class="dashboard-container">
    <!-- Welcome Section dengan Gradient Background -->
    <div class="welcome-section">
      <div class="welcome-content">
        <h1 class="welcome-title">Dashboard SMK Yasmu Gresik</h1>
        <p class="welcome-subtitle">{{ greeting }}, selamat datang kembali! 👋</p>
        <p class="welcome-date">{{ currentDate }}</p>
      </div>
      <div class="welcome-illustration">
        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
        </svg>
      </div>
    </div>

    <!-- Stats Cards dengan Animasi -->
    <div class="row">
      <div class="col-md-3 mb-4">
        <div class="stat-card card-primary" @mouseenter="animateCard" @mouseleave="resetCard">
          <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
              <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
            </svg>
          </div>
          <div class="stat-content">
            <h3 class="stat-number">{{ totalStudents }}</h3>
            <p class="stat-label">Total Siswa</p>
            <div class="stat-trend positive">
              <span>↑ 12%</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4">
        <div class="stat-card card-success" @mouseenter="animateCard" @mouseleave="resetCard">
          <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
              <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
              <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
            </svg>
          </div>
          <div class="stat-content">
            <h3 class="stat-number">{{ todayAttendance }}</h3>
            <p class="stat-label">Hadir Hari Ini</p>
            <div class="stat-progress">
              <div class="progress-bar">
                <div class="progress-fill" :style="{ width: attendancePercentage + '%' }"></div>
              </div>
              <span class="progress-text">{{ attendancePercentage }}%</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4">
        <div class="stat-card card-warning" @mouseenter="animateCard" @mouseleave="resetCard">
          <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
              <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z"/>
              <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Z"/>
            </svg>
          </div>
          <div class="stat-content">
            <h3 class="stat-number">{{ averageGrade }}</h3>
            <p class="stat-label">Rata-rata Nilai</p>
            <div class="stat-trend positive">
              <span>↑ 5.2%</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4">
        <div class="stat-card card-info" @mouseenter="animateCard" @mouseleave="resetCard">
          <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" viewBox="0 0 16 16">
              <path d="M6 1v3H1V1h5zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1H1zm14 12v3h-5v-3h5zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-5zM6 8v7H1V8h5zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H1zm14-6v7h-5V1h5zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1h-5z"/>
            </svg>
          </div>
          <div class="stat-content">
            <h3 class="stat-number">{{ totalClasses }}</h3>
            <p class="stat-label">Kelas Aktif</p>
            <div class="stat-trend neutral">
              <span>→ Stabil</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
      <h3 class="section-title">Aksi Cepat</h3>
      <div class="row">
        <div class="col-md-3 mb-3">
          <div class="quick-action-card" @click="handleQuickAction('students')">
            <div class="quick-action-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1v-1c0-1-1-4-6-4s-6 3-6 4v1a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12z"/>
              </svg>
            </div>
            <p class="quick-action-label">Data Siswa</p>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="quick-action-card" @click="handleQuickAction('attendance')">
            <div class="quick-action-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
              </svg>
            </div>
            <p class="quick-action-label">Absensi</p>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="quick-action-card" @click="handleQuickAction('grades')">
            <div class="quick-action-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5Z"/>
              </svg>
            </div>
            <p class="quick-action-label">Nilai</p>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="quick-action-card" @click="handleQuickAction('reports')">
            <div class="quick-action-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                <path d="M4 0h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4z"/>
                <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-2z"/>
              </svg>
            </div>
            <p class="quick-action-label">Laporan</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4">
      <div class="col-md-8 mb-4">
        <div class="card chart-card">
          <h3 class="chart-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 10px;">
              <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z"/>
            </svg>
            Statistik Kehadiran
          </h3>
          <AttendanceChart />
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card activity-card">
          <h3 class="chart-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 10px;">
              <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
            </svg>
            Aktivitas Terbaru
          </h3>
          <div class="activity-list">
            <div v-for="(activity, index) in recentActivities" :key="index" class="activity-item">
              <div class="activity-icon" :class="activity.type">
                <span>{{ activity.icon }}</span>
              </div>
              <div class="activity-content">
                <p class="activity-text">{{ activity.text }}</p>
                <span class="activity-time">{{ activity.time }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Achievement Chart & Announcement -->
    <div class="row mt-4">
      <div class="col-md-6 mb-4">
        <div class="card chart-card">
          <h3 class="chart-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 10px;">
              <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
              <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
            </svg>
            Pencapaian Siswa
          </h3>
          <AchievementChart />
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card announcement-card">
          <div class="announcement-header">
            <h3 class="announcement-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49a68.14 68.14 0 0 0-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 74.663 74.663 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199V2.5zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0zm-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233c.778.02 1.544.063 2.3.13 2.132.192 4.307.673 6.7 1.82V3.85z"/>
              </svg>
              Pengumuman Terbaru
            </h3>
          </div>
          <div class="announcements-list">
            <div v-for="(announcement, index) in announcements" :key="index" class="announcement-item">
              <div class="announcement-badge">
                <span>{{ announcement.badge }}</span>
              </div>
              <div class="announcement-content">
                <h4 class="announcement-item-title">{{ announcement.title }}</h4>
                <p class="announcement-date">📅 {{ announcement.date }}</p>
                <p class="announcement-desc">{{ announcement.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AttendanceChart from '../components/AttendanceChart.vue';
import AchievementChart from '../components/AchievementChart.vue';

// Stats data
const totalStudents = ref(500);
const todayAttendance = ref(480);
const averageGrade = ref(85.5);
const totalClasses = ref(15);

// Computed properties
const attendancePercentage = computed(() => {
  return Math.round((todayAttendance.value / totalStudents.value) * 100);
});

const greeting = computed(() => {
  const hour = new Date().getHours();
  if (hour < 12) return 'Selamat Pagi';
  if (hour < 15) return 'Selamat Siang';
  if (hour < 18) return 'Selamat Sore';
  return 'Selamat Malam';
});

const currentDate = computed(() => {
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  return new Date().toLocaleDateString('id-ID', options);
});

// Recent activities
const recentActivities = ref([
  {
    icon: '👤',
    text: '15 siswa baru terdaftar',
    time: '2 jam yang lalu',
    type: 'success'
  },
  {
    icon: '📝',
    text: 'Ujian Matematika selesai',
    time: '4 jam yang lalu',
    type: 'info'
  },
  {
    icon: '📊',
    text: 'Laporan nilai semester uploaded',
    time: '1 hari yang lalu',
    type: 'warning'
  },
  {
    icon: '🎓',
    text: 'Kelulusan batch 2025 diumumkan',
    time: '2 hari yang lalu',
    type: 'success'
  },
  {
    icon: '📅',
    text: 'Jadwal baru telah dibuat',
    time: '3 hari yang lalu',
    type: 'info'
  }
]);

// Announcements
const announcements = ref([
  {
    badge: 'PENTING',
    title: 'Libur Nasional Hari Raya Idul Fitri',
    date: '20-30 April 2026',
    description: 'Sekolah libur selama 10 hari untuk perayaan Idul Fitri'
  },
  {
    badge: 'INFO',
    title: 'Pendaftaran Ekstrakurikuler',
    date: '15 Mei 2026',
    description: 'Pendaftaran dibuka untuk semua siswa kelas X dan XI'
  },
  {
    badge: 'UJIAN',
    title: 'Ujian Tengah Semester',
    date: '1-5 Juni 2026',
    description: 'Persiapkan diri untuk menghadapi UTS'
  }
]);

// Methods
const handleQuickAction = (action) => {
  console.log('Quick action:', action);
  // Implement navigation or modal opening here
  alert(`Navigating to ${action}...`);
};

const animateCard = (event) => {
  event.currentTarget.style.transform = 'translateY(-8px) scale(1.02)';
};

const resetCard = (event) => {
  event.currentTarget.style.transform = 'translateY(0) scale(1)';
};

// Lifecycle
onMounted(() => {
  console.log('Dashboard mounted');
});
</script>

<style scoped>
.dashboard-container {
  padding: 2rem;
  max-width: 1600px;
  margin: 0 auto;
  background: #f8fafc;
  min-height: 100vh;
}

/* Welcome Section */
.welcome-section {
  background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
  border-radius: 20px;
  padding: 2.5rem;
  margin-bottom: 2.5rem;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
  position: relative;
  overflow: hidden;
}

.welcome-section::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -10%;
  width: 300px;
  height: 300px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
}

.welcome-section::after {
  content: '';
  position: absolute;
  bottom: -30%;
  left: -5%;
  width: 200px;
  height: 200px;
  background: rgba(255, 255, 255, 0.08);
  border-radius: 50%;
}

.welcome-content {
  position: relative;
  z-index: 1;
}

.welcome-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.welcome-subtitle {
  font-size: 1.3rem;
  opacity: 0.95;
  margin-bottom: 0.5rem;
}

.welcome-date {
  font-size: 1rem;
  opacity: 0.85;
}

.welcome-illustration {
  position: relative;
  z-index: 1;
}

.welcome-illustration svg {
  opacity: 0.3;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
}

/* Stat Cards */
.stat-card {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding: 1.75rem;
  border-radius: 20px;
  background: white;
  border: none;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  height: 100%;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  width: 100px;
  height: 100px;
  background: radial-gradient(circle, rgba(46, 125, 50, 0.1) 0%, transparent 70%);
  border-radius: 50%;
  transform: translate(30%, -30%);
}

.stat-card:hover {
  box-shadow: 0 12px 35px rgba(79, 70, 229, 0.15);
}

.stat-icon {
  width: 65px;
  height: 65px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 16px;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.card-primary .stat-icon {
  background: linear-gradient(135deg, #4f46e5, #6366f1);
}

.card-success .stat-icon {
  background: linear-gradient(135deg, #10b981, #34d399);
}

.card-warning .stat-icon {
  background: linear-gradient(135deg, #f59e0b, #fbbf24);
}

.card-info .stat-icon {
  background: linear-gradient(135deg, #3b82f6, #60a5fa);
}

.stat-content {
  flex: 1;
}

.stat-number {
  font-size: 2.8rem;
  font-weight: 700;
  color: var(--primary-color);
  margin: 0;
  line-height: 1;
}

.stat-label {
  font-size: 1rem;
  font-weight: 600;
  color: var(--text-color);
  margin: 0.75rem 0 0.5rem 0;
}

.stat-trend {
  display: inline-block;
  padding: 0.35rem 0.85rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  margin-top: 0.5rem;
}

.stat-trend.positive {
  background: linear-gradient(135deg, #dbeafe, #bfdbfe);
  color: #1e40af;
}

.stat-trend.neutral {
  background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
  color: #475569;
}

.stat-progress {
  margin-top: 0.75rem;
}

.progress-bar {
  width: 100%;
  height: 8px;
  background: #e0e0e0;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 0.4rem;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #4f46e5, #818cf8);
  border-radius: 10px;
  transition: width 1s ease-in-out;
}

.progress-text {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--primary-color);
}

/* Quick Actions */
.quick-actions-section {
  margin: 2.5rem 0;
}

.section-title {
  color: var(--primary-color);
  font-size: 1.75rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  padding-left: 0.5rem;
  border-left: 5px solid var(--primary-color);
}

.quick-action-card {
  background: white;
  border-radius: 16px;
  padding: 2rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
  height: 100%;
  border: 2px solid transparent;
}

.quick-action-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 25px rgba(79, 70, 229, 0.2);
  border-color: var(--primary-color);
}

.quick-action-icon {
  width: 70px;
  height: 70px;
  margin: 0 auto 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: linear-gradient(135deg, #dbeafe, #bfdbfe);
  color: var(--primary-color);
  transition: all 0.3s ease;
}

.quick-action-card:hover .quick-action-icon {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  transform: scale(1.1) rotate(5deg);
}

.quick-action-label {
  font-weight: 600;
  color: var(--text-color);
  margin: 0;
  font-size: 1.05rem;
}

/* Chart Cards */
.chart-card {
  height: 100%;
  min-height: 380px;
  background: white;
  border-radius: 16px;
  padding: 1.75rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.chart-title {
  color: var(--primary-color);
  font-size: 1.4rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 3px solid var(--border-color);
  display: flex;
  align-items: center;
}

/* Activity Card */
.activity-card {
  background: white;
  border-radius: 16px;
  padding: 1.75rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  height: 100%;
}

.activity-list {
  max-height: 350px;
  overflow-y: auto;
  padding-right: 0.5rem;
}

.activity-list::-webkit-scrollbar {
  width: 6px;
}

.activity-list::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.activity-list::-webkit-scrollbar-thumb {
  background: var(--primary-color);
  border-radius: 10px;
}

.activity-item {
  display: flex;
  gap: 1rem;
  padding: 1rem;
  border-radius: 12px;
  margin-bottom: 0.75rem;
  background: #f8fafc;
  border-left: 4px solid var(--primary-color);
  transition: all 0.3s ease;
}

.activity-item:hover {
  background: #f1f5f9;
  transform: translateX(5px);
}

.activity-icon {
  width: 45px;
  height: 45px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.activity-icon.success {
  background: linear-gradient(135deg, #d1fae5, #a7f3d0);
}

.activity-icon.info {
  background: linear-gradient(135deg, #dbeafe, #bfdbfe);
}

.activity-icon.warning {
  background: linear-gradient(135deg, #fef3c7, #fde68a);
}

.activity-content {
  flex: 1;
}

.activity-text {
  font-weight: 600;
  color: var(--text-color);
  margin: 0 0 0.25rem 0;
  font-size: 0.95rem;
}

.activity-time {
  font-size: 0.8rem;
  color: var(--text-secondary);
}

/* Announcement Card */
.announcement-card {
  background: white;
  border-radius: 16px;
  padding: 1.75rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  height: 100%;
}

.announcement-header {
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 3px solid var(--border-color);
}

.announcement-title {
  color: var(--primary-color);
  font-size: 1.4rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin: 0;
}

.announcements-list {
  max-height: 350px;
  overflow-y: auto;
}

.announcement-item {
  display: flex;
  gap: 1rem;
  padding: 1.25rem;
  border-radius: 12px;
  margin-bottom: 1rem;
  background: #f8fafc;
  border: 2px solid var(--border-color);
  transition: all 0.3s ease;
}

.announcement-item:hover {
  background: #f1f5f9;
  border-color: var(--primary-color);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
}

.announcement-badge {
  flex-shrink: 0;
}

.announcement-badge span {
  display: inline-block;
  padding: 0.4rem 0.8rem;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.announcement-content {
  flex: 1;
}

.announcement-item-title {
  color: var(--text-color);
  font-size: 1.1rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
}

.announcement-date {
  color: var(--text-secondary);
  font-size: 0.9rem;
  margin: 0 0 0.5rem 0;
}

.announcement-desc {
  color: var(--text-secondary);
  font-size: 0.9rem;
  margin: 0;
  line-height: 1.5;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .col-md-3 {
    flex: 0 0 50%;
    max-width: 50%;
  }
}

@media (max-width: 768px) {
  .dashboard-container {
    padding: 1rem;
  }
  
  .welcome-section {
    flex-direction: column;
    text-align: center;
    padding: 2rem 1.5rem;
  }
  
  .welcome-title {
    font-size: 1.8rem;
  }
  
  .welcome-illustration {
    margin-top: 1rem;
  }
  
  .col-md-3, .col-md-4, .col-md-6, .col-md-8 {
    flex: 0 0 100%;
    max-width: 100%;
  }
  
  .stat-number {
    font-size: 2.2rem;
  }
  
  .section-title {
    font-size: 1.4rem;
  }
}

/* Animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.stat-card, .quick-action-card, .chart-card, .activity-card, .announcement-card {
  animation: fadeInUp 0.6s ease-out;
  animation-fill-mode: both;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
</style>
