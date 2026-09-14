# 📘 Panduan Ringkas Alur UML Sistem Monitoring Ayam Broiler

Dokumen ini dibuat ringkas dan praktis agar kamu bisa langsung menggambarnya ke **Draw.io**, **StarUML**, atau kertas tugas/laporan.

---

## 👥 1. Aktor Sistem
1. **Petugas ABK / Admin**: Mengelola data kandang, periode batch, dan mencatat laporan harian kandang.
2. **Pemilik / Manajemen**: Memantau dashboard performa (KPI & grafik), menerima peringatan kematian, input bobot panen (hitung FCR & IP), dan cetak laporan.

---

## 📌 2. Use Case Diagram (5 Use Case Utama & Cabangnya)

Sistem memiliki **5 Use Case Utama** (terhubung langsung ke Aktor dengan garis lurus), yang kemudian menjorok/bercabang ke oval-oval spesifik menggunakan relasi `<<include>>` (wajib) dan `<<extend>>` (kondisional):

### 5 Use Case Utama:
1. **(UC-01) Login Sistem** *(Aktor: ABK & Pemilik)*
2. **(UC-02) Input Laporan Harian** *(Aktor: Petugas ABK)*
3. **(UC-03) Monitoring Dashboard Eksekutif** *(Aktor: Pemilik)*
4. **(UC-04) Input Bobot Panen & Tutup Batch** *(Aktor: Pemilik)*
5. **(UC-05) Cetak Laporan Produksi** *(Aktor: Pemilik)*

---

### Pemetaan 5 Bundaran Utama ke Oval Cabang:
| No | 5 Bundaran Utama | Oval Cabang (Menjorok) | Tipe Relasi | Keterangan |
|---|---|---|---|---|
| **1** | **Login Sistem** | Verifikasi Kredensial & Role | `<<include>>` | Sistem selalu memeriksa username, hash password, dan role |
| | | Tampil Pesan Gagal Login | `<<extend>>` | Hanya muncul jika password salah atau akun nonaktif |
| **2** | **Input Laporan Harian** | Kalkulasi Hari ke-X & Rekomendasi Pakan | `<<include>>` | Otomatis terhitung dari selisih tanggal DOC masuk |
| | | Validasi Duplikasi Laporan | `<<extend>>` | Menolak jika tanggal pada batch yang sama sudah diisi |
| **3** | **Monitoring Dashboard** | Tampil 6 Grafik Analitik Kinerja | `<<include>>` | Memuat grafik bobot, tren mortalitas, mikroklimat, dll |
| | | Peringatan Kematian (Early Warning) | `<<extend>>` | Hanya muncul jika mortalitas harian $\ge$ 1% populasi |
| **4** | **Input Bobot Panen** | Hitung Otomatis FCR & Indeks Prestasi (IP) | `<<include>>` | Menghitung rumus efisiensi panen setelah bobot riil diisi |
| **5** | **Cetak Laporan Produksi** | Generate Dokumen Kop PT BTS & Kolom TTD | `<<include>>` | Menyiapkan lembar resmi siap print/simpan PDF |

---

### Visual Use Case Diagram (5 Bundaran Utama & Cabangnya):

