# TODO - Implementasi Logout Otomatis 1 Jam Tanpa Aktivitas

## [x] 1. Database Migration
- [x] Buat migration untuk menambah kolom `last_activity` pada tabel users
- [x] Jalankan migration

## [x] 2. Middleware CheckActivity
- [x] Buat middleware baru untuk memeriksa aktivitas user
- [x] Implementasi logika timeout 1 jam

## [x] 3. Update Model Admin
- [x] Tambahkan kolom `last_activity` ke fillable properties

## [x] 4. Update AuthController
- [x] Set `last_activity` saat login berhasil
- [x] Tambahkan method heartbeat untuk memperbarui aktivitas

## [x] 5. JavaScript Activity Detection
- [x] Buat script untuk mendeteksi aktivitas client-side
- [x] Implementasi heartbeat request

## [x] 6. Update Layout Template
- [x] Integrasi script deteksi aktivitas ke layout admin

## [x] 7. Register Middleware
- [x] Tambahkan middleware ke Kernel.php
- [x] Tambahkan middleware ke route group admin

## [ ] 8. Testing
- [ ] Test implementasi dengan berbagai skenario
- [ ] Verifikasi session timeout bekerja dengan benar
