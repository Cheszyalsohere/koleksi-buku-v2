<div align="center">

# 📚 Koleksi-Buku

### Aplikasi Web Multi-Fitur — Workshop on Web Software Development

Sebuah proyek pembelajaran Laravel yang dibangun bertahap dari **Week 1 hingga Week 11**, tumbuh dari CRUD sederhana menjadi aplikasi multi-fitur: pembayaran daring, pemindai barcode/QR, NFC, Geolocation, hingga sistem antrian real-time.

<br>

![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

![Status](https://img.shields.io/badge/status-active-success?style=flat-square)
![Weeks](https://img.shields.io/badge/progress-Week%201--11-blue?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)

</div>

---

## 🎯 Tentang Proyek

**Koleksi-Buku** adalah aplikasi web yang dikembangkan sebagai tugas mingguan mata kuliah *Workshop on Web Software Development*. Setiap minggu menambahkan satu konsep pengembangan web modern, sehingga proyek ini menjadi rangkuman menyeluruh atas berbagai teknologi — mulai dari operasi dasar CRUD hingga pemanfaatan perangkat keras (kamera & NFC) dan komunikasi *real-time*.

Aplikasi dibangun dengan arsitektur **MVC (Model-View-Controller)** dan menerapkan praktik keamanan seperti validasi sisi server, transaksi basis data, serta verifikasi *signature* pembayaran.

---

## ✨ Fitur per Minggu

| Week | Fitur | Teknologi Utama |
|:---:|:---|:---|
| **1** | 📖 CRUD Buku & Kategori | Eloquent ORM, Route::resource |
| **2** | 📄 Generate PDF (Sertifikat & Undangan) | DomPDF |
| **3** | 📦 CRUD Barang (ID otomatis) | MySQL Trigger, Raw SQL |
| **4** | 📊 Tabel Interaktif & Dropdown | DataTables, Select2 |
| **5** | 🌍 Wilayah Bertingkat | AJAX (jQuery & Axios) |
| **6** | 🍔 Kantin Online + Pembayaran | Midtrans Snap, DB Transaction |
| **7** | 📷 Pendataan + Kamera | getUserMedia, Base64 |
| **8** | 🔍 Barcode & QR Scanner | html5-qrcode |
| **9** | 📍 Geolocation Kunjungan Toko | Haversine, Geolocation API |
| **10** | 🔔 Antrian Real-Time | Server-Sent Events, Web Speech |
| **11** | 💳 Absensi NFC | Web NFC API (NDEFReader) |

---

## 🛠️ Tech Stack

**Backend**
- Laravel 10 (PHP 8)
- MySQL — Eloquent ORM
- Autentikasi multi-guard (User & Vendor) + Google OAuth (Socialite)

**Frontend**
- Blade Templating, Bootstrap 5, jQuery

**Integrasi & API**
- 💰 **Midtrans Snap** — payment gateway (sandbox)
- 🔐 **Google OAuth** — login pihak ketiga
- 🗺️ **API Wilayah Indonesia** (emsifa)

**Library & Web API**
- DomPDF · picqer Barcode (CODE_128) · Endroid QR Code
- Web NFC · Geolocation · Server-Sent Events · Web Speech · Web Audio

---

## 🚀 Instalasi

```bash
# 1. Clone repository
git clone https://github.com/Cheszyalsohere/koleksi-buku.git
cd koleksi-buku

# 2. Install dependency
composer install

# 3. Salin & konfigurasi environment
cp .env.example .env
php artisan key:generate

# 4. Atur koneksi database di file .env
#    DB_DATABASE=koleksi_buku
#    DB_USERNAME=root
#    DB_PASSWORD=

# 5. Jalankan migrasi database
php artisan migrate

# 6. Jalankan server
php artisan serve
```

Akses aplikasi di **http://127.0.0.1:8000**

> ⚙️ **Konfigurasi tambahan** (opsional): isi `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, dan kredensial Google OAuth pada file `.env` untuk mengaktifkan fitur pembayaran dan login Google.

---

## 📱 Catatan Fitur Khusus

| Fitur | Persyaratan |
|:---|:---|
| **NFC (Week 11)** | Ponsel Android + Chrome ≥ 89, diakses via **HTTPS** (mis. ngrok) |
| **Barcode/QR Scanner** | Browser dengan akses kamera |
| **Antrian SSE (Week 10)** | Buka 3 tab: `/antrian/guest`, `/antrian/admin`, `/antrian/papan` |
| **Pembayaran** | Mode **sandbox** Midtrans |

---

## 🗂️ Struktur Singkat

```
app/
 ├─ Http/Controllers/   # Logika tiap fitur (Buku, Antrian, NFC, dll)
 └─ Models/             # Model Eloquent
database/migrations/    # Skema tabel + SQL Trigger
resources/views/        # Tampilan Blade per fitur
routes/web.php          # Definisi seluruh route
```

---

<div align="center">

### 👨‍💻 Author

**Muhammad Irfan Nuha**

Dibuat untuk mata kuliah *Workshop on Web Software Development*

⭐ Beri bintang jika repo ini bermanfaat!

</div>
