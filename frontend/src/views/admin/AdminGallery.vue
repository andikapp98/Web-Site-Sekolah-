<template>
  <AdminLayout>
    <div class="admin-page">
      <div class="page-header">
        <h1>Galeri Media</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Tambah Media
        </button>
      </div>

      <!-- Filter Tabs -->
      <div class="filter-tabs">
        <button 
          v-for="tab in tabs" 
          :key="tab.value"
          :class="['filter-tab', { active: activeTab === tab.value }]"
          @click="activeTab = tab.value"
        >
          <component :is="tab.icon" :size="16" />
          {{ tab.label }}
        </button>
      </div>
      
      <div class="table-card">
        <div class="table-loading" v-if="loading"><span class="loader"></span> Memuat...</div>
        
        <div v-else class="gallery-grid">
          <div 
            v-for="(item, index) in filteredItems" 
            :key="item.id" 
            class="gallery-item"
            :class="item.type"
          >
            <div class="gallery-preview">
              <!-- Photo -->
              <img v-if="item.type === 'photo' && item.image" :src="getImageUrl(item.image)" />
              
              <!-- Reels -->
              <div v-else-if="item.type === 'reels'" class="reels-preview">
                <img v-if="item.thumbnail" :src="getImageUrl(item.thumbnail)" />
                <div v-else class="reels-placeholder">
                  <Film :size="32" />
                </div>
                <div class="play-icon"><Play :size="24" /></div>
              </div>
              
              <!-- YouTube -->
              <div v-else-if="item.type === 'youtube'" class="youtube-preview">
                <img :src="getYoutubeThumbnail(item.video_url)" />
                <div class="play-icon youtube"><Youtube :size="24" /></div>
              </div>
              
              <div v-else class="no-preview">
                <ImageIcon :size="32" />
              </div>
            </div>
            
            <div class="gallery-info">
              <span class="type-badge" :class="item.type">{{ getTypeLabel(item.type) }}</span>
              <h4>{{ item.title }}</h4>
              <span class="status-badge" :class="item.is_active ? 'active' : 'inactive'">
                {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </div>
            
            <div class="gallery-actions">
              <button @click="openModal(item)" class="btn-icon btn-edit"><Pencil :size="16" /></button>
              <button @click="deleteItem(item)" class="btn-icon btn-delete"><Trash2 :size="16" /></button>
            </div>
          </div>
          
          <div v-if="filteredItems.length === 0 && !loading" class="empty-state">
            <ImageIcon :size="48" />
            <p>Belum ada media untuk kategori ini</p>
          </div>
        </div>
      </div>
      
      <!-- Modal Form -->
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} Media</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <div class="form-row">
              <div class="form-group">
                <label>Jenis Media *</label>
                <select v-model="form.type" required>
                  <option value="photo">📷 Foto</option>
                  <option value="reels">🎬 Reels Instagram</option>
                  <option value="youtube">▶️ Video YouTube</option>
                </select>
              </div>
              <div class="form-group">
                <label class="checkbox-label" style="margin-top: 1.5rem;">
                  <input type="checkbox" v-model="form.is_active" />
                  <span>Aktif</span>
                </label>
              </div>
            </div>
            
            <div class="form-group">
              <label>Judul *</label>
              <input v-model="form.title" type="text" required placeholder="Judul media" />
            </div>
            
            <div class="form-group">
              <label>Deskripsi</label>
              <textarea v-model="form.description" rows="2" placeholder="Deskripsi singkat..."></textarea>
            </div>
            
            <!-- Photo Upload -->
            <div v-if="form.type === 'photo'" class="form-group">
              <label>Gambar *</label>
              <input type="file" @change="handleImageChange" accept="image/*" />
              <div v-if="imagePreview" class="image-preview">
                <img :src="imagePreview" />
                <button type="button" @click="removeImage" class="remove-image">✕</button>
              </div>
            </div>
            
            <!-- Video URL for Reels/YouTube -->
            <div v-if="form.type === 'reels' || form.type === 'youtube'" class="form-group">
              <label>{{ form.type === 'reels' ? 'URL Reels Instagram' : 'URL Video YouTube' }} *</label>
              <input 
                v-model="form.video_url" 
                type="url" 
                :placeholder="form.type === 'reels' ? 'https://www.instagram.com/reel/...' : 'https://www.youtube.com/watch?v=...'" 
                required
              />
            </div>
            
            <!-- Thumbnail for Reels -->
            <div v-if="form.type === 'reels'" class="form-group">
              <label>Thumbnail (Opsional)</label>
              <input type="file" @change="handleThumbnailChange" accept="image/*" />
              <div v-if="thumbnailPreview" class="image-preview">
                <img :src="thumbnailPreview" />
                <button type="button" @click="removeThumbnail" class="remove-image">✕</button>
              </div>
            </div>
            
            <div class="form-group">
              <label>Urutan</label>
              <input v-model.number="form.order" type="number" min="0" placeholder="0" />
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
import { ref, reactive, computed, onMounted } from 'vue';
import { Plus, Pencil, Trash2, Image as ImageIcon, Film, Play, Youtube } from 'lucide-vue-next';
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
const thumbnailPreview = ref(null);
const activeTab = ref('all');

