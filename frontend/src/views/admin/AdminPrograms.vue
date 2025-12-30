<template>
  <AdminLayout>
    <div class="admin-page">
      <!-- Header -->
      <div class="page-header">
        <h1>Program Keahlian / Jurusan</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Tambah Program
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
              <th>Gambar</th>
              <th>Nama Program</th>
              <th>Singkatan</th>
              <th>Deskripsi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td>
                <img v-if="item.image" :src="getImageUrl(item.image)" class="table-image" />
                <Image v-else class="no-image-icon" :size="24" />
              </td>
              <td><strong>{{ item.name }}</strong></td>
              <td>{{ item.short_name || '-' }}</td>
              <td class="text-truncate">{{ item.description }}</td>
              <td>
                <span class="status-badge" :class="item.is_active ? 'active' : 'inactive'">
                  {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="actions">
                <button @click="openModal(item)" class="btn-icon btn-edit" title="Edit"><Pencil :size="16" /></button>
                <button @click="deleteItem(item)" class="btn-icon btn-delete" title="Hapus"><Trash2 :size="16" /></button>
              </td>
            </tr>
            <tr v-if="items.length === 0 && !loading">
              <td colspan="7" class="empty-row">Belum ada data program keahlian</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Modal Form -->
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content modal-large">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} Program Keahlian</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          
          <!-- Tabs -->
          <div class="modal-tabs">
            <button 
              :class="['tab-btn', { active: activeTab === 'basic' }]"
              @click="activeTab = 'basic'"
            >
              Informasi Dasar
            </button>
            <button 
              :class="['tab-btn', { active: activeTab === 'detail' }]"
              @click="activeTab = 'detail'"
            >
              Detail Jurusan
            </button>
            <button 
              :class="['tab-btn', { active: activeTab === 'media' }]"
              @click="activeTab = 'media'"
            >
              Media
            </button>
          </div>
          
          <form @submit.prevent="saveItem" class="modal-form">
            <!-- Tab: Informasi Dasar -->
            <div v-show="activeTab === 'basic'" class="tab-content">
              <div class="form-group">
                <label>Nama Program *</label>
                <input v-model="form.name" type="text" required placeholder="Contoh: Teknik Pemesinan" />
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label>Singkatan</label>
                  <input v-model="form.short_name" type="text" placeholder="Contoh: TPM" />
                </div>
                <div class="form-group">
                  <label>Urutan</label>
                  <input v-model.number="form.order" type="number" min="0" placeholder="0" />
                </div>
              </div>
              
              <div class="form-group">
                <label>Deskripsi Singkat *</label>
                <textarea v-model="form.description" required rows="3" placeholder="Deskripsi singkat program..."></textarea>
              </div>
              
              <div class="form-group">
                <label>Deskripsi Lengkap</label>
                <textarea v-model="form.full_description" rows="5" placeholder="Deskripsi lengkap program..."></textarea>
              </div>
              
              <div class="form-group">
                <label class="checkbox-label">
                  <input type="checkbox" v-model="form.is_active" />
                  <span>Aktif (tampilkan di website)</span>
                </label>
              </div>
            </div>
            
            <!-- Tab: Detail Jurusan -->
            <div v-show="activeTab === 'detail'" class="tab-content">
              <div class="form-group">
                <label>Peluang Karir</label>
                <textarea v-model="form.career_opportunities" rows="5" placeholder="Deskripsi peluang karir bagi lulusan..."></textarea>
              </div>
              
              <div class="form-group">
                <label>Kompetensi Lulusan</label>
                <div class="helper-text">Masukkan satu kompetensi per baris</div>
                <textarea 
                  v-model="competenciesText" 
                  rows="5" 
                  placeholder="Gambar Teknik Manufaktur&#10;Teknik Pemesinan Bubut & Frais&#10;Teknik Pemesinan CNC"
                ></textarea>
              </div>
              
              <div class="form-group">
                <label>Ruang Praktek</label>
                <div class="helper-text">Masukkan satu ruang per baris</div>
                <textarea 
                  v-model="facilitiesText" 
                  rows="5" 
                  placeholder="Ruang Praktek Kerja Bangku&#10;Ruang Praktek Mesin CNC&#10;Ruang Praktek Gambar"
                ></textarea>
              </div>
              
              <div class="form-group">
                <label>Alat Praktek</label>
                <div class="helper-text">Masukkan satu alat per baris</div>
                <textarea 
                  v-model="equipmentText" 
                  rows="5" 
                  placeholder="Mesin Bubut Konvensional&#10;Mesin Frais CNC&#10;Mesin Gerinda"
                ></textarea>
              </div>
            </div>
            
            <!-- Tab: Media -->
            <div v-show="activeTab === 'media'" class="tab-content">
              <div class="form-group">
                <label>Gambar Utama (Thumbnail)</label>
                <input type="file" @change="handleFileChange($event, 'image')" accept="image/*" />
                <div v-if="form.image && typeof form.image === 'string'" class="image-preview">
                  <img :src="getImageUrl(form.image)" alt="Preview" />
                  <button type="button" @click="form.image = null" class="remove-image">✕</button>
                </div>
              </div>

              <div class="form-group">
                <label>Gambar Karir (untuk card Peluang Karir)</label>
                <input type="file" @change="handleFileChange($event, 'career_image')" accept="image/*" />
                <div v-if="form.career_image && typeof form.career_image === 'string'" class="image-preview">
                  <img :src="getImageUrl(form.career_image)" alt="Preview" />
                  <button type="button" @click="form.career_image = null" class="remove-image">✕</button>
                </div>
              </div>

              <div class="form-group">
                <label>Gambar Workshop (untuk card Praktek)</label>
                <input type="file" @change="handleFileChange($event, 'workshop_image')" accept="image/*" />
                <div v-if="form.workshop_image && typeof form.workshop_image === 'string'" class="image-preview">
                  <img :src="getImageUrl(form.workshop_image)" alt="Preview" />
                  <button type="button" @click="form.workshop_image = null" class="remove-image">✕</button>
                </div>
              </div>

              <div class="form-group">
                <label>Video Hero (Opsional, Max 10MB)</label>
                <div class="helper-text">Format: MP4, GIF. Jika diisi, akan menggantikan gambar di header.</div>
                <input type="file" @change="handleVideoChange" accept="video/mp4,image/gif" />
                <div v-if="form.video && typeof form.video === 'string'" class="video-preview-info">
                  <span>Video saat ini: {{ form.video }}</span>
                  <button type="button" @click="form.video = null" class="remove-image">✕</button>
                </div>
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { Image, Plus, Pencil, Trash2, Video } from 'lucide-vue-next';
import AdminLayout from '../../components/AdminLayout.vue';
import { adminApi } from '../../services/api';

const items = ref([]);
const loading = ref(true);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const activeTab = ref('basic');

const initialForm = {
  name: '',
  short_name: '',
  description: '',
  full_description: '',
  career_opportunities: '',
  competencies: [],
  facilities: [],
  equipment: [],
  image: null,
  career_image: null,
  workshop_image: null,
  video: null,
  order: 0,
  is_active: true,
};

const form = ref({ ...initialForm });
const editingId = ref(null);

// Computed untuk convert array ke text dan sebaliknya
const competenciesText = computed({
  get: () => (form.value.competencies || []).join('\n'),
  set: (val) => { form.value.competencies = val.split('\n').filter(v => v.trim()); }
});

const facilitiesText = computed({
  get: () => (form.value.facilities || []).join('\n'),
  set: (val) => { form.value.facilities = val.split('\n').filter(v => v.trim()); }
});

const equipmentText = computed({
  get: () => (form.value.equipment || []).join('\n'),
  set: (val) => { form.value.equipment = val.split('\n').filter(v => v.trim()); }
});

const loadData = async () => {
  loading.value = true;
  try {
    const response = await adminApi.programs.list({ per_page: 100 });
    items.value = response.data.data || response.data;
  } catch (error) {
    console.error('Error loading programs:', error);
    alert('Gagal memuat data program');
  } finally {
    loading.value = false;
  }
};

const openModal = (item = null) => {
  activeTab.value = 'basic';
  if (item) {
    isEditing.value = true;
    editingId.value = item.id;
    form.value = { 
      ...item,
      competencies: item.competencies || [],
      facilities: item.facilities || [],
      equipment: item.equipment || [],
    };
  } else {
    isEditing.value = false;
    editingId.value = null;
    form.value = { ...initialForm };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  form.value = { ...initialForm };
};

const handleFileChange = (e, field) => {
  const file = e.target.files[0];
  if (file) {
    form.value[field] = file;
  }
};

const handleVideoChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    if (file.size > 10 * 1024 * 1024) {
      alert('Ukuran file video maksimal 10MB!');
      e.target.value = '';
      return;
    }
    form.value.video = file;
  }
};

