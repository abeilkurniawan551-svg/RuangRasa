# 🌿 RuangRasa - Platform Kesehatan Mental & Ruang Pulih

<div align="center">
  <img src="image/logo-horizontal.png" alt="RuangRasa Logo" width="280">
  <p><strong>Ruang aman, nyaman, dan terpercaya untuk mengenali, merawat, dan memulihkan kesehatan mentalmu.</strong></p>
  <p>
    <img src="https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/Database-MySQL%20%2F%20MariaDB-4479A1?style=flat&logo=mysql&logoColor=white" alt="MySQL">
    <img src="https://img.shields.io/badge/Server-Apache%20XAMPP-FB7A24?style=flat&logo=apache&logoColor=white" alt="XAMPP">
    <img src="https://img.shields.io/badge/UI-Vanilla%20CSS%20%26%20JS-1572B6?style=flat" alt="CSS JS">
    <img src="https://img.shields.io/badge/Privacy-100%25%20Privat%20%26%20Anonim-0f766e?style=flat" alt="Privacy">
  </p>
</div>

---

## 📖 Tentang RuangRasa

**RuangRasa** adalah aplikasi web kesehatan mental terintegrasi yang dirancang khusus untuk mahasiswa, pemuda, dan masyarakat umum. Platform ini menyediakan berbagai alat pemulihan mandiri *(self-care)*, pemantauan suasana hati harian, skrining emosional terstandar, AI companion, hingga akses langsung ke psikolog klinis profesional dan bantuan krisis darurat 24/7.

---

## ✨ Fitur-Fitur Utama

| Modul | Berkas Utama | Deskripsi Fitur |
|---|---|---|
| **Dashboard Terpadu** | `dashboard.php` | Pusat navigasi harian, widget ringkasan kondisi emosi, pengingat skrining berkala, tren suasana hati mingguan, dan status aktivitas. |
| **Self Check (Skrining Kesejahteraan)** | `self-check.php` | Evaluasi psikologis mandiri 10 pertanyaan (adaptasi instrumen DASS-21 & GAD-7) dengan perhitungan skor instan (0–30), indikator gauge lingkaran animasi SVG, tingkat keparahan *(Minimal, Ringan, Sedang, Berat)*, saran personal, serta rekap jawaban. |
| **Mood Tracker** | `mood-tracker.php` | Pencatatan suasana hati harian, penandaan faktor pemicu stres *(triggers)*, grafik tren 7 hari, dan refleksi jurnal emosi. |
| **Meditasi & Relaksasi** | `meditasi.php` | Latihan pernapasan terpandu (*Box Breathing 4-4-4*), panduan mindfulness, serta audio generator ambient penenang (suara hujan & derau putih berbasis Web Audio API). |
| **AI Companion Chatbot** | `chatbot.php`, `api_chat.php` | Teman bicara interaktif berbasis AI yang siap mendengarkan curhat kapan saja tanpa menghakimi, dengan dukungan saran penenang. |
| **Konsultasi Psikolog** | `konsultasi.php` | Sistem reservasi dan penjadwalan sesi konseling terarah bersama psikolog klinis bersertifikasi. |
| **Forum Komunitas** | `forum-komunitas.php` | Ruang diskusi aman dan suportif bagi anggota untuk saling berbagi pengalaman secara anonim. |
| **Artikel & Edukasi** | `artikel.php` | Kumpulan artikel edukasi kesehatan mental terpercaya, panduan mengatasi burnout, kecemasan akademis, dan manajemen stres. |
| **Event Wellness** | `event-wellness.php` | Direktori agenda workshop kesehatan mental, webinar, dan sesi meditasi kelompok. |
| **Bantuan Krisis Darurat** | `emergency.php` | Akses cepat hotline darurat nasional (Sejiwa 119), panduan pertolongan pertama krisis psikologis, dan grounding audio. |
| **Profil & Pengaturan** | `profile.php` | Manajemen identitas anggota, pengelolaan data kontak darurat, dan riwayat kesehatan emosional. |

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.x (PDO MySQL)
- **Database**: MySQL / MariaDB (via phpMyAdmin XAMPP)
- **Frontend**: Semantic HTML5, Vanilla CSS3 (desain responsif kustom, dark-mode accents, glassmorphism), Vanilla JavaScript (ES6+)
- **Audio API**: HTML5 Web Audio API untuk synthesizer ambient meditasi
- **Web Server**: Apache via XAMPP

---

## 📂 Struktur Direktori Proyek

