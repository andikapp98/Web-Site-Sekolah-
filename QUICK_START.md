# Quick Start Guide - SMK Yasmu Gresik

## 🚀 Start Aplikasi

### 1. Backend (Laravel)
```bash
cd C:\Users\KIM\Documents\sekolah
php artisan serve
```
✓ Berjalan di: `http://localhost:8000`

### 2. Frontend (Vue.js)
```bash
cd C:\Users\KIM\Documents\sekolah\frontend
pnpm run dev
```
✓ Berjalan di: `http://localhost:5173` atau `http://localhost:5174`

---

## 🔐 Login

Buka browser: `http://localhost:5173` (atau 5174)

### Admin Account
```
Email:    admin@smkyasmu.com
Password: password
```

### User Account
```
Email:    user@smkyasmu.com
Password: password
```

---

## 📁 Struktur Penting

```
C:\Users\KIM\Documents\sekolah\          ← Backend Laravel
C:\Users\KIM\Documents\sekolah\frontend\ ← Frontend Vue.js
```

---

## ⚡ Command Cepat

### Jalankan Database Migration
```bash
cd C:\Users\KIM\Documents\sekolah
php artisan migrate
```

### Seed Database (Create Users)
```bash
cd C:\Users\KIM\Documents\sekolah
php artisan db:seed
```

### Install Dependencies Frontend
```bash
cd C:\Users\KIM\Documents\sekolah\frontend
pnpm install
```

### Build Production
```bash
cd C:\Users\KIM\Documents\sekolah\frontend
pnpm build
```

---

## 🐛 Troubleshooting

### Port sudah digunakan?
- Frontend biasanya coba port 5173, 5174, dst
- Backend default port 8000
- Cek port yang sedang digunakan di output terminal

### Login tidak berhasil?
1. Cek backend berjalan: `http://localhost:8000`
2. Cek database connected (PostgreSQL di Docker)
3. Lihat console browser (F12) untuk error
4. Pastikan credentials benar

### Vite error?
- Gunakan `pnpm` bukan `npm`
- Pastikan di folder `frontend/` saat run `pnpm run dev`

### Database error?
- Pastikan PostgreSQL container berjalan: `docker ps`
- Cek `.env` file: `DB_HOST=127.0.0.1`
- PHP extensions: `pdo_pgsql` dan `pgsql` harus aktif

---

## 📚 Dokumentasi Lengkap

- `LOGIN_CREDENTIALS.md` - Kredensial login detail
- `UI_IMPROVEMENTS.md` - Daftar perubahan UI
- `README.md` - Laravel documentation

---

## ✨ Fitur UI Baru

✓ Design modern tema hijau sekolah
✓ Dashboard dengan stat cards & charts
✓ Login page dengan animasi
✓ Loading states & error handling
✓ Responsive untuk mobile & desktop
✓ Sidebar dengan gradient & icons
✓ Header dengan tanggal & notifikasi

---

**Last Updated:** 2025-10-26
