<template>
  <Teleport to="body">
    <Transition name="toast">
      <div v-if="visible" class="toast-container" :class="type">
        <div class="toast-icon">
          <span v-if="type === 'success'">✓</span>
          <span v-else-if="type === 'error'">✕</span>
          <span v-else-if="type === 'warning'">⚠</span>
          <span v-else>ℹ</span>
        </div>
        <div class="toast-content">
          <p class="toast-title">{{ title }}</p>
          <p v-if="message" class="toast-message">{{ message }}</p>
        </div>
        <button @click="close" class="toast-close">×</button>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  show: { type: Boolean, default: false },
  type: { type: String, default: 'success' }, // success, error, warning, info
  title: { type: String, default: '' },
  message: { type: String, default: '' },
  duration: { type: Number, default: 3000 }
});

const emit = defineEmits(['close']);
const visible = ref(false);
let timer = null;

watch(() => props.show, (val) => {
  visible.value = val;
  if (val && props.duration > 0) {
    clearTimeout(timer);
    timer = setTimeout(() => {
      close();
    }, props.duration);
  }
});

const close = () => {
  visible.value = false;
  emit('close');
};
</script>

<style scoped>
.toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 16px 20px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.15);
  z-index: 10000;
  min-width: 300px;
  max-width: 400px;
  border-left: 4px solid;
}

.toast-container.success {
  border-color: #22c55e;
}
.toast-container.success .toast-icon {
  background: #dcfce7;
  color: #22c55e;
}

.toast-container.error {
  border-color: #ef4444;
}
.toast-container.error .toast-icon {
  background: #fee2e2;
  color: #ef4444;
}

.toast-container.warning {
  border-color: #f59e0b;
}
.toast-container.warning .toast-icon {
  background: #fef3c7;
  color: #f59e0b;
}

.toast-container.info {
  border-color: #3b82f6;
}
.toast-container.info .toast-icon {
  background: #dbeafe;
  color: #3b82f6;
}

.toast-icon {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  font-weight: 700;
  flex-shrink: 0;
}

.toast-content {
  flex: 1;
}

.toast-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
}

.toast-message {
  font-size: 0.85rem;
  color: #64748b;
  margin: 4px 0 0 0;
}

.toast-close {
  background: none;
  border: none;
  font-size: 1.25rem;
  color: #94a3b8;
  cursor: pointer;
  padding: 0;
  line-height: 1;
}

.toast-close:hover {
  color: #64748b;
}

/* Animation */
.toast-enter-active {
  animation: slideIn 0.3s ease;
}

.toast-leave-active {
  animation: slideOut 0.3s ease;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOut {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(100%);
    opacity: 0;
  }
}
</style>