```mermaid
flowchart LR
    ABK["👷‍♂️ Petugas ABK"]
    PEMILIK["👨‍💼 Pemilik"]

    subgraph "Sistem Monitoring Ayam Broiler PT BTS"
        %% 5 Bundaran / Oval Utama
        UC1(["1. Login Sistem"])
        UC2(["2. Input Laporan Harian"])
        UC3(["3. Monitoring Dashboard Eksekutif"])
        UC4(["4. Input Bobot Panen & Tutup Batch"])
        UC5(["5. Cetak Laporan Produksi"])

        %% Oval Cabang Menjorok
        UC1_INC(["Verifikasi Kredensial & Role"])
        UC1_EXT(["Tampil Pesan Gagal Login"])

        UC2_INC(["Kalkulasi Hari ke-X & Rekomendasi Pakan"])
        UC2_EXT(["Validasi Duplikasi Laporan"])

        UC3_INC(["Tampil 6 Grafik Analitik Kinerja"])
        UC3_EXT(["Peringatan Kematian Early Warning"])

        UC4_INC(["Hitung Otomatis FCR & Indeks Prestasi IP"])

        UC5_INC(["Generate Lembar Kop PT BTS & TTD"])
    end

    %% Garis Solid Aktor ke 5 Bundaran Utama
    ABK --- UC1
    ABK --- UC2

    PEMILIK --- UC1
    PEMILIK --- UC3
    PEMILIK --- UC4
    PEMILIK --- UC5

    %% Garis Putus-putus Menjorok (Include & Extend)
    UC1 -.->|<<include>>| UC1_INC
    UC1 -.->|<<extend>>| UC1_EXT

    UC2 -.->|<<include>>| UC2_INC
    UC2 -.->|<<extend>>| UC2_EXT

    UC3 -.->|<<include>>| UC3_INC
    UC3 -.->|<<extend>>| UC3_EXT

    UC4 -.->|<<include>>| UC4_INC

    UC5 -.->|<<include>>| UC5_INC
```

---

## 🔄 3. Activity Diagram (Model 2 Kolom Swimlane: Aktor & System)

> **Format ini dibuat persis sesuai model yang kamu lampirkan** (Kolom Kiri: **Aktor**, Kolom Kanan: **System**).

```
Simbol yang Digunakan:
● (Lingkaran Hitam)       = Titik Mulai (Initial State)
[Teks Aktivitas]          = Kotak Aktivitas (Action State)
<> (Belah Ketupat)        = Pilihan / Percabangan (Decision)
(●) (Lingkaran Berbingkai)= Titik Selesai (Final State)
```

---

### 3.1 Activity Diagram 1: Login Sistem

| Pengguna (ABK / Pemilik) | System |
| :--- | :--- |
| ● *(Mulai)* | |
| ↓ | |
| **[Masuk Aplikasi]** | ───► **[Tampil Form Login]** |
| | │ |
| **[Memasukkan Username & Password]** | ◄───┘ |
| │ | |
| └───────────────────────────────► | **< Benar? >** |
| | ├── **Tidak** ──► Kembali ke **[Tampil Form Login]** |
| | └── **Ya** |
| | ↓ |
| | **[Tampil Dashboard / Menu Utama]** |
| | ↓ |
| | (●) *(Selesai)* |

#### Versi Visual Diagram:
```mermaid
flowchart TD
    subgraph Pengguna["Pengguna (ABK / Pemilik)"]
        A((●)) --> B[Masuk Aplikasi]
        D[Memasukkan Username dan Password]
    end

    subgraph System["System"]
        C[Tampil Form Login]
        E{Benar?}
        F[Tampil Menu Utama]
        G(((●)))
    end

    B --> C
    C --> D
    D --> E
    E -- Tidak --> C
    E -- Ya --> F
    F --> G
```

---

### 3.2 Activity Diagram 2: Input Laporan Harian Kandang

| Petugas ABK | System |
| :--- | :--- |
| ● *(Mulai)* | |
| ↓ | |
| **[Pilih Menu Input Harian]** | ───► **[Tampil Form Input Harian]** |
| | │ |
| **[Pilih Batch Periode & Tanggal]** | ◄───┘ |
| │ | |
| └───────────────────────────────► | **[Hitung Otomatis Hari ke-X & Tentukan Jenis Pakan]** |
| | │ |
| **[Input Data Kematian, Pakan, Suhu, & Bobot]** | ◄───┘ |
| │ | |
| **[Klik Tombol Simpan]** | |
| │ | |
| └───────────────────────────────► | **< Data Tanggal Sudah Ada? >** |
| | ├── **Ya** ──► **[Tampil Notifikasi Error Duplikat]** ──► kembali ke Form |
| | └── **Tidak** |
| | ↓ |
| | **[Simpan Data ke Database & Update Sisa Populasi]** |
| | ↓ |
| | **[Tampil Notifikasi Berhasil & Buka Riwayat]** |
| | ↓ |
| | (●) *(Selesai)* |

