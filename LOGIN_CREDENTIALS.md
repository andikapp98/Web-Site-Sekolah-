# Login Credentials - SMK Yasmu Gresik

## Default Login Accounts

Sistem ini sudah dilengkapi dengan akun default untuk testing dan development.

### Admin Account

**Email:** `admin@smkyasmu.com`  
**Password:** `password`  
**Role:** Administrator (Full Access)

**Hak Akses:**
- Akses penuh ke dashboard
- Dapat menambah/edit berita
- Manage data siswa
- Semua fitur admin

---

### User Account (Optional)

**Email:** `user@smkyasmu.com`  
**Password:** `password`  
**Role:** User Biasa (Limited Access)

**Hak Akses:**
- Akses ke dashboard
- View data saja (read-only)

---

## Catatan Keamanan

⚠️ **PENTING:** 
- Password default ini **HANYA** untuk development/testing
- **WAJIB** diganti sebelum production
- Gunakan password yang kuat (minimal 8 karakter, kombinasi huruf, angka, dan simbol)

## Cara Mengganti Password

1. Login ke sistem
2. (Fitur ganti password akan ditambahkan)

Atau via database:

```bash
cd C:\Users\KIM\Documents\sekolah
php artisan tinker
```

Kemudian jalankan:

```php
$user = App\Models\User::where('email', 'admin@smkyasmu.com')->first();
$user->password = Hash::make('password_baru_anda');
$user->save();
```

---

## Testing Login

1. Buka browser: `http://localhost:5173`
2. Masukkan email dan password dari akun di atas
3. Klik tombol "Masuk"
4. Anda akan diarahkan ke Dashboard

---

*File ini dibuat untuk memudahkan testing dan development.*