const tabs = [
  { value: 'all', label: 'Semua', icon: ImageIcon },
  { value: 'photo', label: 'Foto', icon: ImageIcon },
  { value: 'reels', label: 'Reels', icon: Film },
  { value: 'youtube', label: 'YouTube', icon: Youtube },
];

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
  title: '', 
  description: '', 
  type: 'photo', 
  image: null, 
  video_url: '', 
  thumbnail: null, 
  order: 0, 
  is_active: true 
};
const form = ref({ ...initialForm });

const filteredItems = computed(() => {
  if (activeTab.value === 'all') return items.value;
  return items.value.filter(item => item.type === activeTab.value);
});

const loadData = async () => {
  loading.value = true;
  try {
    const response = await adminApi.galleries.list({ per_page: 100 });
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
    thumbnailPreview.value = item.thumbnail ? getImageUrl(item.thumbnail) : null;
  } else {
    form.value = { ...initialForm };
    imagePreview.value = null;
    thumbnailPreview.value = null;
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  imagePreview.value = null;
  thumbnailPreview.value = null;
};

const handleImageChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.value.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

const handleThumbnailChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.value.thumbnail = file;
    thumbnailPreview.value = URL.createObjectURL(file);
  }
};

const removeImage = () => {
  form.value.image = null;
  imagePreview.value = null;
};

const removeThumbnail = () => {
  form.value.thumbnail = null;
  thumbnailPreview.value = null;
};

const saveItem = async () => {
  saving.value = true;
  try {
    const formData = { ...form.value };
    if (formData.image && !(formData.image instanceof File)) {
      delete formData.image;
    }
    if (formData.thumbnail && !(formData.thumbnail instanceof File)) {
      delete formData.thumbnail;
    }

    if (isEditing.value) {
      await adminApi.galleries.update(editingId.value, formData);
      showToast('success', 'Berhasil!', 'Media berhasil diperbarui');
    } else {
      await adminApi.galleries.create(formData);
      showToast('success', 'Berhasil!', 'Media berhasil ditambahkan');
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
    await adminApi.galleries.delete(item.id);
    showToast('success', 'Berhasil!', 'Media berhasil dihapus');
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

const getYoutubeThumbnail = (url) => {
  if (!url) return '';
  const match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
  if (match && match[1]) {
    return `https://img.youtube.com/vi/${match[1]}/mqdefault.jpg`;
  }
  return '';
};

const getTypeLabel = (type) => {
  const labels = { photo: 'Foto', reels: 'Reels', youtube: 'YouTube' };
  return labels[type] || type;
};

onMounted(loadData);
</script>

<style scoped>
@import '../../assets/admin-common.css';

.filter-tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
}

.filter-tab {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.2rem;
  background: #f1f5f9;
  border: none;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 500;
  color: #64748b;
  cursor: pointer;
  transition: all 0.2s;
}

.filter-tab:hover {
  background: #e2e8f0;
}

.filter-tab.active {
  background: #155d27;
  color: #fff;
}

.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
  padding: 1rem 0;
}

.gallery-item {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: all 0.3s;
  position: relative;
}

.gallery-item:hover {
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  transform: translateY(-4px);
}

.gallery-preview {
  aspect-ratio: 16/9;
  background: #f1f5f9;
  position: relative;
  overflow: hidden;
}

.gallery-item.reels .gallery-preview {
  aspect-ratio: 9/16;
  max-height: 300px;
}

.gallery-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.reels-preview, .youtube-preview {
  position: relative;
  width: 100%;
  height: 100%;
}

.reels-placeholder, .no-preview {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.play-icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 50px;
  height: 50px;
  background: rgba(0,0,0,0.6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
}

.play-icon.youtube {
  background: #ff0000;
}

.gallery-info {
  padding: 1rem;
}

.type-badge {
  display: inline-block;
  padding: 0.2rem 0.6rem;
  border-radius: 4px;
  font-size: 0.7rem;
  font-weight: 600;
  text-transform: uppercase;
  margin-bottom: 0.5rem;
}

.type-badge.photo { background: #dcfce7; color: #166534; }
.type-badge.reels { background: #fce7f3; color: #9d174d; }
.type-badge.youtube { background: #fee2e2; color: #991b1b; }

.gallery-info h4 {
  font-size: 0.95rem;
  font-weight: 600;
  color: #1e293b;
  margin-bottom: 0.5rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.gallery-actions {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  display: flex;
  gap: 0.5rem;
  opacity: 0;
  transition: opacity 0.2s;
}

.gallery-item:hover .gallery-actions {
  opacity: 1;
}

.empty-state {
  grid-column: 1 / -1;
  text-align: center;
  padding: 4rem 2rem;
  color: #94a3b8;
}

.empty-state p {
  margin-top: 1rem;
}

.image-preview {
  position: relative;
  display: inline-block;
  margin-top: 10px;
}

.image-preview img {
  max-width: 200px;
  max-height: 150px;
  border-radius: 8px;
}

.remove-image {
  position: absolute;
  top: -8px;
  right: -8px;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #ef4444;
  color: white;
  border: none;
  cursor: pointer;
  font-size: 14px;
}

@media (max-width: 640px) {
  .gallery-grid {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  }
}
</style>