#### Versi Visual Diagram:
```mermaid
flowchart TD
    subgraph ABK["Petugas ABK"]
        A((●)) --> B[Pilih Menu Input Harian]
        D[Pilih Batch & Tanggal]
        F[Input Kematian, Pakan, Suhu, & Bobot]
        G[Klik Tombol Simpan]
    end

    subgraph System["System"]
        C[Tampil Form Input Harian]
        E[Hitung Hari ke-X & Rekomendasi Pakan Otomatis]
        H{Data Tanggal Sudah Ada?}
        I[Tampil Notifikasi Error Duplikat]
        J[Simpan ke Database & Update Sisa Ayam]
        K[Tampil Riwayat Data Berhasil]
        L(((●)))
    end

    B --> C
    C --> D
    D --> E
    E --> F
    F --> G
    G --> H
    H -- Ya --> I
    I --> C
    H -- Tidak --> J
    J --> K
    K --> L
```

---

### 3.3 Activity Diagram 3: Monitoring Dashboard Eksekutif

| Pemilik | System |
| :--- | :--- |
| ● *(Mulai)* | |
| ↓ | |
| **[Pilih Menu Dashboard]** | ───► **[Ambil Data Periode & Hitung KPI (Mortalitas, FCR, Pakan)]** |
| | ↓ |
| | **[Render 6 Grafik Analitik Kinerja]** |
| | ↓ |
| | **< Kematian Harian >= 1% Populasi? >** |
| | ├── **Ya** ──► **[Tampilkan Banner Early Warning Merah]** |
| | └── **Tidak** ─► **[Tampilkan Status Normal]** |
| | │ |
| **[Melihat Analisis & Evaluasi Kondisi Ayam]** | ◄───┘ |
| ↓ | |
| (●) *(Selesai)* | |

#### Versi Visual Diagram:
```mermaid
flowchart TD
    subgraph Pemilik["Pemilik"]
        A((●)) --> B[Pilih Menu Dashboard]
        F[Melihat Analisis & Evaluasi Kondisi Ayam] --> G(((●)))
    end

    subgraph System["System"]
        C[Ambil Data & Hitung KPI]
        D[Render 6 Grafik Analitik]
        E{Kematian >= 1%?}
        H[Tampilkan Banner Early Warning]
        I[Tampilkan Status Normal]
    end

    B --> C
    C --> D
    D --> E
    E -- Ya --> H
    E -- Tidak --> I
    H --> F
    I --> F
```

---

### 3.4 Activity Diagram 4: Input Bobot Panen & Perhitungan FCR / IP

| Pemilik | System |
| :--- | :--- |
| ● *(Mulai)* | |
| ↓ | |
| **[Buka Menu Laporan Produksi]** | ───► **[Tampilkan Data Rekap Siklus Pemeliharaan]** |
| | │ |
| **[Buka Modal Tutup Panen]** | ◄───┘ |
| │ | |
| **[Masukkan Total Bobot Panen Riil (Kg)]** | |
| │ | |
| **[Klik Simpan & Selesai Panen]** | |
| │ | |
| └───────────────────────────────► | **[Update Status Batch Menjadi 'Selesai']** |
| | ↓ |
| | **[Hitung Otomatis FCR Final & Nilai Indeks Prestasi (IP)]** |
| | ↓ |
| | **[Tampilkan Hasil Evaluasi Kinerja (Istimewa/Baik/Kurang)]** |
| | ↓ |
| | (●) *(Selesai)* |

