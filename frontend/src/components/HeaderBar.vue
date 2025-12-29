<template>
  <div class="header-bar">
    <div class="header-content">
      <div class="header-title">
        <div class="header-title-wrapper">
          <img src="/LOGO BULAT PNG.png" alt="Logo SMKS YASMU GRESIK" class="header-logo">
          <div>
            <h2>Selamat Datang di SMKS YASMU GRESIK</h2>
            <p class="header-date">{{ currentDate }}</p>
          </div>
        </div>
      </div>
      <div class="header-actions">
        <div class="notification-area">
          <button class="btn-notification">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
              <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
            </svg>
            <span class="notification-badge">3</span>
          </button>
        </div>
        <div class="user-profile">
          <div class="user-info-header">
            <span class="user-name-header">{{ userName }}</span>
            <span class="user-status">Online</span>
          </div>
          <div class="user-avatar-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
              <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
              <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { getUser } from '../services/auth';

const user = ref(getUser());
const userName = computed(() => user.value ? user.value.name : 'Guest');
const currentDate = ref('');

const updateDate = () => {
  const now = new Date();
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  currentDate.value = now.toLocaleDateString('id-ID', options);
};

onMounted(() => {
  updateDate();
});
</script>

<style scoped>
.header-bar {
  background: linear-gradient(135deg, #ffffff 0%, #f8fdf9 100%);
  border-bottom: 2px solid var(--border-color);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  position: sticky;
  top: 0;
  z-index: 1000;
  padding: 1rem 2rem;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1400px;
  margin: 0 auto;
}

.header-title-wrapper {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.header-logo {
  width: 50px;
  height: 50px;
  object-fit: contain;
}

.header-title h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--primary-color);
  margin: 0;
}

.header-date {
  font-size: 0.9rem;
  color: var(--text-secondary);
  margin: 0.25rem 0 0 0;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.notification-area {
  position: relative;
}

.btn-notification {
  background: var(--background-color);
  border: 2px solid var(--border-color);
  color: var(--primary-color);
  width: 45px;
  height: 45px;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.btn-notification:hover {
  background: var(--primary-color);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(46, 125, 50, 0.2);
}

.notification-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #f44336;
  color: white;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 0.15rem 0.4rem;
  border-radius: 10px;
  min-width: 20px;
  text-align: center;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 1rem;
  background: var(--background-color);
  border: 2px solid var(--border-color);
  border-radius: 12px;
  transition: all 0.3s ease;
}

.user-profile:hover {
  background: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.user-info-header {
  text-align: right;
}

.user-name-header {
  display: block;
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--text-color);
  line-height: 1.2;
}

.user-status {
  display: block;
  font-size: 0.75rem;
  color: var(--secondary-color);
}

.user-avatar-header {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

@media (max-width: 768px) {
  .header-bar {
    padding: 1rem;
  }
  
  .header-title h2 {
    font-size: 1.2rem;
  }
  
  .header-date {
    font-size: 0.8rem;
  }
  
  .user-info-header {
    display: none;
  }
}
</style>