```text
ruangrasa/
├── image/                     # Aset gambar, ikon, ilustrasi, dan logo
├── .vscode/                   # Konfigurasi workspace editor
├── README.md                  # Dokumentasi proyek
├── api_chat.php               # Endpoint pemrosesan pesan chatbot AI
├── artikel-edukasi.php        # Redireksi ke artikel edukasi
├── artikel.php                # Halaman kumpulan artikel edukasi kesehatan mental
├── bantuan-darurat.php        # Redireksi ke bantuan darurat
├── chatbot.php                # Halaman antarmuka percakapan AI Chatbot
├── dashboard.php              # Halaman dashboard utama pengguna
├── database.sql               # Skema database MySQL (tabel users & consultations)
├── db_connect.php             # Jembatan koneksi database MySQL (PDO)
├── emergency.php              # Pusat bantuan krisis darurat & Hotline 119
├── event-wellness.php         # Halaman jadwal webinar & wellness event
├── events.php                 # Redireksi ke event wellness
├── forum-komunitas.php        # Komunitas berbagi cerita anonim
├── forum.php                  # Redireksi ke forum komunitas
├── index.php                  # Landing page publik RuangRasa
├── konsultasi-psikolog.php    # Redireksi ke konsultasi psikolog
├── konsultasi.php             # Halaman jadwal & reservasi konsultasi psikolog
├── login.php                  # Halaman masuk (autentikasi pengguna)
├── logout.php                 # Halaman keluar sesi
├── meditasi.php               # Halaman meditasi & audio relaksasi mandiri
├── meditation.php             # Redireksi ke meditasi
├── mood-tracker.php           # Halaman pencatat emosi harian & tren 7 hari
├── profile.php                # Halaman profil akun & kontak darurat
├── register.php               # Halaman pendaftaran akun baru
├── script.js                  # Logika interaktif JavaScript global
├── self-check.php             # Skrining interaktif 10 pertanyaan & hasil evaluasi
└── style.css                  # Berkas stylesheet utama platform
```

---

## 🚀 Panduan Instalasi & Menjalankan Lokal

### 1. Prasyarat Sistem
- **XAMPP** (disarankan versi dengan PHP 8.0 / 8.1 / 8.2 ke atas dan MySQL/MariaDB)
- Peramban web modern (*Google Chrome, Microsoft Edge, Mozilla Firefox, dll.*)

### 2. Penempatan Berkas
Pastikan folder proyek berada di direktori `htdocs` XAMPP Anda:
```bash
C:\xampp\htdocs\ruangrasa\
```

### 3. Menjalankan Apache & MySQL
1. Buka **XAMPP Control Panel**.
2. Klik tombol **Start** pada modul **Apache** dan **MySQL**.

### 4. Import Database MySQL
1. Buka browser dan akses **phpMyAdmin**:
   ```text
   http://localhost/phpmyadmin/
   ```
2. Buat database baru bernama `RuangRasa_db`:
   - Klik menu **New / Baru**.
   - Masukkan nama database: `RuangRasa_db`.
   - Pilih Collation: `utf8mb4_general_ci`, lalu klik **Create / Buat**.
3. Import berkas `database.sql`:
   - Pilih database `RuangRasa_db` pada menu samping kiri.
   - Klik tab **Import**.
   - Pilih berkas `database.sql` dari folder `c:\xampp\htdocs\ruangrasa\database.sql`.
   - Klik tombol **Import / Kirim** di bagian bawah.

### 5. Konfigurasi Koneksi (`db_connect.php`)
Secara default, `db_connect.php` sudah terhubung ke MySQL XAMPP lokal:
```php
$host = '127.0.0.1';
$dbname = 'RuangRasa_db';
$username = 'root';
$password = ''; // Kosongkan jika MySQL XAMPP Anda tidak menggunakan password
```

### 6. Akses Aplikasi
Buka peramban web dan kunjungi alamat berikut:
```text
http://localhost/ruangrasa/
```

---

## 🔑 Autentikasi & Akun Pengguna

- **Pendaftaran**: Buka `http://localhost/ruangrasa/register.php` untuk membuat akun baru.
- **Masuk**: Buka `http://localhost/ruangrasa/login.php` untuk masuk ke sesi pengguna Anda.
- Kata sandi dienkripsi menggunakan algoritma standar industri **Bcrypt** (`PASSWORD_BCRYPT`).

---

## 🔒 Privasi & Keamanan Data

- **Autentikasi Aman**: Perlindungan terhadap password plaintext dengan hashing Bcrypt.
- **Anonimitas Komunitas**: Anggota dapat berbagi cerita tanpa paksaan menampilkan identitas riil.
- **Prepared Statements**: Seluruh kueri SQL menggunakan PDO Prepared Statements untuk mencegah risiko SQL Injection.

---

## 📄 Lisensi & Hak Cipta

Dikembangkan dengan penuh kepedulian untuk kesehatan mental generasi muda.  
© 2026 **RuangRasa**. *Semua hak dilindungi undang-undang.*
