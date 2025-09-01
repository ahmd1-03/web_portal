# 🌐 WebPortal Karawang

Sebuah aplikasi web portal resmi Kabupaten Karawang yang dibangun dengan teknologi modern untuk menyediakan informasi dan layanan publik secara terintegrasi.

## 📖 Deskripsi Proyek

WebPortal Karawang adalah platform digital yang dirancang untuk memfasilitasi akses masyarakat terhadap informasi dan layanan pemerintah Kabupaten Karawang. Portal ini menyediakan antarmuka yang user-friendly untuk menjelajahi berbagai layanan publik, informasi penting, dan konten terkait pemerintahan daerah.

### 🎯 Tujuan Proyek
- Menyediakan akses mudah ke layanan publik Kabupaten Karawang
- Meningkatkan transparansi informasi pemerintah
- Memfasilitasi komunikasi antara pemerintah dan masyarakat
- Menyediakan platform manajemen konten yang efisien untuk admin

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 12** - Framework PHP modern untuk pengembangan web
- **PHP 8.2+** - Bahasa pemrograman utama
- **MySQL/MariaDB** - Sistem manajemen basis data relasional

### Frontend
- **Blade Templates** - Template engine Laravel
- **Tailwind CSS** - Framework CSS utility-first
- **Vite** - Build tool modern untuk asset bundling
- **Alpine.js** - Framework JavaScript reaktif (jika digunakan)
- **Axios** - HTTP client untuk AJAX requests
- **SweetAlert2** - Library untuk modal dan alert yang cantik

### Fitur Tambahan
- **AOS (Animate On Scroll)** - Library animasi scroll
- **Laravel Sail** - Environment development berbasis Docker
- **Laravel Sanctum** - API authentication (jika diperlukan)

## ✨ Fitur Utama

### 🏠 Frontend (Publik)
- **Portal Informasi** - Halaman utama dengan desain responsif
- **Pencarian Real-time** - Fitur pencarian konten dengan debouncing
- **Grid Cards** - Tampilan kartu untuk berbagai layanan/links
- **Responsive Design** - Kompatibel dengan semua device
- **Animasi Smooth** - Transisi dan animasi yang halus
- **SEO Friendly** - Optimasi untuk mesin pencari

### 👨‍💼 Admin Panel
- **Dashboard Admin** - Overview statistik dan aktivitas
- **Manajemen Kartu** - CRUD untuk konten kartu (layanan/links)
- **Activity Logging** - Pencatatan aktivitas admin
- **Auto Logout** - Logout otomatis setelah 1 jam tidak aktif
- **Profile Management** - Pengelolaan profil admin
- **Password Reset** - Sistem reset password dengan email

### 🔐 Keamanan & Monitoring
- **Authentication** - Sistem login/logout yang aman
- **Authorization** - Kontrol akses berdasarkan role
- **Activity Monitoring** - Monitoring aktivitas real-time
- **Session Management** - Manajemen sesi yang ketat
- **CSRF Protection** - Perlindungan terhadap serangan CSRF
- **SQL Injection Prevention** - Proteksi terhadap SQL injection

### 📊 Analytics & Reporting
- **Visitor Tracking** - Pelacakan pengunjung
- **Activity Reports** - Laporan aktivitas admin
- **Search Analytics** - Analitik pencarian pengguna
- **Performance Monitoring** - Monitoring performa aplikasi

## 📋 Prerequisites (Yang Perlu Diinstall Sebelumnya)

### 1. Software yang Diperlukan

**PHP:**
- PHP 8.2 atau lebih tinggi
- Ekstensi PHP yang diperlukan:
  - PDO PHP Extension
  - OpenSSL PHP Extension
  - Mbstring PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
  - Ctype PHP Extension
  - JSON PHP Extension

**Database:**
- MySQL/MariaDB (disarankan) atau SQLite
- phpMyAdmin (opsional, untuk manajemen database)

**Node.js:**
- Node.js 16.0 atau lebih tinggi
- npm atau yarn

**Composer:**
- Composer 2.0 atau lebih tinggi

