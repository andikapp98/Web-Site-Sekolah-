<template>
  <div class="container my-5">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Tambah Berita Baru</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="handleSubmit">
          <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" class="form-control" id="title" v-model="form.title" required>
          </div>
          <div class="mb-3">
            <label for="summary" class="form-label">Deskripsi Singkat</label>
            <textarea class="form-control" id="summary" rows="3" v-model="form.summary" required></textarea>
          </div>
          <div class="mb-3">
            <label for="content" class="form-label">Isi Berita</label>
            <textarea class="form-control" id="content" rows="10" v-model="form.content" required></textarea>
          </div>
          <div class="mb-3">
            <label for="photo" class="form-label">Foto</label>
            <input type="file" class="form-control" id="photo" @change="handleFileUpload">
          </div>
          <button type="submit" class="btn btn-primary">Simpan Berita</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

const form = reactive({
  title: '',
  summary: '',
  content: '',
  photo: null
});

const handleFileUpload = (event) => {
  form.photo = event.target.files[0];
};

const handleSubmit = () => {
  // Here you would typically send the data to a backend API
  console.log('Form submitted:', form);
  alert('Berita berhasil disimpan! (Cek console untuk melihat data)');
  // Reset form after submission
  form.title = '';
  form.summary = '';
  form.content = '';
  form.photo = null;
  document.getElementById('photo').value = null;
};
</script>

<style scoped>
.card-header {
  background-color: var(--primary-color);
  color: white;
}
</style>