#### Versi Visual Diagram:
```mermaid
flowchart TD
    subgraph Pemilik["Pemilik"]
        A((●)) --> B[Buka Menu Laporan Produksi]
        D[Buka Modal Tutup Panen]
        E[Masukkan Total Bobot Panen Riil]
        F[Klik Simpan & Selesai Panen]
    end

    subgraph System["System"]
        C[Tampilkan Data Rekap Siklus]
        G[Update Status Batch Menjadi 'Selesai']
        H[Hitung Otomatis FCR Final & Indeks Prestasi IP]
        I[Tampilkan Hasil Evaluasi Kinerja Panen]
        J(((●)))
    end

    B --> C
    C --> D
    D --> E
    E --> F
    F --> G
    G --> H
    H --> I
    I --> J
```

---

### 3.5 Activity Diagram 5: Cetak Laporan Produksi Resmi

| Pemilik | System |
| :--- | :--- |
| ● *(Mulai)* | |
| ↓ | |
| **[Pilih Periode Laporan]** | ───► **[Ambil Data Detail Rekap & Profil Perusahaan]** |
| | │ |
| **[Klik Tombol Cetak / PDF]** | ◄───┘ |
| │ | |
| └───────────────────────────────► | **[Siapkan Format Cetak Resmi (Kop PT BTS & Kolom TTD)]** |
| | ↓ |
| | **[Buka Kotak Dialog Print Browser / PDF]** |
| | │ |
| **[Simpan PDF / Cetak ke Printer]** | ◄───┘ |
| ↓ | |
| (●) *(Selesai)* | |

---

## ⚡ 4. Sequence Diagram (5 Diagram Sesuai 5 Activity Diagram)

Setiap Sequence Diagram memetakan alur: **Aktor $\rightarrow$ Tampilan (View) $\rightarrow$ Controller $\rightarrow$ Database**.

---

### 4.1 Sequence Diagram 1: Login Sistem
```mermaid
sequenceDiagram
    autonumber
    actor U as 👤 Pengguna (ABK / Pemilik)
    participant V as 🖥️ View: auth/login
    participant C as ⚙️ Controller: Auth
    participant D as 🗄️ Database: users

    U->>V: Masukkan Username & Password, klik Login
    V->>C: POST /auth/login
    C->>D: Cari user berdasarkan username & cek hash password
    alt Kredensial Valid & Akun Aktif
        D-->>C: Return data user (role, name, id)
        C->>C: Set Session (logged_in = TRUE, role)
        C-->>V: Redirect ke /dashboard
        V-->>U: Tampilkan Menu Utama Sesuai Role
    else Kredensial Salah / Akun Nonaktif
        D-->>C: Data tidak cocok / FALSE
        C-->>V: Set Flashdata Error ("Username/Password Salah")
        V-->>U: Kembali ke Form Login + Tampil Pesan Error
    end
```

---

### 4.2 Sequence Diagram 2: Input Laporan Harian Kandang
```mermaid
sequenceDiagram
    autonumber
    actor ABK as 👷‍♂️ Petugas ABK
    participant V as 🖥️ View: harian/input
    participant C as ⚙️ Controller: Harian
    participant D as 🗄️ Database: input_harian

    Note over ABK,C: Kalkulasi Otomatis via AJAX
    ABK->>V: Pilih Batch & Ubah Tanggal Laporan
    V->>C: AJAX GET /harian/ajax_info?periode_id=X&tgl_input=Y
    C->>C: Hitung Hari ke-X & Tentukan Jenis Pakan
    C-->>V: Return JSON (hari_ke, sisa_populasi, jenis_pakan)
    V-->>ABK: Field Hari ke-X & Jenis Pakan otomatis terisi

    Note over ABK,D: Simpan Data Laporan Harian
    ABK->>V: Isi kematian, pakan, suhu, klik "Simpan Laporan"
    V->>C: POST /harian/input
    C->>D: Cek apakah tanggal input sudah ada pada periode ini?
    alt Tanggal Belum Pernah Diinput (Valid)
        D-->>C: Data belum ada (NULL)
        C->>D: INSERT INTO input_harian VALUES (...)
        D-->>C: Sukses simpan
        C-->>V: Redirect /harian?periode_id=X (Flashdata Success)
        V-->>ABK: Tampilkan data baru di tabel riwayat
    else Tanggal Sudah Ada (Duplikat)
        D-->>C: Data ditemukan (Existing)
        C-->>V: Redirect Flashdata Error ("Data hari ini sudah ada")
        V-->>ABK: Tampilkan peringatan duplikat
    end
```

