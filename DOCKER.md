# Docker Setup for WebPortal Laravel

Dokumen ini menjelaskan cara menjalankan proyek WebPortal Laravel menggunakan Docker dengan Laravel Sail.

## 📋 Prerequisites

- Docker Desktop (Windows/Mac) atau Docker Engine (Linux)
- Git (opsional)

## 🚀 Cara Menjalankan dengan Docker

### 1. Install Docker
Pastikan Docker sudah terinstall di sistem Anda:
- Windows/Mac: Download Docker Desktop dari https://www.docker.com/products/docker-desktop
- Linux: Ikuti panduan instalasi Docker untuk distribusi Linux Anda

### 2. Setup Environment
Copy file environment untuk Docker:
```bash
cp .env.docker.example .env
```

Edit file `.env` dan sesuaikan jika diperlukan:
```bash
# Gunakan editor teks favorit Anda
notepad .env  # Windows
nano .env     # Linux/Mac
```

Pastikan konfigurasi database sesuai:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=webportall
DB_USERNAME=sail
DB_PASSWORD=password
```

### 3. Generate Application Key
```bash
# Windows
php bin/sail artisan key:generate

# Linux/Mac
./bin/sail artisan key:generate
```

### 4. Build dan Jalankan Container Docker
```bash
# Windows
php bin/sail up -d

# Linux/Mac
./bin/sail up -d
```

### 5. Install Dependencies
```bash
# Windows
php bin/sail composer install
php bin/sail npm install

# Linux/Mac
./bin/sail composer install
./bin/sail npm install
```

### 6. Migrasi Database
```bash
# Windows
php bin/sail artisan migrate
php bin/sail artisan db:seed

# Linux/Mac
./bin/sail artisan migrate
./bin/sail artisan db:seed
```

### 7. Build Assets Frontend
```bash
# Windows
php bin/sail npm run dev

# Linux/Mac
./bin/sail npm run dev
```

## 🌐 Akses Aplikasi

Setelah semua container berjalan, akses aplikasi di:
- **Aplikasi:** http://localhost
- **Mailpit (Email Testing):** http://localhost:8025
- **Database:** localhost:3306 (username: sail, password: password)

## 🛠 Perintah Sail yang Berguna

### Menjalankan perintah artisan:
```bash
php bin/sail artisan [command]
```

### Menjalankan perintah composer:
```bash
php bin/sail composer [command]
```

### Menjalankan perintah npm:
```bash
php bin/sail npm [command]
```

### Melihat logs:
```bash
php bin/sail logs
```

### Masuk ke container:
```bash
php bin/sail shell
```

### Stop container:
```bash
php bin/sail down
```

### Restart container:
```bash
php bin/sail restart
```

## 🔧 Troubleshooting

### Jika port sudah digunakan:
Edit file `.env` dan ubah port:
```env
APP_PORT=8080
FORWARD_DB_PORT=3307
FORWARD_REDIS_PORT=6380
```

### Jika ada masalah permission:
Pada Linux/Mac, pastikan file permission benar:
```bash
chmod -R 755 storage bootstrap/cache
```

### Jika container tidak bisa di-build:
```bash
# Hapus container dan volume yang ada
php bin/sail down --volumes

# Build ulang
php bin/sail build --no-cache
php bin/sail up -d
```

### Jika ada masalah database:
```bash
# Hapus dan buat ulang database
php bin/sail artisan db:wipe
php bin/sail artisan migrate
php bin/sail artisan db:seed
```

## 📁 Struktur Docker

- `docker-compose.yml` - Konfigurasi container Docker
- `bin/sail` - Script untuk mengelola container
- `.env.docker.example` - Contoh konfigurasi environment untuk Docker

## 🐳 Services yang Disediakan

1. **laravel.test** - Aplikasi Laravel dengan PHP 8.2 + Nginx
2. **mysql** - Database MySQL 8.0
3. **redis** - Redis untuk cache dan session
4. **mailpit** - Email testing tool

## 🔒 Environment Variables Penting

| Variable | Description | Default |
|----------|-------------|---------|
| `DB_HOST` | Database host | `mysql` |
| `DB_PASSWORD` | Database password | `password` |
| `REDIS_HOST` | Redis host | `redis` |
| `APP_PORT` | Port aplikasi | `80` |
| `WWWGROUP` | Group ID | `1000` |
| `WWWUSER` | User ID | `1000` |

## 📞 Support

Jika mengalami masalah:
1. Pastikan Docker berjalan dengan baik
2. Cek logs dengan `php bin/sail logs`
3. Pastikan port tidak bentrok dengan aplikasi lain

Selamat coding dengan Docker! 🐳
