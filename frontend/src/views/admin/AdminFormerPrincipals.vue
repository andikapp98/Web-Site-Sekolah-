<template>
  <AdminLayout>
    <div class="admin-page">
      <div class="page-header">
        <h1>Kepala Sekolah Terdahulu</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Tambah Data
        </button>
      </div>
      
      <div class="table-card">
        <div class="table-loading" v-if="loading">
          <span class="loader"></span> Memuat data...
        </div>
        
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>Urutan</th>
              <th>Foto</th>
              <th>Nama</th>
              <th>Periode</th>
              <th>Tahun</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in items" :key="item.id">
              <td>{{ item.order }}</td>
              <td>
                <img v-if="item.photo" :src="getImageUrl(item.photo)" class="table-image" />
                <User v-else class="no-image-icon" :size="24" />
              </td>
              <td><strong>{{ item.name }}</strong></td>
              <td>Periode {{ item.period }}</td>
              <td>{{ item.years }}</td>
              <td class="actions">
                <button @click="openModal(item)" class="btn-icon btn-edit"><Pencil :size="16" /></button>
                <button @click="deleteItem(item)" class="btn-icon btn-delete"><Trash2 :size="16" /></button>
              </td>
            </tr>
            <tr v-if="items.length === 0 && !loading">
              <td colspan="6" class="empty-row">Belum ada data kepala sekolah terdahulu</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Modal Form -->
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} Data</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <div class="form-group">
              <label>Nama Lengkap *</label>
              <input v-model="form.name" type="text" required placeholder="Nama lengkap dengan gelar" />
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Periode (Angka)</label>
                <input v-model="form.period" type="text" placeholder="Contoh: 1" />
              </div>
              <div class="form-group">
                <label>Tahun Jabatan</label>
                <input v-model="form.years" type="text" placeholder="Contoh: 1999 - 2008" />
              </div>
            </div>

            <div class="form-group">
              <label>Urutan Tampilan</label>
              <input v-model.number="form.order" type="number" />
            </div>
            
            <div class="form-group">
              <label>Foto</label>
              <input type="file" @change="handleFileChange" accept="image/*" />
              <div v-if="imagePreview" class="image-preview">
                <img :src="imagePreview" alt="Preview" />
                <button type="button" @click="removeImage" class="remove-image">✕</button>
              </div>
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
import { User, Plus, Pencil, Trash2 } from 'lucide-vue-next';
import AdminLayout from '../../components/AdminLayout.vue';
import ToastNotification from '../../components/ToastNotification.vue';
import { adminApi } from '../../services/api';

const items = ref([]);
const loading = ref(true);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
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

const initialForm = {
  name: '',
  period: '',
  years: '',
  photo: null,
  order: 0
};

const form = ref({ ...initialForm });
const editingId = ref(null);

const loadData = async () => {
  loading.value = true;
  try {
    const response = await adminApi.formerPrincipals.list();
    items.value = response.data.data || response.data;
  } catch (error) {
    console.error('Error loading former principals:', error);
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
    imagePreview.value = item.photo ? getImageUrl(item.photo) : null;
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
    form.value.photo = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const removeImage = () => {
  form.value.photo = null;
  imagePreview.value = null;
};

const saveItem = async () => {
  saving.value = true;
  try {
    const formData = { ...form.value };
    if (formData.photo && !(formData.photo instanceof File)) {
      delete formData.photo;
    }

    if (isEditing.value) {
      await adminApi.formerPrincipals.update(editingId.value, formData);
      showToast('success', 'Berhasil!', 'Data berhasil diperbarui');
    } else {
      await adminApi.formerPrincipals.create(formData);
      showToast('success', 'Berhasil!', 'Data berhasil ditambahkan');
    }
    closeModal();
    loadData();
  } catch (error) {
    showToast('error', 'Gagal menyimpan', error.response?.data?.message || error.message);
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (item) => {
  if (!confirm(`Hapus data "${item.name}"?`)) return;
  try {
    await adminApi.formerPrincipals.delete(item.id);
    showToast('success', 'Berhasil!', 'Data berhasil dihapus');
    loadData();
  } catch (error) {
    showToast('error', 'Gagal menghapus', error.message);
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
.image-preview { position: relative; display: inline-block; margin-top: 10px; }
.image-preview img { max-width: 150px; max-height: 150px; border-radius: 8px; object-fit: cover; }
.remove-image { position: absolute; top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; background: #ef4444; color: white; border: none; cursor: pointer; font-size: 14px; }
</style>