### 2. Web Server:
- XAMPP (Windows)
- Laragon (Windows)
- WAMP (Windows)
- MAMP (Mac)
- Atau web server lokal lainnya

## 🚀 Cara Install di VS Code

### Langkah 1: Clone Repository dari GitHub
```bash
# Clone repository
git clone https://github.com/username/WebPortall.git
cd WebPortall

# Atau jika menggunakan SSH
git clone git@github.com:username/WebPortall.git
cd WebPortall
```

### Langkah 2: Install Dependencies PHP
```bash
# Install dependencies Composer
composer install

# Atau jika composer install error, coba:
composer update
```

### Langkah 3: Install Dependencies JavaScript
```bash
# Install dependencies Node.js
npm install

# Atau jika menggunakan yarn
yarn install
```

### Langkah 4: Setup Environment
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Langkah 5: Konfigurasi Database
Edit file `.env` dan sesuaikan dengan konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webportal_karawang
DB_USERNAME=root
DB_PASSWORD=your_password
```

Buat database baru di phpMyAdmin atau MySQL dengan nama yang sesuai.

### Langkah 6: Migrasi Database
```bash
# Jalankan migrasi database
php artisan migrate

# Jalankan seeder untuk data contoh
php artisan db:seed
```

### Langkah 7: Create Storage Symlink
```bash
# Buat symbolic link untuk storage (penting untuk upload file)
php artisan storage:link
```

### Langkah 8: Build Assets Frontend
```bash
# Development mode dengan hot reload
npm run dev

# Atau untuk production
npm run build
```

### Langkah 8: Jalankan Server
```bash
# Jalankan Laravel development server
php artisan serve

# Server akan berjalan di http://localhost:8000
```

## 🔧 Konfigurasi Tambahan

### Untuk XAMPP:
1. Pastikan XAMPP sudah terinstall dan running
2. Apache dan MySQL harus aktif
3. Letakkan project di folder `htdocs` XAMPP
4. Akses melalui `http://localhost/WebPortall/public`

### Menggunakan Laravel Sail (Docker):
```bash
# Install Laravel Sail
composer require laravel/sail --dev

# Publish Sail
php artisan sail:install

# Jalankan Sail
./vendor/bin/sail up
```

### VS Code Extensions yang Disarankan:
- PHP Intelephense
- Laravel Extension Pack
- Blade Snippets
- Tailwind CSS IntelliSense
- Live Server (opsional)
- Docker (jika menggunakan Sail)

## 📁 Struktur Project

```
WebPortall/
├── app/                          # Logic aplikasi Laravel
│   ├── Http/
│   │   ├── Controllers/         # Controller classes
│   │   ├── Middleware/          # Custom middleware
│   │   └── Kernel.php          # HTTP Kernel
│   ├── Models/                  # Eloquent models
│   ├── Mail/                    # Email classes
│   └── Providers/               # Service providers
├── database/                     # Database related files
│   ├── migrations/              # Database migrations
│   ├── seeders/                 # Database seeders
│   └── sql/                     # SQL files
├── public/                       # Public web assets
│   ├── css/
│   ├── js/
│   └── images/
├── resources/                    # Frontend resources
│   ├── css/                     # Custom CSS
│   ├── js/                      # JavaScript files
│   └── views/                   # Blade templates
├── routes/                       # Route definitions
│   └── web.php
├── storage/                      # File storage
├── tests/                        # Test files
├── config/                       # Configuration files
├── bootstrap/                    # Bootstrap files
├── vendor/                       # Composer dependencies
├── node_modules/                 # NPM dependencies
├── .env.example                  # Environment template
├── composer.json                 # Composer configuration
├── package.json                  # NPM configuration
├── vite.config.js               # Vite configuration
├── tailwind.config.js           # Tailwind configuration
└── README.md                     # Documentation
```

## 🌐 Akses Aplikasi

- **Frontend:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin
- **Admin Login:** http://localhost:8000/admin/login
- **phpMyAdmin:** http://localhost/phpmyadmin (jika menggunakan XAMPP)