---

### 4.3 Sequence Diagram 3: Monitoring Dashboard Eksekutif
```mermaid
sequenceDiagram
    autonumber
    actor P as 👨‍💼 Pemilik
    participant V as 🖥️ View: dashboard/index_pemilik
    participant C as ⚙️ Controller: Dashboard
    participant D as 🗄️ Database: periode & input_harian

    P->>V: Buka Menu Dashboard
    V->>C: GET /dashboard?periode_id=X
    C->>D: SELECT data batch & log harian
    D-->>C: Return data akumulasi (kematian, pakan, bobot)
    C->>C: Hitung KPI (Sisa Ayam, Livability %, FCR Sementara)
    C->>C: Siapkan data 6 Grafik Analitik Kinerja
    C->>C: Cek apakah ada kematian harian >= 1% populasi?
    alt Terdapat Lonjakan Kematian (>= 1%)
        C->>C: Buat array data peringatan (Alert)
    else Kondisi Normal (< 1%)
        C->>C: Status peternakan aman
    end
    C-->>V: Render view dengan KPI, Grafik, & Alert
    V-->>P: Tampilkan Dashboard Eksekutif Interaktif
```

---

### 4.4 Sequence Diagram 4: Input Bobot Panen & Perhitungan FCR / IP
```mermaid
sequenceDiagram
    autonumber
    actor P as 👨‍💼 Pemilik
    participant V as 🖥️ View: laporan/index
    participant C as ⚙️ Controller: Laporan
    participant D as 🗄️ Database: periode

    P->>V: Buka Modal Panen, isi Total Bobot Panen (Kg), klik Simpan
    V->>C: POST /laporan/update_bobot_panen
    C->>D: UPDATE periode SET total_bobot_panen_kg=?, status='selesai'
    D-->>C: Update Berhasil
    C->>C: Hitung FCR Final = Total Pakan / Total Bobot Panen
    C->>C: Hitung Indeks Prestasi (IP)
    C-->>V: Redirect /laporan?periode_id=X (Flashdata Success)
    V-->>P: Tampilkan Nilai FCR Final & Kategori Indeks Prestasi (IP)
```

---

### 4.5 Sequence Diagram 5: Cetak Laporan Produksi Resmi
```mermaid
sequenceDiagram
    autonumber
    actor P as 👨‍💼 Pemilik
    participant V_Idx as 🖥️ View: laporan/index
    participant C as ⚙️ Controller: Laporan
    participant D as 🗄️ Database: input_harian & pengaturan
    participant V_Ctk as 🖥️ View: laporan/cetak

    P->>V_Idx: Klik Tombol "Cetak Laporan / PDF"
    V_Idx->>C: GET /laporan/cetak/{periode_id}
    C->>D: SELECT detail rekapitulasi harian & profil PT BTS
    D-->>C: Return data lengkap
    C-->>V_Ctk: Render view cetak (Kop Surat PT BTS + Kolom TTD)
    V_Ctk-->>P: Tampilkan lembar laporan & otomatis trigger window.print()
```


---

## 🏛️ 5. Class Diagram (Format Sesuai Standar UML)

