# 🐔 APLIKASI MONITORING PETERNAKAN AYAM BROILER PT BTS BERBASIS WEB

Aplikasi sistem informasi manajemen dan monitoring peternakan ayam broiler terpadu berbasis web yang dirancang untuk **PT BTS (Berkah Ternak Sejahtera)**. Sistem ini responsif (dapat digunakan secara optimal di perangkat HP, tablet, maupun desktop PC) untuk menggantikan pencatatan kertas fisik (*recording sheet*) di kandang menjadi sistem digital *real-time*.

---

## 🌟 Fitur Utama & Hak Akses (Role-Based Access)

### 1. 👨‍💼 Role Pemilik / Manajemen (Executive Monitoring)
- **Dashboard Monitoring Eksekutif**:
  - **KPI Cards Real-time**: Sisa Populasi Hidup, Daya Hidup (*Livability %*), Tingkat Mortalitas (%), Total Konsumsi Pakan (Kg & Karung), dan *FCR Sementara*.
  - **Early Warning Alert System**: Peringatan otomatis jika lonjakan kematian harian melebihi ambang batas wajar (> 1% populasi).
  - **6 Grafik Analitik & Infografis Interaktif (ApexCharts)**:
    1. *Kurva Pertumbuhan Bobot Aktual vs Standar Performa Strain* (Cobb 500 / Ross 308).
    2. *Analisis Penyebab Kematian Terbanyak* (Donut Chart).
    3. *Grafik Tren Mortalitas Harian vs Akumulasi Kematian* (Mixed Column & Line).
    4. *Fluktuasi Suhu (°C) & Kelembaban (%) Mikroklimat Kandang*.
    5. *Efisiensi Asupan Pakan Harian per Ekor (Feed Intake Rate - gr/ekor/hari)*.
    6. *Konsumsi Pakan Kumulatif (Kg)*.
  - **Estimasi Proyeksi Finansial**: Perhitungan estimasi nilai penjualan panen (Rp) vs biaya pakan (Rp).
- **Laporan Rekapitulasi Produksi**:
  - Tabel rekapitulasi data harian dari Hari ke-1 hingga Hari ke-40.
  - Modal input total bobot panen riil untuk menghitung **FCR Final** dan **Indeks Prestasi (IP)**.
- **Fitur Cetak Lembar Produksi Resmi (Print / PDF)**:
  - Format cetak standar dokumen resmi dilengkapi **Kop Surat PT BTS**, ringkasan KPI, dan **Kolom Tanda Tangan Serah Terima** (Petugas ABK dan Pemilik).

---

### 2. 👷‍♂️ Role Admin / ABK (Anak Buah Kandang - Operasional)
- **Dashboard Operasional Kandang**:
  - Indikator status checklist laporan hari ini (apakah seluruh kandang aktif sudah diinput atau belum).
  - *Progress bar* siklus pemeliharaan menuju target umur panen.
  - Ringkasan 5 log input operasional terkini.
  - Grafik tren pemberian pakan harian & komposisi pakan (*Starter, Grower, Finisher*).
- **Form Input Laporan Harian Terintegrasi (Core Feature)**:
  - **Kalkulasi Umur Otomatis**: Umur ayam (**Hari ke-X**) terhitung otomatis berdasarkan Tanggal Masuk DOC dan Tanggal Laporan via AJAX.
  - **Rekomendasi Pakan Cerdas**: Penentuan otomatis jenis pakan (*Starter* 1-10 hari, *Grower* 11-21 hari, *Finisher* 22+ hari).
  - **Konversi Satuan Pakan Otomatis**: Mengetik Kg otomatis menghitung Karung (`1 Karung = 50 Kg`) dan sebaliknya.
  - **Pencatatan Lengkap**: Kematian ekor & dropdown penyebab, obat/vitamin/vaksin (dosis & cara pemberian), suhu (°C), kelembaban (%), dan sampel timbang bobot mingguan.
- **Riwayat Input Harian**:
  - Dilengkapi fitur pencarian cepat, sortir, paginasi data menggunakan **DataTables**, serta edit dan hapus data.
- **Manajemen Data Master**:
  - Kelola Unit Kandang (*Closed House* / *Open House*, kapasitas, lokasi).
  - Setup Periode Panen / Batch Baru (Strain bibit, tanggal DOC masuk, jumlah bibit).
  - Manajemen Pengguna (Tambah akun ABK, ganti password, atur status).

---

## 🎨 Tampilan & Desain
- **Switcher Tema Gelap & Terang (Dark / Light Mode)**: Tombol di navbar pojok kanan atas dengan penyimpanan otomatis di `localStorage`.
- **Integrasi DataTables**: Seluruh tabel dilengkapi *search bar* instan, filter jumlah baris, sortir kolom, dan penomoran halaman.
- **Desain Modern & Responsif**: Menggunakan palet warna *dark green / forest* khas peternakan modern dengan aksen *lime green*, kompatibel di layar HP dan laptop.

