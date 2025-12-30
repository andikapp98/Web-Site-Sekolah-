<template>
  <router-view />
</template>

<script setup>
// App.vue now only serves as router outlet
// AdminLayout handles sidebar/header for admin pages
// Public pages have their own layout (PublicNavbar/PublicFooter)
</script>

<style>
/* Global Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --primary-color: #155d27;
  --primary-hover: #0d4f1f;
  --secondary-color: #d4a017;
  --text-color: #1f2937;
  --text-secondary: #64748b;
  --border-color: #e2e8f0;
  --bg-color: #f8fafc;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  color: var(--text-color);
  background: #fff;
  line-height: 1.6;
}

a {
  text-decoration: none;
  color: inherit;
}

button {
  font-family: inherit;
}
</style>
