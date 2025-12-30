<template>
  <AdminLayout>
    <div class="admin-page">
      <div class="page-header">
        <h1>Mitra Industri</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Tambah Mitra
        </button>
      </div>
      
      <div class="table-card">
        <div class="table-loading" v-if="loading">
          <span class="loader"></span> Memuat data...
        </div>
        
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Logo</th>
              <th>Nama Perusahaan</th>
              <th>Website</th>
              <th>Urutan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td>
                <img v-if="item.logo" :src="getImageUrl(item.logo)" class="table-image" />
                <Building2 v-else class="no-image-icon" :size="24" />
              </td>
              <td><strong>{{ item.name }}</strong></td>
              <td>
                <a v-if="item.website" :href="item.website" target="_blank" class="link">{{ item.website }}</a>
                <span v-else>-</span>
              </td>
              <td>{{ item.order }}</td>
              <td>
                <span class="status-badge" :class="item.is_active ? 'active' : 'inactive'">
                  {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="actions">
                <button @click="openModal(item)" class="btn-icon btn-edit"><Pencil :size="16" /></button>
                <button @click="deleteItem(item)" class="btn-icon btn-delete"><Trash2 :size="16" /></button>
              </td>
            </tr>
            <tr v-if="items.length === 0 && !loading">
              <td colspan="7" class="empty-row">Belum ada data mitra</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Modal Form -->
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} Mitra Industri</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <div class="form-group">
              <label>Nama Perusahaan *</label>
              <input v-model="form.name" type="text" required placeholder="Nama perusahaan/instansi" />
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Website</label>
                <input v-model="form.website" type="url" placeholder="https://..." />
              </div>
              <div class="form-group">
                <label>Urutan</label>
                <input v-model.number="form.order" type="number" min="0" />
              </div>
            </div>
            
            <div class="form-group">
              <label>Logo</label>
              <input type="file" @change="handleFileChange" accept="image/*" />
              <div v-if="imagePreview" class="image-preview">
                <img :src="imagePreview" alt="Preview" />
                <button type="button" @click="removeImage" class="remove-image">✕</button>
              </div>
            </div>
            
            <div class="form-group">
              <label>Deskripsi</label>
              <textarea v-model="form.description" rows="3" placeholder="Deskripsi kerjasama..."></textarea>
            </div>
            
            <div class="form-group">
              <label class="checkbox-label">
                <input type="checkbox" v-model="form.is_active" />
                <span>Aktif</span>
              </label>
            </div>
            
            <div class="modal-actions">
              <button type="button" @click="closeModal" class="btn-secondary">Batal</button>
              <button type="submit" class="btn-primary" :disabled="saving">
                {{ saving ? 'Menyimpan...' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Toast Notification -->
      <ToastNotification 
        :show="toast.show" 
        :type="toast.type" 
        :title="toast.title" 
        :message="toast.message" 
        @close="toast.show = false" 
      />
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { Building2, Plus, Pencil, Trash2 } from 'lucide-vue-next';
import AdminLayout from '../../components/AdminLayout.vue';
import ToastNotification from '../../components/ToastNotification.vue';
import { adminApi } from '../../services/api';

const items = ref([]);
const loading = ref(true);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const editingId = ref(null);
const imagePreview = ref(null);

const toast = reactive({
  show: false,
  type: 'success',
  title: '',
  message: ''
});

const showToast = (type, title, message = '') => {
  toast.show = false;
  setTimeout(() => {
    toast.type = type;
    toast.title = title;
    toast.message = message;
    toast.show = true;
  }, 100);
};

const initialForm = { name: '', logo: null, website: '', description: '', order: 0, is_active: true };
const form = ref({ ...initialForm });

const loadData = async () => {
  loading.value = true;
  try {
    const response = await adminApi.partners.list({ per_page: 100 });
    items.value = response.data.data || response.data;
  } catch (error) {
    console.error('Error:', error);
    showToast('error', 'Gagal memuat data', error.message);
  } finally {
    loading.value = false;
  }
};

const openModal = (item = null) => {
  if (item) {
    isEditing.value = true;
    editingId.value = item.id;
    form.value = { ...item };
    imagePreview.value = item.logo ? getImageUrl(item.logo) : null;
  } else {
    isEditing.value = false;
    editingId.value = null;
    form.value = { ...initialForm };
    imagePreview.value = null;
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  imagePreview.value = null;
};

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.value.logo = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const removeImage = () => {
  form.value.logo = null;
  imagePreview.value = null;
};

const saveItem = async () => {
  saving.value = true;
  try {
    const formData = { ...form.value };
    if (formData.logo && !(formData.logo instanceof File)) {
      delete formData.logo;
    }

    if (isEditing.value) {
      await adminApi.partners.update(editingId.value, formData);
      showToast('success', 'Berhasil!', 'Mitra berhasil diperbarui');
    } else {
      await adminApi.partners.create(formData);
      showToast('success', 'Berhasil!', 'Mitra berhasil ditambahkan');
    }
    closeModal();
    loadData();
  } catch (error) {
    const msg = error.response?.data?.message || error.message;
    showToast('error', 'Gagal menyimpan', msg);
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (item) => {
  if (!confirm(`Hapus "${item.name}"?`)) return;
  try {
    await adminApi.partners.delete(item.id);
    showToast('success', 'Berhasil!', 'Mitra berhasil dihapus');
    loadData();
  } catch (error) {
    showToast('error', 'Gagal menghapus', error.message);
  }
};

const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http') || path.startsWith('/')) return path;
  return `http://localhost:8000/storage/${path}`;
};

onMounted(loadData);
</script>

<style scoped>
@import '../../assets/admin-common.css';
.link { color: #155d27; text-decoration: none; }
.link:hover { text-decoration: underline; }
.image-preview { position: relative; display: inline-block; margin-top: 10px; }
.image-preview img { max-width: 150px; max-height: 100px; border-radius: 8px; object-fit: contain; }
.remove-image { position: absolute; top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; background: #ef4444; color: white; border: none; cursor: pointer; font-size: 14px; }
</style>