---

## 📐 Standar Rumus Perhitungan Performa Broiler

| Parameter | Rumus Perhitungan | Standar Baik |
| :--- | :--- | :--- |
| **Mortalitas (%)** | `(Total Kematian / Populasi Awal DOC) × 100%` | $\le 4.0\%$ |
| **Daya Hidup (Livability %)** | `100% - Mortalitas (%)` | $\ge 96.0\%$ |
| **FCR (Feed Conversion Ratio)** | `Total Konsumsi Pakan (Kg) / Total Bobot Panen (Kg)` | $\le 1.55$ *(makin kecil makin efisien)* |
| **Indeks Prestasi (IP)** | `(Livability % × Rata-rata Bobot Panen (Kg)) / (FCR × Umur Rata-rata Panen (Hari)) × 100` | $\ge 350$ *(Sangat Baik / Istimewa)* |
| **Feed Intake (gr/ekor/hari)** | `(Pakan Harian (Kg) × 1000) / Sisa Ayam Hidup` | Sesuai kurva umur |

---

## 🔑 Kredensial Login Default

| Role | Username | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Pemilik / Manajemen** | `pemilik` | `password` | Akses penuh dashboard monitoring eksekutif, grafik analitik, laporan produksi, kalkulasi FCR/IP, cetak PDF |
| **Admin / ABK** | `abk` | `password` | Input laporan harian operasional, kelola data kandang & batch, monitoring harian |
| **Admin Kandang** | `admin` | `password` | Input laporan harian operasional, manajemen pengguna & kandang |

---

## 🛠️ Persyaratan Sistem & Tech Stack

- **Web Server**: Apache (XAMPP / Laragon / WAMP)
- **Bahasa Pemrograman**: PHP $\ge$ 7.4 s/d PHP 8.2+
- **Database**: MySQL / MariaDB
- **Framework Backend**: CodeIgniter 3.1.13
- **Framework Frontend**: Bootstrap 5.3, Bootstrap Icons
- **Visualisasi & Grafik**: ApexCharts.js
- **Interaktivitas Tabel**: jQuery & DataTables Responsive
- **Date Picker**: Flatpickr

---

## ⚙️ Panduan Instalasi & Menjalankan Aplikasi

### 1. Letakkan Folder Project
Pastikan folder project berada pada direktori htdocs XAMPP:
```
C:\xampp\htdocs\project\monitoring_boiler\
```

### 2. Konfigurasi & Import Database
1. Buka aplikasi **XAMPP Control Panel** dan nyalakan modul **Apache** & **MySQL**.
2. Buka browser dan akses **phpMyAdmin** pada URL: `http://localhost/phpmyadmin/`.
3. Buat database baru bernama: `db_monitoring_boiler`.
4. Klik tab **Import** dan pilih file SQL yang berada di:
   ```
   database/db_monitoring_boiler.sql
   ```
5. Klik tombol **Go / Kirim** hingga import selesai.

### 3. Akses Aplikasi
Buka peramban (Google Chrome, Firefox, Safari, atau Edge) dan akses:
```
http://localhost/project/monitoring_boiler/
```
Gunakan salah satu kredensial login default di atas untuk masuk ke sistem.

---

## 📁 Struktur Direktori Project

```text
monitoring_boiler/
├── application/
│   ├── config/             # Konfigurasi database, routes, autoload, config CI3
│   ├── controllers/        # Controller: Auth, Dashboard, Harian, Kandang, Laporan, Pengguna, Periode
│   ├── models/             # Model: User_model, Kandang_model, Periode_model, Harian_model, Laporan_model
│   └── views/              # View templates: layouts, auth, dashboard, harian, kandang, laporan, pengguna, periode
├── assets/
│   ├── css/                # main.css (desain visual modern Spark Admin)
│   ├── js/                 # dashboard.js & script interaksi
│   ├── libs/               # ApexCharts, Bootstrap 5, Bootstrap Icons, DataTables, Flatpickr, jQuery
│   └── images/             # Favicon & assets gambar
├── database/
│   └── db_monitoring_boiler.sql  # Database dump skema & data seed awal
├── system/                 # CodeIgniter 3 Core Engine
├── .htaccess               # Konfigurasi Apache mod_rewrite clean URL
├── index.php               # Front Controller aplikasi
└── README.md               # Dokumentasi lengkap proyek
```

---

## 📄 Lisensi
Dikembangkan untuk **PT BTS (Berkah Ternak Sejahtera)** - Seluruh hak cipta dilindungi undang-undang.
