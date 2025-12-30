<template>
  <AdminLayout>
    <div class="admin-page">
      <div class="page-header">
        <h1>Testimonial Alumni</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Tambah Testimonial
        </button>
      </div>
      
      <div class="table-card">
        <div class="table-loading" v-if="loading"><span class="loader"></span> Memuat...</div>
        
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Foto</th>
              <th>Nama</th>
              <th>Jurusan</th>
              <th>Testimoni</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td>
                <img v-if="item.photo" :src="getImageUrl(item.photo)" class="table-image" />
                <User v-else class="no-image-icon" :size="24" />
              </td>
              <td><strong>{{ item.name }}</strong></td>
              <td>{{ item.jurusan }}</td>
              <td class="text-truncate">"{{ item.quote }}"</td>
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
              <td colspan="7" class="empty-row">Belum ada testimonial</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} Testimonial</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <div class="form-group">
              <label>Nama Alumni *</label>
              <input v-model="form.name" type="text" required placeholder="Nama lengkap" />
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Jurusan *</label>
                <input v-model="form.jurusan" type="text" required placeholder="Contoh: ALUMNI TPM" />
              </div>
              <div class="form-group">
                <label>Tahun Lulus</label>
                <input v-model="form.graduation_year" type="text" placeholder="2020" />
              </div>
            </div>
            
            <div class="form-group">
              <label>Testimoni *</label>
              <textarea v-model="form.quote" required rows="4" placeholder="Testimoni dari alumni..."></textarea>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Pekerjaan Saat Ini</label>
                <input v-model="form.current_position" type="text" placeholder="Jabatan" />
              </div>
              <div class="form-group">
                <label>Perusahaan</label>
                <input v-model="form.company" type="text" placeholder="Nama perusahaan" />
              </div>
            </div>
            
            <div class="form-group">
              <label>Foto</label>
              <input type="file" @change="e => form.photo = e.target.files[0]" accept="image/*" />
              <div v-if="form.photo && typeof form.photo === 'string'" class="image-preview">
                <img :src="getImageUrl(form.photo)" />
                <button type="button" @click="form.photo = null" class="remove-image">✕</button>
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
import { User, Plus, Pencil, Trash2 } from 'lucide-vue-next';
import AdminLayout from '../../components/AdminLayout.vue';
import { adminApi } from '../../services/api';

const items = ref([]);
const loading = ref(true);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const editingId = ref(null);

const initialForm = { name: '', jurusan: '', graduation_year: '', photo: null, quote: '', current_position: '', company: '', order: 0, is_active: true };
const form = ref({ ...initialForm });

const loadData = async () => {
  loading.value = true;
  try { items.value = (await adminApi.testimonials.list({ per_page: 100 })).data.data || []; }
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
    isEditing.value ? await adminApi.testimonials.update(editingId.value, form.value) : await adminApi.testimonials.create(form.value);
    closeModal(); loadData();
  } catch (e) { alert('Gagal: ' + (e.response?.data?.message || e.message)); }
  finally { saving.value = false; }
};

const deleteItem = async (item) => {
  if (!confirm(`Hapus testimonial "${item.name}"?`)) return;
  try { await adminApi.testimonials.delete(item.id); loadData(); } catch { alert('Gagal'); }
};

const getImageUrl = (path) => path?.startsWith('http') ? path : `http://localhost:8000/storage/${path}`;

onMounted(loadData);
</script>

<style scoped>@import '../../assets/admin-common.css';</style>
