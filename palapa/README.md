# PALAPA - PELAPORAN LANGSUNG TITIK API

PALAPA adalah aplikasi berbasis web yang digunakan untuk melaporkan kejadian kebakaran secara real-time di wilayah Kalimantan. Aplikasi ini memudahkan masyarakat Kalimantan dalam melaporkan insiden kebakaran hutan/lahan (karhutla) maupun kebakaran pemukiman, memetakan koordinat lokasi secara akurat di pulau Kalimantan, serta membantu petugas pemadam kebakaran/BPBD dalam memvalidasi, menugaskan tim lapangan, dan menyelesaikan laporan penanganan secara terstruktur.

---

## 🚀 Fitur Utama Berdasarkan Peran (Role)

### 1. Masyarakat (Warga)
* **Pendaftaran & Login Mandiri**: Warga dapat mendaftar akun dan masuk ke dalam sistem secara mandiri.
* **Buat Laporan Kebakaran**: Melaporkan kebakaran dengan mengisi judul, deskripsi, tingkat keparahan (`low`, `medium`, `high`, `critical`), alamat lengkap, koordinat (latitude & longitude) menggunakan peta interaktif, serta mengunggah foto kejadian.
* **Preview Laporan**: Memastikan data laporan yang diisi sudah benar sebelum disimpan ke database.
* **Edit Laporan**: Mengubah informasi laporan yang dikirim jika masih dalam status awal/belum diproses.
* **Riwayat Status Laporan (Timeline)**: Melihat progres penanganan laporan secara detail mulai dari verifikasi admin, penugasan petugas lapangan, hingga laporan diselesaikan.
* **Notifikasi**: Menerima notifikasi langsung saat status laporan mereka diperbarui oleh petugas.

### 2. Petugas (Petugas Pemadam / BPBD)
* **Dashboard Statistik**: Melihat ringkasan data laporan masuk hari ini, laporan sedang diproses, laporan selesai, dan total laporan keseluruhan.
* **Filter Laporan**: Memfilter laporan masuk berdasarkan tanggal, status, atau kata kunci pencarian lokasi/deskripsi.
* **Verifikasi Laporan**: Menilai keaslian laporan dan mengubah status menjadi **Valid** (Verified) atau **Ditolak** (Invalid beserta alasan penolakannya).
* **Penugasan Petugas Lapangan**: Memilih petugas lapangan yang bertugas menangani kebakaran, otomatis mengubah status laporan menjadi **Diproses** (In Progress) dan mencatat catatan awal penanganan.
* **Penyelesaian Laporan**: Memperbarui status laporan yang diproses menjadi **Selesai** (Resolved) dengan mengunggah bukti foto penanganan dan catatan akhir.

### 3. Admin (Administrator Utama)
* **Dashboard Admin**: Melihat ringkasan statistik laporan masuk dan kontrol penuh terhadap seluruh laporan.
* **Verifikasi & Penugasan**: Melakukan verifikasi validasi laporan dan menugaskan petugas seperti halnya petugas pemadam.
* **Hapus Laporan**: Memiliki hak akses khusus untuk menghapus laporan jika diperlukan.
* **Manajemen Pengguna (User Management)**: Melihat, mengedit, membuat, dan menghapus akun pengguna (Admin, Petugas, Masyarakat).
* **Import Petugas**: Mengimpor data petugas pemadam kebakaran secara massal ke dalam sistem.

---

## 🛠️ Langkah Menjalankan Seeder dan Migrasi

Untuk menjalankan sistem dengan database bersih dan data uji coba (seeder) yang lengkap di wilayah Kalimantan, jalankan perintah berikut di terminal:

```bash
# Jalankan seluruh migrasi dari awal beserta data seeders
php artisan migrate:fresh --seed
```

Jika ingin menjalankan seeder saja pada database yang sudah termigrasi:

```bash
php artisan db:seed
```

---

## 🔑 Daftar Akun Uji Coba (Seeded Accounts)

Semua akun di bawah menggunakan password bawaan: **`password`**

### 1. Administrator (Role: `admin`)
Digunakan untuk mengelola pengguna, menghapus laporan, dan melakukan verifikasi/penugasan.
* **Admin Utama**: `admin@example.com`
* **Admin 1**: `admin1@example.com`
* **Admin 2**: `admin2@example.com`
* **Admin 3**: `admin3@example.com`

### 2. Petugas Pemadam / BPBD (Role: `petugas`)
Digunakan untuk mengelola penanganan kebakaran, memverifikasi laporan warga, dan memperbarui status laporan.
* **Petugas Utama**: `petugas@example.com`
* **Petugas 1 s.d 10**: `petugas1@example.com` hingga `petugas10@example.com`

