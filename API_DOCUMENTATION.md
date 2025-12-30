# API Documentation - Website SMK YASMU

## Base URL
```
http://localhost:8000/api
```

## Authentication
Untuk admin routes, gunakan Bearer Token dari Sanctum.

---

## Public Endpoints (No Auth Required)

### Hero Slides
```
GET /public/hero-slides
```
Response: Array of hero slides untuk homepage

### Achievements (Jejak Gemilang)
```
GET /public/achievements?limit=6
```
Response: Array of prestasi siswa

### Programs (Jurusan)
```
GET /public/programs
GET /public/programs/{slug}
```
Response: Array atau single program keahlian

### Partners (Mitra)
```
GET /public/partners
```
Response: Array of mitra industri

### Testimonials (Alumni)
```
GET /public/testimonials
```
Response: Array of testimonial alumni

### Articles (Berita)
```
GET /public/articles?limit=10
GET /public/articles/{slug}
```
Response: Array atau single artikel

### School Profiles (Profil Sekolah)
```
GET /public/school-profiles
GET /public/school-profiles/{key}
```
Keys: `principal_message`, `vision`, `mission`, `history`, `school_info`

### Teachers (Data Guru)
```
GET /public/teachers
GET /public/teachers?position=Guru
GET /public/teachers?program_id=1
GET /public/teachers/leadership
GET /public/teachers/{id}
```
Response: Array atau single guru

### Schedules (Jadwal Pembelajaran)
```
GET /public/schedules
GET /public/schedules?program_id=1
GET /public/schedules?class_name=X TPM 1
GET /public/schedules?day=Senin
GET /public/schedules/classes
```
Response: Jadwal dikelompokkan per hari

---

## Admin Endpoints (Auth Required)

### Header
```
Authorization: Bearer {token}
Content-Type: multipart/form-data (untuk upload file)
```

### Achievements
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /admin/achievements | List semua prestasi |
| POST | /admin/achievements | Tambah prestasi baru |
| GET | /admin/achievements/{id} | Detail prestasi |
| PUT | /admin/achievements/{id} | Update prestasi |
| DELETE | /admin/achievements/{id} | Hapus prestasi |

**Fields:**
- `title` (required): Judul prestasi
- `excerpt`: Ringkasan
- `content`: Konten lengkap
- `category`: Kategori (e.g., KEGIATAN, LOMBA)
- `image`: File gambar (max 2MB)
- `event_date`: Tanggal event
- `is_featured`: Boolean
- `is_active`: Boolean

### Articles
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /admin/articles | List semua artikel |
| POST | /admin/articles | Tambah artikel baru |
| GET | /admin/articles/{id} | Detail artikel |
| PUT | /admin/articles/{id} | Update artikel |
| DELETE | /admin/articles/{id} | Hapus artikel |

**Fields:**
- `title` (required): Judul artikel
- `content` (required): Konten artikel
- `excerpt`: Ringkasan
- `category`: Kategori
- `image`: File gambar (max 2MB)
- `is_published`: Boolean

### Programs (Jurusan)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /admin/programs | List semua jurusan |
| POST | /admin/programs | Tambah jurusan baru |
| GET | /admin/programs/{id} | Detail jurusan |
| PUT | /admin/programs/{id} | Update jurusan |
| DELETE | /admin/programs/{id} | Hapus jurusan |

**Fields:**
- `name` (required): Nama program
- `short_name`: Singkatan (e.g., TPM, TKR)
- `description` (required): Deskripsi singkat
- `full_description`: Deskripsi lengkap
- `image`: File gambar (max 2MB)
- `icon`: File icon (max 1MB)
- `order`: Urutan tampil
- `is_active`: Boolean

### Partners (Mitra)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /admin/partners | List semua mitra |
| POST | /admin/partners | Tambah mitra baru |
| GET | /admin/partners/{id} | Detail mitra |
| PUT | /admin/partners/{id} | Update mitra |
| DELETE | /admin/partners/{id} | Hapus mitra |

**Fields:**
- `name` (required): Nama mitra
- `logo`: File logo (max 1MB)
- `website`: URL website
- `description`: Deskripsi
- `order`: Urutan tampil
- `is_active`: Boolean

### Testimonials (Alumni)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /admin/testimonials | List semua testimonial |
| POST | /admin/testimonials | Tambah testimonial baru |
| GET | /admin/testimonials/{id} | Detail testimonial |
| PUT | /admin/testimonials/{id} | Update testimonial |
| DELETE | /admin/testimonials/{id} | Hapus testimonial |

**Fields:**
- `name` (required): Nama alumni
- `jurusan` (required): Jurusan alumni
- `graduation_year`: Tahun lulus
- `photo`: File foto (max 2MB)
- `quote` (required): Testimoni
- `current_position`: Pekerjaan saat ini
- `company`: Nama perusahaan
- `order`: Urutan tampil
- `is_active`: Boolean

### Hero Slides
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /admin/hero-slides | List semua slides |
| POST | /admin/hero-slides | Tambah slide baru |
| GET | /admin/hero-slides/{id} | Detail slide |
| PUT | /admin/hero-slides/{id} | Update slide |
| DELETE | /admin/hero-slides/{id} | Hapus slide |

**Fields:**
- `title`: Judul (kosongkan untuk slide tanpa text)
- `description`: Deskripsi
- `image` (required): File gambar (max 5MB)
- `button_text`: Text tombol
- `button_link`: Link tombol
- `order`: Urutan tampil
- `is_active`: Boolean

---

## Menjalankan Backend

```bash
# Install dependencies
composer install

# Migrate database
php artisan migrate --database=sqlite

# Seed data awal
php artisan db:seed --class=ContentSeeder --database=sqlite

# Create storage link
php artisan storage:link

# Run server
php artisan serve
```

---

## Database Schema

### achievements
- id, title, excerpt, content, category, image, event_date, is_featured, is_active, timestamps

### articles
- id, title, slug, excerpt, content, image, category, author_id, is_published, published_at, views, timestamps

### programs
- id, name, slug, short_name, description, full_description, image, icon, order, is_active, timestamps

### partners
- id, name, logo, website, description, order, is_active, timestamps

### testimonials
- id, name, jurusan, graduation_year, photo, quote, current_position, company, order, is_active, timestamps

### hero_slides
- id, title, description, image, button_text, button_link, order, is_active, timestamps
