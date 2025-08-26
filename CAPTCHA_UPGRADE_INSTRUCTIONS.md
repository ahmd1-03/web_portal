# Panduan Upgrade reCAPTCHA v2 ke Image Challenge

## Overview
Implementasi ini telah mengubah reCAPTCHA dari versi checkbox sederhana ke versi image challenge (tantangan gambar) untuk meningkatkan keamanan sistem login.

## Perubahan yang Telah Dilakukan

### 1. Halaman Login (`resources/views/admin/login.blade.php`)
- ✅ Ditambahkan parameter `data-size="normal"` pada widget reCAPTCHA
- ✅ Menggunakan `{{ config('services.recaptcha.site_key') }}` untuk site key
- ✅ Komentar diupdate untuk mencerminkan perubahan ke image challenge

### 2. Controller Auth (`app/Http/Controllers/Admin/AuthController.php`)
- ✅ Method `verifyRecaptcha()` sekarang menggunakan `config('services.recaptcha.secret_key')`
- ✅ Kunci tidak lagi hardcoded, menggunakan environment variables

### 3. Konfigurasi Services (`config/services.php`)
- ✅ Ditambahkan konfigurasi reCAPTCHA dengan support environment variables:
```php
'recaptcha' => [
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
],
```

## Langkah-langkah untuk User

### 1. Update File .env
Tambahkan variabel berikut ke file `.env` Anda:

```env
RECAPTCHA_SITE_KEY=6LfGZ60rAAAAAMuSo6eFy3k47g1LdjN1IQ0c6AM8
RECAPTCHA_SECRET_KEY=6LfGZ60rAAAAACmi3icf1fuK5F6F9_COtzypVkMi
```

### 2. Clear Cache (Opsional)
Jalankan perintah berikut untuk memastikan konfigurasi terbaru digunakan:

```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Verifikasi Implementasi
1. Buka halaman login: `/admin/login`
2. Sekarang akan muncul reCAPTCHA dengan tantangan gambar
3. User harus memilih gambar yang sesuai dengan instruksi
4. Coba login untuk memastikan validasi masih berfungsi

## Fitur Keamanan yang Diperoleh

1. **Image Challenge**: User harus memilih gambar tertentu (misalnya jembatan, mobil, dll)
2. **Bot Detection**: Lebih efektif mendeteksi bot dibanding checkbox
3. **User Experience**: Tetap user-friendly dengan interface visual
4. **Backward Compatibility**: Validasi tetap sama, hanya tampilan yang berubah

## Troubleshooting

### Jika reCAPTCHA tidak muncul:
- Pastikan kunci reCAPTCHA valid di Google reCAPTCHA admin
- Periksa koneksi internet untuk loading script Google

### Jika validasi gagal:
- Pastikan variabel environment sudah ditambahkan dengan benar
- Clear cache config dengan `php artisan config:clear`

### Untuk development/testing:
- Anda bisa menggunakan reCAPTCHA test keys dari Google:
  - Site Key: `6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI`
  - Secret Key: `6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe`

## Catatan Penting
- Kunci reCAPTCHA yang digunakan adalah kunci yang sudah ada sebelumnya
- Tidak ada perubahan pada logic validasi, hanya tampilan yang ditingkatkan
- Sistem sekarang menggunakan reCAPTCHA v2 dengan image challenge instead of checkbox