### Akun Admin Default
```
Email: admin@karawang.go.id
Password: password
```

### 📍 Lokasi Halaman Admin

| Halaman             | URL                            |             File View |
|---------------------|--------------------------------|-----------|
| **Login Admin**     | `/admin/login`                 | `resources/views/admin/login.blade.php` |
| **Register Admin**  | `/admin/register`              | `resources/views/admin/register.blade.php` |
| **Dashboard Admin** | `/admin/dashboard`             | `resources/views/admin/dashboard.blade.php` |
| **Lupa Password**   | `/admin/lupa-password`         | `resources/views/admin/lupa-password.blade.php` |
| **Verify Code**     | `/admin/verify-code`           | `resources/views/admin/verify-code.blade.php` |
| **New Password**    | `/admin/new-password`          | `resources/views/admin/new-password.blade.php` |
| **Reset Password**  | `/admin/reset-password/{token}`| `resources/views/admin/reset-password.blade.php` |
| **Manajemen Kartu** | `/admin/cards`                 | `resources/views/admin/cards/index.blade.php` |
| **Activity Logs**   | `/admin/detail`                | `resources/views/admin/activities/detail.blade.php` |
| **Profile Admin**   | `/admin/profile`               | `resources/views/admin/profile.blade.php` |

## 🐛 Troubleshooting

### Jika Composer Error:
```bash
# Clear cache composer
composer clear-cache
composer install --no-scripts
```

### Jika npm Error:
```bash
# Clear cache npm
npm cache clean --force
npm install
```

### Jika Migration Error:
Pastikan database sudah dibuat dan kredensial di `.env` sudah benar.

### Jika Vite Error:
```bash
# Install ulang dependencies
rm -rf node_modules
npm install
```

### Jika Permission Error:
```bash
# Set permission untuk storage dan bootstrap/cache
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## 📊 Database Schema

### Tabel Utama:
- `users` - Data admin/user
- `cards` - Data kartu layanan
- `activity_logs` - Log aktivitas admin
- `visitors` - Data pengunjung
- `pages` - Data halaman statis

### Relasi:
- User hasMany ActivityLogs
- User hasMany Cards
- Card belongsTo User

## 🔄 Development Workflow

### Menambah Fitur Baru:
1. Buat branch baru: `git checkout -b feature/nama-fitur`
2. Implementasi fitur
3. Test fitur
4. Commit perubahan: `git commit -m "Add: nama fitur"`
5. Push ke branch: `git push origin feature/nama-fitur`
6. Buat Pull Request

### Database Changes:
1. Buat migration: `php artisan make:migration nama_migration`
2. Jalankan migration: `php artisan migrate`
3. Update seeder jika perlu: `php artisan db:seed`

## 📈 Performance Optimization

- **Caching:** Gunakan Laravel caching untuk query yang sering diakses
- **Asset Optimization:** Vite untuk bundling dan minifikasi
- **Database Indexing:** Pastikan tabel memiliki index yang tepat
- **Image Optimization:** Kompresi gambar sebelum upload

## 🔒 Security Best Practices

- **Environment Variables:** Jangan commit file `.env`
- **Input Validation:** Selalu validasi input dari user
- **SQL Injection:** Gunakan Eloquent ORM atau prepared statements
- **CSRF Protection:** Aktifkan CSRF protection
- **Password Hashing:** Gunakan bcrypt untuk hash password
- **Session Security:** Konfigurasi session dengan aman

## 🤝 Contributing

1. Fork repository
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support & Contact

Jika mengalami masalah selama instalasi atau penggunaan, pastikan:
1. Semua prerequisites terinstall dengan benar
2. Port 8000 dan 3306 tidak digunakan aplikasi lain
3. File `.env` sudah dikonfigurasi dengan benar
4. Cache sudah dibersihkan jika ada error

### Kontak Developer:
- Email: developer@karawang.go.id
- Website: https://karawang.go.id

---

**Dibangun dengan ❤️ untuk masyarakat Kabupaten Karawang**

Selamat coding! 🚀
