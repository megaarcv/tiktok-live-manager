# TikTok Live Manager

Aplikasi web untuk mengelola sesi live TikTok — tracking penonton, likes, diamonds, jadwal live, dan statistik performa akun.

## Fitur

- **Dashboard** — ringkasan statistik semua akun
- **Manajemen Akun TikTok** — tambah, edit, hapus akun
- **Sesi Live** — catat dan monitor sesi live (penonton, likes, diamonds, durasi)
- **Jadwal Live** — buat dan kelola jadwal live mendatang
- **Statistik** — analitik performa dengan chart dan top sessions

## Tech Stack

- **Backend** — Laravel 12 (PHP 8.4)
- **Frontend** — Tailwind CSS v4, Alpine.js
- **Database** — SQLite (default) / MySQL
- **Build Tool** — Vite

---

## Instalasi Lokal (Development)

### Requirement
- PHP >= 8.2
- Composer
- Node.js >= 18
- Git

### Langkah

```bash
# 1. Clone repository
git clone https://github.com/megaarcv/tiktok-live-manager.git
cd tiktok-live-manager

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies & build assets
npm install
npm run build

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Jalankan migrasi database
php artisan migrate

# 6. Jalankan server
php artisan serve
```

Buka browser ke `http://localhost:8000`

---

## Instalasi di VPS (Production)

### Requirement VPS
- Ubuntu 22.04 / Debian 12
- Minimal 1 GB RAM
- Nginx
- PHP 8.4 + FPM
- Composer
- Node.js 20
- Git

---

### 1. Koneksi ke VPS

```bash
ssh root@IP_VPS_KAMU
```

---

### 2. Install Dependencies

```bash
# Update sistem
apt update && apt upgrade -y

# Install PHP 8.4 + extensions yang dibutuhkan
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.4 php8.4-fpm php8.4-cli php8.4-mbstring php8.4-xml \
  php8.4-curl php8.4-zip php8.4-sqlite3 php8.4-bcmath php8.4-tokenizer

# Install Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install Node.js 20
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Install Nginx & Git
apt install -y nginx git
```

---

### 3. Clone Project

```bash
cd /var/www
git clone https://github.com/megaarcv/tiktok-live-manager.git
cd tiktok-live-manager
```

---

### 4. Setup Project

```bash
# Install PHP dependencies (tanpa dev packages)
composer install --optimize-autoloader --no-dev

# Install & build frontend assets
npm install
npm run build

# Buat file .env dari template
cp .env.example .env

# Generate app key
php artisan key:generate
```

Edit file `.env`:

```bash
nano .env
```

Ubah nilai berikut:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://domain-atau-ip-kamu.com
```

---

### 5. Migrasi Database

```bash
php artisan migrate --force
php artisan storage:link
```

---

### 6. Set Permission Folder

```bash
chown -R www-data:www-data /var/www/tiktok-live-manager
chmod -R 755 /var/www/tiktok-live-manager
chmod -R 775 /var/www/tiktok-live-manager/storage
chmod -R 775 /var/www/tiktok-live-manager/bootstrap/cache
```

---

### 7. Konfigurasi Nginx

Buat file konfigurasi Nginx:

```bash
nano /etc/nginx/sites-available/tiktok-live-manager
```

Isi dengan:

```nginx
server {
    listen 80;
    server_name DOMAIN_ATAU_IP_KAMU;
    root /var/www/tiktok-live-manager/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan konfigurasi:

```bash
ln -s /etc/nginx/sites-available/tiktok-live-manager /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
systemctl restart php8.4-fpm
```

---

### 8. Optimize Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

### 9. (Opsional) SSL dengan Let's Encrypt

Kalau sudah punya domain:

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d domainmu.com
```

---

### Akses Aplikasi

Buka browser ke `http://IP_VPS_KAMU` atau `https://domainmu.com`

Daftar akun baru → login → mulai kelola live TikTok.

---

## Update Aplikasi di VPS

Kalau ada perubahan code di GitHub:

```bash
cd /var/www/tiktok-live-manager
git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate --force
php artisan optimize
```

---

## Lisensi

MIT License
