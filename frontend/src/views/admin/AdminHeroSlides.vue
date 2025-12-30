<template>
  <AdminLayout>
    <div class="admin-page">
      <div class="page-header">
        <h1>Hero Slides</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Tambah Slide
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
              <th>Deskripsi</th>
              <th>Urutan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td>
                <img v-if="item.image" :src="getImageUrl(item.image)" class="table-image slide-img" />
                <ImageIcon v-else class="no-image-icon" :size="24" />
              </td>
              <td>{{ item.title || '(Tanpa Judul)' }}</td>
              <td class="text-truncate">{{ item.description || '-' }}</td>
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
              <td colspan="7" class="empty-row">Belum ada hero slides</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} Hero Slide</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <div class="form-group">
              <label>Gambar Slide *</label>
              <input type="file" @change="e => form.image = e.target.files[0]" accept="image/*" :required="!isEditing" />
              <div v-if="form.image && typeof form.image === 'string'" class="image-preview slide-preview">
                <img :src="getImageUrl(form.image)" />
                <button type="button" @click="form.image = null" class="remove-image">✕</button>
              </div>
              <small class="form-hint">Ukuran optimal: 1920x600px</small>
            </div>
            
            <div class="form-group">
              <label>Judul (kosongkan untuk slide tanpa teks)</label>
              <input v-model="form.title" type="text" placeholder="Judul slide" />
            </div>
            
            <div class="form-group">
              <label>Deskripsi</label>
              <textarea v-model="form.description" rows="2" placeholder="Deskripsi singkat..."></textarea>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Teks Tombol</label>
                <input v-model="form.button_text" type="text" placeholder="Contoh: Selengkapnya" />
              </div>
              <div class="form-group">
                <label>Link Tombol</label>
                <input v-model="form.button_link" type="text" placeholder="#atau /halaman" />
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Urutan</label>
                <input v-model.number="form.order" type="number" min="0" />
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Image as ImageIcon, Plus, Pencil, Trash2 } from 'lucide-vue-next';
import AdminLayout from '../../components/AdminLayout.vue';
import { adminApi } from '../../services/api';

const items = ref([]);
const loading = ref(true);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const editingId = ref(null);

const initialForm = { title: '', description: '', image: null, button_text: '', button_link: '', order: 0, is_active: true };
const form = ref({ ...initialForm });

const loadData = async () => {
  loading.value = true;
  try { items.value = (await adminApi.heroSlides.list({ per_page: 100 })).data.data || []; }
  catch (e) { console.error(e); }
  finally { loading.value = false; }
};

const openModal = (item = null) => {
  isEditing.value = !!item;
  editingId.value = item?.id;
  form.value = item ? { ...item } : { ...initialForm };
  showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

const saveItem = async () => {
  saving.value = true;
  try {
    isEditing.value ? await adminApi.heroSlides.update(editingId.value, form.value) : await adminApi.heroSlides.create(form.value);
    closeModal(); loadData();
  } catch (e) { alert('Gagal: ' + (e.response?.data?.message || e.message)); }
  finally { saving.value = false; }
};

const deleteItem = async (item) => {
  if (!confirm('Hapus slide ini?')) return;
  try { await adminApi.heroSlides.delete(item.id); loadData(); } catch { alert('Gagal'); }
};

const getImageUrl = (path) => path?.startsWith('http') ? path : `http://localhost:8000/storage/${path}`;

onMounted(loadData);
</script>

<style scoped>
@import '../../assets/admin-common.css';
.slide-img { width: 120px; height: 60px; object-fit: cover; }
.slide-preview img { width: 200px; height: 100px; object-fit: cover; }
.form-hint { display: block; margin-top: 0.25rem; color: #94a3b8; font-size: 0.8rem; }
</style>
