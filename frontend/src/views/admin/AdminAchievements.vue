<template>
  <AdminLayout>
    <div class="admin-page">
      <div class="page-header">
        <h1>Jejak Gemilang / Prestasi</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Tambah Prestasi
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
              <th>Kategori</th>
              <th>Featured</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td>
                <img v-if="item.image" :src="getImageUrl(item.image)" class="table-image" />
                <Trophy v-else class="no-image-icon" :size="24" />
              </td>
              <td><strong class="text-truncate">{{ item.title }}</strong></td>
              <td><span class="category-badge" :class="item.category?.toLowerCase()">{{ item.category }}</span></td>
              <td><Star v-if="item.is_featured" :size="16" class="text-yellow" /><span v-else>-</span></td>
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
              <td colspan="7" class="empty-row">Belum ada data prestasi</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Modal Form -->
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} Prestasi</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <div class="form-group">
              <label>Judul Prestasi *</label>
              <input v-model="form.title" type="text" required placeholder="Judul prestasi/berita" />
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Kategori</label>
                <select v-model="form.category">
                  <option value="KEGIATAN">KEGIATAN</option>
                  <option value="LOMBA">LOMBA</option>
                  <option value="PRESTASI">PRESTASI</option>
                  <option value="AKADEMIK">AKADEMIK</option>
                </select>
              </div>
              <div class="form-group">
                <label>Tanggal Event</label>
                <input v-model="form.event_date" type="date" />
              </div>
            </div>
            
            <div class="form-group">
              <label>Ringkasan</label>
              <textarea v-model="form.excerpt" rows="2" placeholder="Ringkasan singkat..."></textarea>
            </div>
            
            <div class="form-group">
              <label>Konten Lengkap</label>
              <textarea v-model="form.content" rows="5" placeholder="Konten lengkap berita..."></textarea>
            </div>
            
            <div class="form-group">
              <label>Gambar</label>
              <input type="file" @change="handleFileChange" accept="image/*" />
              <div v-if="imagePreview" class="image-preview">
                <img :src="imagePreview" />
                <button type="button" @click="removeImage" class="remove-image">✕</button>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label class="checkbox-label">
                  <input type="checkbox" v-model="form.is_featured" />
                  <span>Featured (Tampil Utama)</span>
                </label>
              </div>
              <div class="form-group">
                <label class="checkbox-label">
                  <input type="checkbox" v-model="form.is_active" />
                  <span>Aktif</span>
                </label>
              </div>
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
import { Trophy, Plus, Pencil, Trash2, Star } from 'lucide-vue-next';
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

const initialForm = { title: '', excerpt: '', content: '', category: 'KEGIATAN', image: null, event_date: '', is_featured: false, is_active: true };
const form = ref({ ...initialForm });

const loadData = async () => {
  loading.value = true;
  try {
    const response = await adminApi.achievements.list({ per_page: 100 });
    items.value = response.data.data || response.data || [];
  } catch (e) {
    console.error(e);
    showToast('error', 'Gagal memuat data', e.message);
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

const handleFileChange = (e) => {
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
    if (formData.image && !(formData.image instanceof File)) {
      delete formData.image;
    }

    if (isEditing.value) {
      await adminApi.achievements.update(editingId.value, formData);
      showToast('success', 'Berhasil!', 'Prestasi berhasil diperbarui');
    } else {
      await adminApi.achievements.create(formData);
      showToast('success', 'Berhasil!', 'Prestasi berhasil ditambahkan');
    }
    closeModal();
    loadData();
  } catch (e) {
    const msg = e.response?.data?.message || e.message;
    showToast('error', 'Gagal menyimpan', msg);
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (item) => {
  if (!confirm(`Hapus "${item.title}"?`)) return;
  try {
    await adminApi.achievements.delete(item.id);
    showToast('success', 'Berhasil!', 'Prestasi berhasil dihapus');
    loadData();
  } catch (e) {
    showToast('error', 'Gagal menghapus', e.message);
  }
};

const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http')) return path;
  return `http://localhost:8000/storage/${path}`;
};

onMounted(loadData);
</script>

<style scoped>
@import '../../assets/admin-common.css';
.category-badge { 
  display: inline-block;
  padding: 0.25rem 0.5rem; 
  border-radius: 4px; 
  font-size: 0.7rem; 
  font-weight: 600; 
  background: #e2e8f0;
  color: #475569;
  text-transform: uppercase;
}
.category-badge.prestasi { background: #fef3c7; color: #92400e; }
.category-badge.kegiatan { background: #dbeafe; color: #1e40af; }
.category-badge.lomba { background: #e0f2fe; color: #075985; }
.category-badge.akademik { background: #f3e8ff; color: #6b21a8; }
.image-preview { position: relative; display: inline-block; margin-top: 10px; }
.image-preview img { max-width: 200px; max-height: 150px; border-radius: 8px; }
.remove-image { position: absolute; top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; background: #ef4444; color: white; border: none; cursor: pointer; font-size: 14px; }
</style>
