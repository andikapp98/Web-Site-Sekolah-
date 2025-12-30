<template>
  <AdminLayout>
    <div class="admin-page">
      <div class="page-header">
        <h1>Profil Sekolah & Visi Misi</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Tambah Profil
        </button>
      </div>
      
      <div class="table-card">
        <div class="table-loading" v-if="loading"><span class="loader"></span> Memuat...</div>
        
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Gambar</th>
              <th>Judul</th>
              <th>Konten</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td>
                <img v-if="item.image" :src="getImageUrl(item.image)" class="table-image" />
                <FileText v-else class="no-image-icon" :size="24" />
              </td>
              <td><strong>{{ item.title }}</strong><br><small class="text-muted">{{ item.key }}</small></td>
              <td class="text-truncate">{{ item.content?.substring(0, 80) }}...</td>
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
              <td colspan="6" class="empty-row">Belum ada profil</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Modal Form -->
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content modal-large">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} Profil</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <div class="form-row">
              <div class="form-group">
                <label>Key (identifier) *</label>
                <select v-model="form.key" required :disabled="isEditing">
                  <option value="">Pilih Key</option>
                  <option value="principal_message">Sambutan Kepala Sekolah</option>
                  <option value="vision">Visi</option>
                  <option value="mission">Misi</option>
                  <option value="history">Sejarah Sekolah</option>
                  <option value="school_info">Profil Sekolah</option>
                  <option value="structure">Struktur Organisasi</option>
                </select>
              </div>
              <div class="form-group">
                <label>Judul</label>
                <input v-model="form.title" type="text" placeholder="Judul bagian" />
              </div>
            </div>
            
            <div class="form-group" v-if="form.key === 'principal_message'">
              <label>Nama Kepala Sekolah</label>
              <input v-model="form.subtitle" type="text" placeholder="Nama dengan gelar" />
            </div>
            
            <div class="form-group">
              <label>Konten *</label>
              <textarea v-model="form.content" required rows="10" placeholder="Isi konten..."></textarea>
            </div>
            
            <!-- Image Upload - Show for profiles that need images -->
            <div class="form-group" v-if="['principal_message', 'school_info', 'history', 'structure'].includes(form.key)">
              <label>
                {{ form.key === 'principal_message' ? 'Foto Kepala Sekolah' : 
                   form.key === 'school_info' ? 'Foto Gedung/Profil Sekolah' : 
                   form.key === 'history' ? 'Foto Sejarah Sekolah' :
                   'Gambar Struktur Organisasi' }}
              </label>
              <input type="file" @change="handleImageChange" accept="image/*" />
              <div v-if="imagePreview" class="image-preview">
                <img :src="imagePreview" />
                <button type="button" @click="removeImage" class="remove-image">✕</button>
              </div>
              <p class="form-hint" v-if="form.key === 'school_info'">
                Gambar ini akan tampil di halaman Profil sekolah
              </p>
              <p class="form-hint" v-if="form.key === 'history'">
                Gambar ini akan tampil di bagian Sejarah
              </p>
            </div>
            
            <div class="form-group">
              <label class="checkbox-label">
                <input type="checkbox" v-model="form.is_active" />
                <span>Aktif</span>
              </label>
            </div>
            
            <div class="modal-actions">
              <button type="button" @click="closeModal" class="btn-secondary">Batal</button>
              <button type="submit" class="btn-primary" :disabled="saving">{{ saving ? 'Menyimpan...' : 'Simpan' }}</button>
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
import { FileText, Plus, Pencil, Trash2 } from 'lucide-vue-next';
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

const initialForm = { key: '', title: '', subtitle: '', content: '', image: null, is_active: true };
const form = ref({ ...initialForm });

const loadData = async () => {
  loading.value = true;
  try {
    const response = await adminApi.schoolProfiles.list({ per_page: 100 });
    items.value = response.data.data || response.data || [];
  } catch (e) {
    console.error('Error loading data:', e);
    showToast('error', 'Gagal memuat data', e.message);
    items.value = [];
  } finally {
    loading.value = false;
  }
};

const openModal = (item = null) => {
  isEditing.value = !!item;
  editingId.value = item?.id;
  if (item) {
    form.value = { ...item };
    imagePreview.value = item.image ? getImageUrl(item.image) : null;
  } else {
    form.value = { ...initialForm };
    imagePreview.value = null;
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  imagePreview.value = null;
};

const handleImageChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.value.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const removeImage = () => {
  form.value.image = null;
  imagePreview.value = null;
};

const saveItem = async () => {
  saving.value = true;
  try {
    const formData = { ...form.value };
    
    // Only include image if it's a File object
    if (formData.image && !(formData.image instanceof File)) {
      delete formData.image;
    }

    if (isEditing.value) {
      await adminApi.schoolProfiles.update(editingId.value, formData);
      showToast('success', 'Berhasil!', 'Profil berhasil diperbarui');
    } else {
      await adminApi.schoolProfiles.create(formData);
      showToast('success', 'Berhasil!', 'Profil berhasil ditambahkan');
    }
    closeModal();
    loadData();
  } catch (e) {
    console.error('Save error:', e);
    const errorMsg = e.response?.data?.message || e.response?.data?.errors || e.message;
    showToast('error', 'Gagal menyimpan', typeof errorMsg === 'object' ? JSON.stringify(errorMsg) : errorMsg);
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (item) => {
  if (!confirm(`Hapus "${item.title}"?`)) return;
  try {
    await adminApi.schoolProfiles.delete(item.id);
    showToast('success', 'Berhasil!', 'Profil berhasil dihapus');
    loadData();
  } catch (e) {
    console.error('Delete error:', e);
    showToast('error', 'Gagal menghapus', e.message);
  }
};

const getImageUrl = (path) => {
  if (!path) return null;
  if (path.startsWith('http')) return path;
  return `http://localhost:8000/storage/${path}`;
};

onMounted(loadData);
</script>

<style scoped>
@import '../../assets/admin-common.css';
.modal-large { max-width: 800px; }
.image-preview { position: relative; display: inline-block; margin-top: 10px; }
.image-preview img { max-width: 200px; max-height: 150px; border-radius: 8px; object-fit: cover; }
.remove-image { position: absolute; top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; background: #ef4444; color: white; border: none; cursor: pointer; font-size: 14px; }
.form-hint { font-size: 0.8rem; color: #64748b; margin-top: 0.5rem; font-style: italic; }
.text-muted { color: #94a3b8; font-size: 0.75rem; }
.table-image { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; }
.no-image { font-size: 1.5rem; opacity: 0.5; }
</style>
