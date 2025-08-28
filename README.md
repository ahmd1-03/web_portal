# WebPortal Laravel Project

Sebuah aplikasi web portal yang dibangun dengan Laravel 12, Tailwind CSS, dan Vite.

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

### Langkah 1: Clone/Download Project
```bash
# Jika menggunakan git
git clone [url-repository]
cd WebPortall

# Atau extract file zip ke folder yang diinginkan
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
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```

Buat database baru di phpMyAdmin atau MySQL dengan nama yang sesuai.

### Langkah 6: Migrasi Database
```bash
# Jalankan migrasi database
php artisan migrate

# Jalankan seeder (jika ada data contoh)
php artisan db:seed
```

### Langkah 7: Build Assets Frontend
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

### VS Code Extensions yang Disarankan:
- PHP Intelephense
- Laravel Extension Pack
- Blade Snippets
- Tailwind CSS IntelliSense
- Live Server (opsional)

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

## 📁 Struktur Project Penting

```
WebPortall/
├── app/              # Logic aplikasi Laravel
├── database/         # Migrations dan seeders
├── resources/
│   ├── views/       # Blade templates
│   ├── js/          # JavaScript files
│   └── css/         # CSS files
├── config/          # Konfigurasi Laravel
├── routes/          # Route definitions
└── public/          # Public assets
```

## 🌐 Akses Aplikasi

- **Frontend:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin
- **phpMyAdmin:** http://localhost/phpmyadmin (jika menggunakan XAMPP)

## 📞 Support

Jika mengalami masalah selama instalasi, pastikan:
1. Semua prerequisites terinstall dengan benar
2. Port 8000 dan 3306 tidak digunakan aplikasi lain
3. File `.env` sudah dikonfigurasi dengan benar

Selamat coding! 🚀
