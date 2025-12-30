<template>
  <AdminLayout>
    <div class="admin-page">
      <div class="page-header">
        <h1>Artikel & Berita</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Buat Artikel
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
              <th>Views</th>
              <th>Publikasi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td>
                <img v-if="item.image" :src="getImageUrl(item.image)" class="table-image" />
                <Newspaper v-else class="no-image-icon" :size="24" />
              </td>
              <td><strong class="text-truncate">{{ item.title }}</strong></td>
              <td><span class="category-badge" :class="item.category?.toLowerCase()">{{ item.category }}</span></td>
              <td>{{ item.views || 0 }}</td>
              <td>
                <span class="status-badge" :class="item.is_published ? 'active' : 'inactive'">
                  {{ item.is_published ? 'Published' : 'Draft' }}
                </span>
              </td>
              <td class="actions">
                <button @click="openModal(item)" class="btn-icon btn-edit"><Pencil :size="16" /></button>
                <button @click="deleteItem(item)" class="btn-icon btn-delete"><Trash2 :size="16" /></button>
              </td>
            </tr>
            <tr v-if="items.length === 0 && !loading">
              <td colspan="7" class="empty-row">Belum ada artikel</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content modal-large">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Buat' }} Artikel</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <div class="form-group">
              <label>Judul Artikel *</label>
              <input v-model="form.title" type="text" required placeholder="Judul artikel" />
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Kategori</label>
                <select v-model="form.category">
                  <option value="Berita">Berita</option>
                  <option value="Pengumuman">Pengumuman</option>
                  <option value="Kegiatan">Kegiatan</option>
                  <option value="Prestasi">Prestasi</option>
                </select>
              </div>
              <div class="form-group">
                <label class="checkbox-label" style="margin-top: 1.5rem;">
                  <input type="checkbox" v-model="form.is_published" />
                  <span>Publikasikan</span>
                </label>
              </div>
            </div>
            
            <div class="form-group">
              <label>Ringkasan</label>
              <textarea v-model="form.excerpt" rows="2" placeholder="Ringkasan singkat..."></textarea>
            </div>
            
            <div class="form-group">
              <label>Konten Artikel *</label>
              <textarea v-model="form.content" required rows="10" placeholder="Isi artikel lengkap..."></textarea>
            </div>
            
            <div class="form-group">
              <label>Gambar</label>
              <input type="file" @change="e => form.image = e.target.files[0]" accept="image/*" />
              <div v-if="form.image && typeof form.image === 'string'" class="image-preview">
                <img :src="getImageUrl(form.image)" />
                <button type="button" @click="form.image = null" class="remove-image">✕</button>
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
import { Newspaper, Plus, Pencil, Trash2 } from 'lucide-vue-next';
import AdminLayout from '../../components/AdminLayout.vue';
import { adminApi } from '../../services/api';

const items = ref([]);
const loading = ref(true);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const editingId = ref(null);

const initialForm = { title: '', excerpt: '', content: '', category: 'Berita', image: null, is_published: false };
const form = ref({ ...initialForm });

const loadData = async () => {
  loading.value = true;
  try { items.value = (await adminApi.articles.list({ per_page: 100 })).data.data || []; }
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
    isEditing.value ? await adminApi.articles.update(editingId.value, form.value) : await adminApi.articles.create(form.value);
    closeModal(); loadData();
  } catch (e) { alert('Gagal: ' + (e.response?.data?.message || e.message)); }
  finally { saving.value = false; }
};

const deleteItem = async (item) => {
  if (!confirm(`Hapus artikel "${item.title}"?`)) return;
  try { await adminApi.articles.delete(item.id); loadData(); } catch { alert('Gagal'); }
};

const getImageUrl = (path) => path?.startsWith('http') ? path : `http://localhost:8000/storage/${path}`;

onMounted(loadData);
</script>

<style scoped>
@import '../../assets/admin-common.css';
.modal-large { max-width: 800px; }
.category-badge {
  padding: 0.2rem 0.6rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
}
.category-badge.prestasi { background: #fef3c7; color: #92400e; }
.category-badge.kegiatan { background: #dbeafe; color: #1e40af; }
.category-badge.pengumuman { background: #fee2e2; color: #991b1b; }
.category-badge.berita { background: #e0f2fe; color: #075985; }
</style>
