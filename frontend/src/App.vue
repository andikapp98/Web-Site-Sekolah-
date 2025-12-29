<template>
  <div class="app-layout">
    <Sidebar v-if="showSidebar" />
    <div class="main-content" :class="{ 'no-sidebar': !showSidebar }">
      <HeaderBar v-if="showHeaderBar" />
      <router-view />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import Sidebar from './components/Sidebar.vue';
import HeaderBar from './components/HeaderBar.vue';

const route = useRoute();

// Show sidebar and header only for authenticated pages (dashboard, admin pages)
const showSidebar = computed(() => {
  return route.meta.requiresAuth === true;
});

const showHeaderBar = computed(() => {
  return route.meta.requiresAuth === true;
});
</script>

<style>
.app-layout {
  display: flex;
}

.main-content {
  flex-grow: 1;
  height: 100vh;
  overflow-y: auto;
}

.main-content.no-sidebar {
  margin-left: 0;
  width: 100%;
}
</style>