> **Format Kotak Kelas Sesuai Contoh Gambar:**
> - **Kotak Atas**: Nama Kelas (`PascalCase`)
> - **Kotak Tengah (Atribut)**: `- namaVariabel: tipeData` *(Variabel di depan, tipe data di belakang)*
> - **Kotak Bawah (Method/Operasi)**: `+ namaMethod()` *(Fungsi/operasi aksi)*
> - **Relasi Kardinalitas**: `1` ke `1..*` *(One-to-Many)*

```mermaid
classDiagram
    class User {
        - userId: int
        - name: string
        - username: string
        - password: string
        - role: string
        - phone: string
        - status: string
        + login()
        + logout()
        + tambahPengguna()
        + updateProfil()
        + gantiPassword()
    }

    class Kandang {
        - kandangId: int
        - kodeKandang: string
        - namaKandang: string
        - kapasitas: int
        - tipeKandang: string
        - lokasi: string
        - status: string
        + tambahKandang()
        + editKandang()
        + hapusKandang()
        + cekKetersediaan()
    }

    class PeriodeBatch {
        - periodeId: int
        - kodePeriode: string
        - kandang: Kandang
        - strain: string
        - tglMasukDoc: date
        - populasiAwal: int
        - targetPanenHari: int
        - targetBobotPanenGram: int
        - totalBobotPanenKg: float
        - hargaKontrakPerKg: float
        - status: string
        + buatPeriodeBaru()
        + tutupPeriodePanen()
        + hitungFCRFinal()
        + hitungIndeksPrestasi()
        + getSummary()
    }

    class InputHarian {
        - harianId: int
        - periode: PeriodeBatch
        - user: User
        - tglInput: date
        - hariKe: int
        - jumlahMati: int
        - penyebabKematian: string
        - jenisPakan: string
        - pakanKg: float
        - pakanKarung: float
        - obatVitamin: string
        - suhuKandang: float
        - kelembaban: float
        - bobotSampelGram: float
        + inputLaporanHarian()
        + editLaporanHarian()
        + hapusLaporanHarian()
        + hitungHariOtomatis()
        + rekomendasiPakan()
        + konversiKarung()
    }

    class LaporanProduksi {
        - periode: PeriodeBatch
        - totalMati: int
        - sisaAyamHidup: int
        - totalPakanKg: float
        - mortalitasPersen: float
        - livabilityPersen: float
        - fcrSementara: float
        - fcrFinal: float
        - indeksPrestasi: float
        + getRekapHarian()
        + hitungKinerja()
        + deteksiLonjakanKematian()
        + cetakLaporanPDF()
    }

    Kandang "1" -- "1..*" PeriodeBatch : digunakan pada
    PeriodeBatch "1" -- "1..*" InputHarian : memiliki log
    User "1" -- "1..*" InputHarian : menginput
    PeriodeBatch "1" -- "1" LaporanProduksi : menghasilkan
```

---

### Rincian Teks Kelas untuk Digambar Manual / Draw.io

#### 1. Kelas: `User`
* **Atribut (Kotak Tengah)**:
  - `- userId: int`
  - `- name: string`
  - `- username: string`
  - `- password: string`
  - `- role: string`
  - `- phone: string`
  - `- status: string`
* **Method (Kotak Bawah)**:
  - `+ login()`
  - `+ logout()`
  - `+ tambahPengguna()`
  - `+ updateProfil()`
  - `+ gantiPassword()`

---

#### 2. Kelas: `Kandang`
* **Atribut (Kotak Tengah)**:
  - `- kandangId: int`
  - `- kodeKandang: string`
  - `- namaKandang: string`
  - `- kapasitas: int`
  - `- tipeKandang: string`
  - `- lokasi: string`
  - `- status: string`
* **Method (Kotak Bawah)**:
  - `+ tambahKandang()`
  - `+ editKandang()`
  - `+ hapusKandang()`
  - `+ cekKetersediaan()`

---

