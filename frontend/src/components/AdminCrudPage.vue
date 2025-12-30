<template>
  <AdminLayout>
    <div class="crud-page">
      <!-- Header -->
      <div class="page-header">
        <h1>{{ title }}</h1>
        <button @click="openCreateModal" class="btn-primary">
          <span>➕</span> Tambah Baru
        </button>
      </div>
      
      <!-- Data Table -->
      <div class="table-card">
        <div class="table-loading" v-if="loading">
          <span class="loader"></span> Memuat data...
        </div>
        
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th v-for="col in columns" :key="col.key">{{ col.label }}</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td v-for="col in columns" :key="col.key">
                <img v-if="col.type === 'image' && item[col.key]" 
                     :src="getImageUrl(item[col.key])" 
                     class="table-image" 
                     @error="handleImageError" />
                <span v-else-if="col.type === 'text'" class="text-truncate">
                  {{ item[col.key] }}
                </span>
                <span v-else>{{ item[col.key] || '-' }}</span>
              </td>
              <td>
                <span class="status-badge" :class="item.is_active ? 'active' : 'inactive'">
                  {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="actions">
                <button @click="editItem(item)" class="btn-icon btn-edit" title="Edit">✏️</button>
                <button @click="deleteItem(item)" class="btn-icon btn-delete" title="Hapus">🗑️</button>
              </td>
            </tr>
            <tr v-if="items.length === 0">
              <td :colspan="columns.length + 3" class="empty-row">
                Belum ada data
              </td>
            </tr>
          </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="pagination" v-if="pagination.lastPage > 1">
          <button 
            @click="changePage(pagination.currentPage - 1)" 
            :disabled="pagination.currentPage === 1"
            class="page-btn"
          >&laquo; Prev</button>
          <span class="page-info">
            Halaman {{ pagination.currentPage }} dari {{ pagination.lastPage }}
          </span>
          <button 
            @click="changePage(pagination.currentPage + 1)" 
            :disabled="pagination.currentPage === pagination.lastPage"
            class="page-btn"
          >Next &raquo;</button>
        </div>
      </div>
      
      <!-- Modal Form -->
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} {{ singularTitle }}</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <slot name="form" :form="form" :isEditing="isEditing"></slot>
            
            <div class="modal-actions">
              <button type="button" @click="closeModal" class="btn-secondary">Batal</button>
              <button type="submit" class="btn-primary" :disabled="saving">
                {{ saving ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Simpan') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue';
import AdminLayout from './AdminLayout.vue';

const props = defineProps({
  title: { type: String, required: true },
  singularTitle: { type: String, required: true },
  columns: { type: Array, required: true },
  items: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
  pagination: { 
    type: Object, 
    default: () => ({ currentPage: 1, lastPage: 1, total: 0 }) 
  },
  initialForm: { type: Object, default: () => ({}) },
});

const emit = defineEmits(['create', 'update', 'delete', 'page-change']);

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const form = ref({ ...props.initialForm });
const saving = ref(false);

const openCreateModal = () => {
  isEditing.value = false;
  editingId.value = null;
  form.value = { ...props.initialForm };
  showModal.value = true;
};

const editItem = (item) => {
  isEditing.value = true;
  editingId.value = item.id;
  form.value = { ...item };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  form.value = { ...props.initialForm };
};

const saveItem = async () => {
  saving.value = true;
  try {
    if (isEditing.value) {
      emit('update', { id: editingId.value, data: form.value });
    } else {
      emit('create', form.value);
    }
  } finally {
    saving.value = false;
  }
};

const deleteItem = (item) => {
  if (confirm(`Apakah Anda yakin ingin menghapus "${item.name || item.title}"?`)) {
    emit('delete', item.id);
  }
};

const changePage = (page) => {
  emit('page-change', page);
};

const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http')) return path;
  return `http://localhost:8000/storage/${path}`;
};

const handleImageError = (e) => {
  e.target.src = '/placeholder.png';
};

// Expose methods to parent
defineExpose({ closeModal, openCreateModal });

// Watch for external close trigger
watch(() => props.loading, (newVal) => {
  if (!newVal && saving.value) {
    saving.value = false;
    closeModal();
  }
});
</script>

<style scoped>
.crud-page {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.page-header h1 {
  margin: 0;
  font-size: 1.5rem;
  color: #1f2937;
}

.btn-primary {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: #155d27;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary:hover {
  background: #0d4f1f;
  transform: translateY(-1px);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  padding: 0.75rem 1.25rem;
  background: #e5e7eb;
  color: #374151;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary:hover {
  background: #d1d5db;
}

/* Table */
.table-card {
  background: #fff;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  overflow-x: auto;
}

.table-loading {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  padding: 3rem;
  color: #64748b;
}

.loader {
  width: 24px;
  height: 24px;
  border: 3px solid #e5e7eb;
  border-top-color: #155d27;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th,
.data-table td {
  padding: 0.875rem 1rem;
  text-align: left;
  border-bottom: 1px solid #e5e7eb;
}

.data-table th {
  background: #f8fafc;
  font-weight: 600;
  color: #374151;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.data-table tbody tr:hover {
  background: #f8fafc;
}

.table-image {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 6px;
}

.text-truncate {
  max-width: 200px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  display: block;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

.status-badge.active {
  background: #dcfce7;
  color: #166534;
}

.status-badge.inactive {
  background: #fee2e2;
  color: #991b1b;
}

.actions {
  display: flex;
  gap: 0.5rem;
}

.btn-icon {
  width: 32px;
  height: 32px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.btn-edit {
  background: #e0f2fe;
}

.btn-edit:hover {
  background: #bae6fd;
}

.btn-delete {
  background: #fee2e2;
}

.btn-delete:hover {
  background: #fecaca;
}

.empty-row {
  text-align: center;
  color: #94a3b8;
  font-style: italic;
  padding: 2rem !important;
}

/* Pagination */
.pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
  margin-top: 1rem;
}

.page-btn {
  padding: 0.5rem 1rem;
  border: 1px solid #e5e7eb;
  background: #fff;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

.page-btn:hover:not(:disabled) {
  background: #f8fafc;
  border-color: #155d27;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-info {
  color: #64748b;
  font-size: 0.9rem;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.modal-content {
  background: #fff;
  border-radius: 16px;
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h2 {
  margin: 0;
  font-size: 1.25rem;
  color: #1f2937;
}

.modal-close {
  width: 36px;
  height: 36px;
  border: none;
  background: #f3f4f6;
  border-radius: 8px;
  font-size: 1.5rem;
  cursor: pointer;
  color: #6b7280;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-close:hover {
  background: #e5e7eb;
}

.modal-form {
  padding: 1.5rem;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  padding-top: 1.5rem;
  margin-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
}
</style>
