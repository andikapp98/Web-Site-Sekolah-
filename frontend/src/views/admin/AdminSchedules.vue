<template>
  <AdminLayout>
    <div class="admin-page">
      <div class="page-header">
        <h1>Jadwal Pembelajaran</h1>
        <button @click="openModal()" class="btn-primary">
          <Plus :size="18" /> Tambah Jadwal
        </button>
      </div>
      
      <!-- Filters -->
      <div class="filter-card">
        <div class="filter-group">
          <label>Kelas:</label>
          <select v-model="filters.class_name" @change="loadData">
            <option value="">Semua Kelas</option>
            <option v-for="c in classes" :key="c">{{ c }}</option>
          </select>
        </div>
        <div class="filter-group">
          <label>Hari:</label>
          <select v-model="filters.day" @change="loadData">
            <option value="">Semua Hari</option>
            <option v-for="d in days" :key="d">{{ d }}</option>
          </select>
        </div>
      </div>
      
      <div class="table-card">
        <div class="table-loading" v-if="loading"><span class="loader"></span> Memuat...</div>
        
        <table v-else class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Hari</th>
              <th>Jam</th>
              <th>Kelas</th>
              <th>Mata Pelajaran</th>
              <th>Guru</th>
              <th>Ruangan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in items" :key="item.id">
              <td>{{ index + 1 }}</td>
              <td><span class="day-badge">{{ item.day }}</span></td>
              <td>{{ item.start_time }} - {{ item.end_time }}</td>
              <td><strong>{{ item.class_name }}</strong></td>
              <td>{{ item.subject }}</td>
              <td>{{ item.teacher?.name || '-' }}</td>
              <td>{{ item.room || '-' }}</td>
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
              <td colspan="9" class="empty-row">Belum ada jadwal</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>{{ isEditing ? 'Edit' : 'Tambah' }} Jadwal</h2>
            <button @click="closeModal" class="modal-close">&times;</button>
          </div>
          <form @submit.prevent="saveItem" class="modal-form">
            <div class="form-row">
              <div class="form-group">
                <label>Kelas *</label>
                <input v-model="form.class_name" type="text" required placeholder="Contoh: X TPM 1" />
              </div>
              <div class="form-group">
                <label>Hari *</label>
                <select v-model="form.day" required>
                  <option v-for="d in days" :key="d" :value="d">{{ d }}</option>
                </select>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Jam Mulai *</label>
                <input v-model="form.start_time" type="time" required />
              </div>
              <div class="form-group">
                <label>Jam Selesai *</label>
                <input v-model="form.end_time" type="time" required />
              </div>
            </div>
            
            <div class="form-group">
              <label>Mata Pelajaran *</label>
              <input v-model="form.subject" type="text" required placeholder="Nama mata pelajaran" />
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Guru</label>
                <select v-model="form.teacher_id">
                  <option value="">Pilih Guru</option>
                  <option v-for="t in teachers" :key="t.id" :value="t.id">{{ t.name }}</option>
                </select>
              </div>
              <div class="form-group">
                <label>Ruangan</label>
                <input v-model="form.room" type="text" placeholder="Contoh: Lab Komputer 1" />
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>Semester</label>
                <select v-model="form.semester">
                  <option value="">Pilih</option>
                  <option value="Ganjil">Ganjil</option>
                  <option value="Genap">Genap</option>
                </select>
              </div>
              <div class="form-group">
                <label>Tahun Ajaran</label>
                <input v-model="form.academic_year" type="text" placeholder="2024/2025" />
              </div>
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';
import AdminLayout from '../../components/AdminLayout.vue';
import { adminApi } from '../../services/api';

const items = ref([]);
const teachers = ref([]);
const classes = ref([]);
const loading = ref(true);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const editingId = ref(null);

const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const filters = ref({ class_name: '', day: '' });

const initialForm = { class_name: '', day: 'Senin', start_time: '07:00', end_time: '08:30', subject: '', teacher_id: '', room: '', semester: 'Ganjil', academic_year: '2024/2025', is_active: true };
const form = ref({ ...initialForm });

const loadData = async () => {
  loading.value = true;
  try {
    const params = { per_page: 100 };
    if (filters.value.class_name) params.class_name = filters.value.class_name;
    if (filters.value.day) params.day = filters.value.day;
    
    const response = await adminApi.schedules.list(params);
    items.value = response.data.data || [];
    
    // Extract unique classes
    const allClasses = new Set(items.value.map(i => i.class_name));
    classes.value = [...allClasses].sort();
  } catch (e) { console.error(e); }
  finally { loading.value = false; }
};

const loadTeachers = async () => {
  try {
    const response = await adminApi.teachers.list({ per_page: 100, active_only: true });
    teachers.value = response.data.data || [];
  } catch (e) { console.error(e); }
};

const openModal = (item = null) => {
  isEditing.value = !!item;
  editingId.value = item?.id;
  form.value = item ? { ...item, teacher_id: item.teacher_id || '' } : { ...initialForm };
  showModal.value = true;
};

const closeModal = () => { showModal.value = false; };

const saveItem = async () => {
  saving.value = true;
  try {
    const data = { ...form.value };
    if (!data.teacher_id) delete data.teacher_id;
    
    isEditing.value ? await adminApi.schedules.update(editingId.value, data) : await adminApi.schedules.create(data);
    closeModal(); loadData();
  } catch (e) { alert('Gagal: ' + (e.response?.data?.message || e.message)); }
  finally { saving.value = false; }
};

const deleteItem = async (item) => {
  if (!confirm('Hapus jadwal ini?')) return;
  try { await adminApi.schedules.delete(item.id); loadData(); } catch { alert('Gagal'); }
};

onMounted(() => { loadData(); loadTeachers(); });
</script>

<style scoped>
@import '../../assets/admin-common.css';

.filter-card {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  background: #fff;
  padding: 1rem 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-group label {
  font-weight: 500;
  color: #64748b;
  font-size: 0.9rem;
}

.filter-group select {
  padding: 0.5rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.9rem;
}

.day-badge {
  background: #d4a017;
  color: #fff;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.75rem;
  font-weight: 600;
}
</style>