#### 3. Kelas: `PeriodeBatch`
* **Atribut (Kotak Tengah)**:
  - `- periodeId: int`
  - `- kodePeriode: string`
  - `- kandang: Kandang`
  - `- strain: string`
  - `- tglMasukDoc: date`
  - `- populasiAwal: int`
  - `- targetPanenHari: int`
  - `- targetBobotPanenGram: int`
  - `- totalBobotPanenKg: float`
  - `- hargaKontrakPerKg: float`
  - `- status: string`
* **Method (Kotak Bawah)**:
  - `+ buatPeriodeBaru()`
  - `+ tutupPeriodePanen()`
  - `+ hitungFCRFinal()`
  - `+ hitungIndeksPrestasi()`
  - `+ getSummary()`

---

#### 4. Kelas: `InputHarian`
* **Atribut (Kotak Tengah)**:
  - `- harianId: int`
  - `- periode: PeriodeBatch`
  - `- user: User`
  - `- tglInput: date`
  - `- hariKe: int`
  - `- jumlahMati: int`
  - `- penyebabKematian: string`
  - `- jenisPakan: string`
  - `- pakanKg: float`
  - `- pakanKarung: float`
  - `- obatVitamin: string`
  - `- suhuKandang: float`
  - `- kelembaban: float`
  - `- bobotSampelGram: float`
* **Method (Kotak Bawah)**:
  - `+ inputLaporanHarian()`
  - `+ editLaporanHarian()`
  - `+ hapusLaporanHarian()`
  - `+ hitungHariOtomatis()`
  - `+ rekomendasiPakan()`
  - `+ konversiKarung()`

---

#### 5. Kelas: `LaporanProduksi`
* **Atribut (Kotak Tengah)**:
  - `- periode: PeriodeBatch`
  - `- totalMati: int`
  - `- sisaAyamHidup: int`
  - `- totalPakanKg: float`
  - `- mortalitasPersen: float`
  - `- livabilityPersen: float`
  - `- fcrSementara: float`
  - `- fcrFinal: float`
  - `- indeksPrestasi: float`
* **Method (Kotak Bawah)**:
  - `+ getRekapHarian()`
  - `+ hitungKinerja()`
  - `+ deteksiLonjakanKematian()`
  - `+ cetakLaporanPDF()`

---

### Garis Relasi & Kardinalitas Antar Kelas:
1. **`Kandang` (`1`) ───────── (`1..*`) `PeriodeBatch`**
   - *Satu unit kandang bisa digunakan berulang kali untuk banyak periode pemeliharaan/batch (1 ke banyak).*
2. **`PeriodeBatch` (`1`) ───────── (`1..*`) `InputHarian`**
   - *Satu batch pemeliharaan memiliki banyak data rekaman harian dari hari ke-1 s/d panen (1 ke banyak).*
3. **`User` (`1`) ───────── (`1..*`) `InputHarian`**
   - *Satu user/petugas ABK dapat menginput banyak catatan laporan harian (1 ke banyak).*
4. **`PeriodeBatch` (`1`) ───────── (`1`) `LaporanProduksi`**
   - *Satu batch pemeliharaan menghasilkan satu rekapitulasi laporan performa produksi akhir (1 ke 1).*

---

## 📐 6. Rumus Utama Sistem (Bila Ditanyakan Dosen/Penguji)
1. **Umur Ayam (Hari ke-X)**: `(Tanggal Laporan - Tanggal Masuk DOC) + 1`
2. **Mortalitas (%)**: `(Total Kematian / Populasi Awal) × 100%`
3. **Livability (%)**: `100% - Mortalitas (%)`
4. **FCR Final**: `Total Pakan Terpakai (Kg) / Total Bobot Panen Riil (Kg)`
5. **Indeks Prestasi (IP)**: `(Livability % × Rata Bobot Panen Kg) / (FCR × Umur Rata Panen) × 100`
6. **Early Warning Alert**: Muncul jika kematian harian $\ge 1\%$ populasi awal.

