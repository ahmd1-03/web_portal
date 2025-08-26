# TODO: Upgrade reCAPTCHA v2 Checkbox ke Image Challenge

## Tujuan
Mengubah implementasi reCAPTCHA dari checkbox sederhana ke image challenge (tantangan gambar) untuk keamanan yang lebih baik.

## Langkah-langkah Implementasi

### [ ] 1. Update Halaman Login (resources/views/admin/login.blade.php)
- Ubah widget reCAPTCHA dari checkbox ke image challenge
- Tambahkan parameter data-size="normal" untuk menampilkan challenge gambar

### [ ] 2. Update Controller Auth (app/Http/Controllers/Admin/AuthController.php)
- Pindahkan kunci reCAPTCHA ke environment variables
- Update method verifyRecaptcha untuk menggunakan konfigurasi environment

### [ ] 3. Update Konfigurasi Services (config/services.php)
- Tambahkan konfigurasi reCAPTCHA untuk site key dan secret key

### [ ] 4. Update Environment Configuration
- Tambahkan variabel environment untuk reCAPTCHA (akan dilakukan manual oleh user)

## Catatan
- Kunci reCAPTCHA yang saat ini digunakan:
  - Site Key: 6LfGZ60rAAAAAMuSo6eFy3k47g1LdjN1IQ0c6AM8
  - Secret Key: 6LfGZ60rAAAAACmi3icf1fuK5F6F9_COtzypVkMi

- User perlu mengupdate file .env dengan variabel:
  - RECAPTCHA_SITE_KEY=6LfGZ60rAAAAAMuSo6eFy3k47g1LdjN1IQ0c6AM8
  - RECAPTCHA_SECRET_KEY=6LfGZ60rAAAAACmi3icf1fuK5F6F9_COtzypVkMi

## Progress
- [x] Langkah 1: Update view - DONE
- [x] Langkah 2: Update controller - DONE  
- [x] Langkah 3: Update config - DONE
- [x] Langkah 4: Dokumentasi untuk user - DONE

## Instruksi untuk User

### 1. Update File .env
Tambahkan variabel environment berikut ke file `.env` Anda:

```
RECAPTCHA_SITE_KEY=6LfGZ60rAAAAAMuSo6eFy3k47g1LdjN1IQ0c6AM8
RECAPTCHA_SECRET_KEY=6LfGZ60rAAAAACmi3icf1fuK5F6F9_COtzypVkMi
```

### 2. Clear Cache (Opsional)
Jalankan perintah berikut untuk clear cache konfigurasi:
```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Verifikasi
- Buka halaman login di `/admin/login`
- Sekarang akan muncul reCAPTCHA dengan tantangan gambar (bukan checkbox)
- User harus memilih gambar yang sesuai dengan tantangan untuk melanjutkan login

### Catatan
- Kunci reCAPTCHA yang digunakan saat ini adalah kunci yang sudah ada sebelumnya
- Sistem akan otomatis menggunakan reCAPTCHA v2 dengan image challenge
- Validasi tetap berfungsi seperti sebelumnya