### 3. Masyarakat / Warga (Role: `masyarakat`)
Digunakan untuk membuat laporan kebakaran dan memantau status laporan.
* **Warga Utama**: `warga@example.com` (Umum)
* **Budi Aktif**: `warga.aktif@example.com` (Uji coba warga yang sudah pernah membuat 10 laporan dengan berbagai status di Kalimantan)
* **Ani Baru**: `warga.baru@example.com` (Uji coba warga dengan laporan baru berstatus **Pending**)
* **Candra Valid**: `warga.valid@example.com` (Uji coba warga dengan laporan terverifikasi **Valid**)
* **Dedi Proses**: `warga.proses@example.com` (Uji coba warga dengan laporan yang sedang **Diproses** / ditugaskan)
* **Eka Selesai**: `warga.selesai@example.com` (Uji coba warga dengan laporan yang sudah **Selesai** / ditangani)
* **Warga 1 s.d 5**: `warga1@example.com` hingga `warga5@example.com` (Umum)

---

## 📋 Detail Data Laporan Hasil Seeder (`ReportSeeder`)

Seluruh data koordinat dalam seeder ditempatkan secara realistis di area **Kalimantan** (Kalimantan Barat, Kalimantan Tengah, Kalimantan Selatan, Kalimantan Timur, dan Kalimantan Utara), sehingga muncul secara akurat di peta interaktif Kalimantan.

| Pelapor | Judul Laporan | Lokasi / Alamat | Status | Keterangan Tambahan / Timeline |
| :--- | :--- | :--- | :--- | :--- |
| **Budi Aktif**<br>(`warga.aktif@`) | Kebakaran Ruko di Jalan Gajah Mada Pontianak | Jl. Gajah Mada No. 12, Pontianak, Kalbar | **Pending** | Baru dilaporkan, menunggu verifikasi petugas. |
| **Budi Aktif**<br>(`warga.aktif@`) | Kabel Listrik Korslet dekat Kantor Gubernur Kaltim | Jl. Gajah Mada No. 2, Samarinda, Kaltim | **Valid** | Terverifikasi valid oleh Admin Utama. |
| **Budi Aktif**<br>(`warga.aktif@`) | Kebakaran Gudang Kayu di Balikpapan | Jl. Jenderal Sudirman No. 88, Balikpapan, Kaltim | **Diproses** | Ditugaskan ke **Petugas 1**. Catatan: BPBD sedang meluncur. |
| **Budi Aktif**<br>(`warga.aktif@`) | Kebakaran Lahan Gambut di Palangka Raya | Jl. Yos Sudarso No. 2, Palangka Raya, Kalteng | **Selesai** | Ditugaskan ke **Petugas 2**. Selesai dipadamkan (ada Bukti Foto). |
| **Budi Aktif**<br>(`warga.aktif@`) | Kebakaran di Siring Menara Pandang Banjarmasin | Siring Menara Pandang, Banjarmasin, Kalsel | **Ditolak** | Laporan palsu. Alasan: Hanya anak bermain kembang api/flare. |
| **Budi Aktif**<br>(`warga.aktif@`) | Kebakaran Tabung Gas Warung Makan Singkawang | Jl. Diponegoro No. 4, Singkawang, Kalbar | **Pending** | Baru dilaporkan, menunggu verifikasi petugas. |
| **Budi Aktif**<br>(`warga.aktif@`) | Kebakaran Ruko Toko Elektronik Banjarbaru | Jl. Jenderal Ahmad Yani Km. 33, Banjarbaru, Kalsel | **Valid** | Terverifikasi valid oleh Admin Utama. |
| **Budi Aktif**<br>(`warga.aktif@`) | Kebakaran Lahan Gambut di Ketapang | Jl. Jenderal Sudirman, Ketapang, Kalbar | **Diproses** | Ditugaskan ke **Petugas 3**. Catatan: Membuat sekat kanal air. |
| **Budi Aktif**<br>(`warga.aktif@`) | Kebakaran Hutan Dekat Bandara Tarakan | Jl. Mulawarman, Tarakan, Kaltara | **Selesai** | Ditugaskan ke **Petugas 4**. Selesai dipadamkan (ada Bukti Foto). |
| **Budi Aktif**<br>(`warga.aktif@`) | Kebakaran Daun Kering di Tanjung Selor | Jl. Kolonel Soetadji No. 8, Tanjung Selor, Kaltara | **Ditolak** | Ditolak. Alasan: Sudah padam secara mandiri oleh pemilik. |
| **Ani Baru**<br>(`warga.baru@`) | Kebakaran Rumah Panggung di Samarinda Seberang | Jl. Bung Tomo, Samarinda Seberang, Kaltim | **Pending** | Laporan baru dari warga baru. |
| **Candra Valid**<br>(`warga.valid@`) | Asap Tebal dari Gudang Ban Balikpapan | Jl. Soekarno Hatta Km. 2, Balikpapan, Kaltim | **Valid** | Laporan terverifikasi valid, siap ditugaskan ke petugas. |
| **Dedi Proses**<br>(`warga.proses@`) | Kebakaran Hutan Lindung Bukit Soeharto | Tahura Bukit Soeharto, Kutai Kartanegara, Kaltim | **Diproses** | Ditugaskan ke **Petugas 5**. Catatan: Manggala Agni sedang menyemprot. |
| **Eka Selesai**<br>(`warga.selesai@`) | Korsleting Listrik Kantor Dinas Pontianak | Jl. Ahmad Yani No. 111, Pontianak, Kalbar | **Selesai** | Ditugaskan ke **Petugas 6**. Selesai dipadamkan (ada Bukti Foto). |