const saveItem = async () => {
  saving.value = true;
  try {
    if (isEditing.value) {
      await adminApi.programs.update(editingId.value, form.value);
      alert('Program berhasil diperbarui');
    } else {
      await adminApi.programs.create(form.value);
      alert('Program berhasil ditambahkan');
    }
    closeModal();
    loadData();
  } catch (error) {
    console.error('Error saving program:', error);
    alert('Gagal menyimpan program: ' + (error.response?.data?.message || error.message));
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (item) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus "${item.name}"?`)) return;
  
  try {
    await adminApi.programs.delete(item.id);
    alert('Program berhasil dihapus');
    loadData();
  } catch (error) {
    console.error('Error deleting program:', error);
    alert('Gagal menghapus program');
  }
};

const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http')) return path;
  return `http://localhost:8000/storage/${path}`;
};

onMounted(() => {
  loadData();
});
</script>

<style scoped>
@import '../../assets/admin-common.css';

.modal-large {
  max-width: 800px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-tabs {
  display: flex;
  gap: 0.5rem;
  padding: 0 1.5rem;
  border-bottom: 1px solid #e2e8f0;
  margin-bottom: 1rem;
}

.tab-btn {
  padding: 0.75rem 1.5rem;
  background: none;
  border: none;
  border-bottom: 3px solid transparent;
  color: #64748b;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.tab-btn:hover {
  color: #155d27;
}

.tab-btn.active {
  color: #155d27;
  border-bottom-color: #155d27;
}

.tab-content {
  padding: 0 1.5rem;
}

.video-preview-info {
  margin-top: 0.5rem;
  font-size: 0.85rem;
  color: #64748b;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: #f1f5f9;
  padding: 0.5rem;
  border-radius: 4px;
}

.helper-text {
  font-size: 0.8rem;
  color: #64748b;
  margin-bottom: 0.5rem;
}
</style>
