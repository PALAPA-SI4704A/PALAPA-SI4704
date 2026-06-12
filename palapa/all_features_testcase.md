# Skenario Pengujian Sistem PALAPA (Pelaporan Langsung Titik Api)

Dokumen ini berisi daftar skenario pengujian komprehensif untuk seluruh fitur utama pada aplikasi PALAPA. Setiap skenario pengujian memiliki minimal 4 langkah terperinci yang mencakup inisiasi, input, eksekusi, dan verifikasi akhir.

---

## 1. Fitur: Autentikasi Pengguna (Pendaftaran & Login) - Feature ID: F01

| Feature ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Step Number | Steps Description | Expected Result |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| F01 | TC.Auth.001 | Pendaftaran akun masyarakat baru dengan data lengkap & valid | Positive | Warga mendaftar akun baru dengan sukses | Berada di halaman Register, email belum terdaftar | 1 | Akses landing page PALAPA, lalu klik tombol "Daftar Akun" di bagian header. | Halaman pendaftaran ditampilkan dengan form isian lengkap. |
| | | | | | | 2 | Isi form pendaftaran: Nama Lengkap, Email unik, No Telepon unik, Password, dan Konfirmasi Password dengan data valid. | Semua input terisi dengan format yang benar. |
| | | | | | | 3 | Klik tombol "Daftar" di bawah form. | Sistem memproses pendaftaran akun baru. |
| | | | | | | 4 | Periksa pesan sukses dan pengalihan halaman. | Pengguna dialihkan ke halaman Login dengan pesan sukses "Pendaftaran berhasil, silakan login". |
| F01 | TC.Auth.002 | Login pengguna dengan kredensial terdaftar | Positive | Pengguna masuk ke sistem menggunakan akun valid | Pengguna memiliki akun yang terdaftar dan aktif | 1 | Akses halaman Login PALAPA. | Halaman login ditampilkan dengan input Email dan Password. |
| | | | | | | 2 | Isi email dan password yang valid sesuai perannya (Admin/Petugas/Warga). | Kredensial terisi di form. |
| | | | | | | 3 | Klik tombol "Masuk". | Sistem mencocokkan kredensial di database. |
| | | | | | | 4 | Periksa halaman dashboard yang terbuka. | Pengguna berhasil masuk dan dialihkan ke dashboard yang sesuai (Warga ke halaman Laporan, Admin/Petugas ke Dashboard statistik). |
| F01 | TC.Auth.003 | Logout pengguna dari sistem | Positive | Pengguna mengakhiri sesi dan keluar dari sistem secara aman | Pengguna sedang dalam keadaan login | 1 | Buka menu dropdown profil pada sudut kanan atas navbar. | Dropdown profil muncul menampilkan pilihan Logout. |
| | | | | | | 2 | Klik tombol "Logout". | Sesi autentikasi dihapus dari server. |
| | | | | | | 3 | Tunggu hingga halaman dialihkan otomatis ke halaman Login/Landing. | Pengguna berhasil dialihkan ke halaman depan. |
| | | | | | | 4 | Klik tombol "Kembali" (Back) pada browser. | Pengguna tidak dapat kembali ke halaman dashboard dan tetap tertahan di halaman login dengan sesi terputus. |
| F01 | TC.Auth.004 | Pendaftaran akun dengan email yang sudah digunakan | Negative | Sistem menolak pendaftaran jika email sudah terdaftar | Berada di halaman Register, email sudah digunakan oleh warga lain | 1 | Akses halaman Register PALAPA. | Form pendaftaran ditampilkan. |
| | | | | | | 2 | Isi nama, no telepon baru, password, namun gunakan email yang sudah terdaftar di database. | Data form terisi lengkap. |
| | | | | | | 3 | Klik tombol "Daftar". | Sistem memvalidasi data email yang masuk. |
| | | | | | | 4 | Periksa pesan kesalahan validasi. | Pendaftaran gagal, pengguna tetap di halaman Register, dan muncul pesan error merah: "Email sudah terdaftar". |
| F01 | TC.Auth.005 | Login dengan password yang salah | Negative | Sistem menolak login jika password tidak sesuai | Akun terdaftar aktif di database | 1 | Masuk ke halaman Login PALAPA. | Form login dimuat. |
| | | | | | | 2 | Masukkan email terdaftar yang valid pada input Email. | Email terisi dengan benar. |
| | | | | | | 3 | Masukkan password yang salah pada input Password, lalu klik "Masuk". | Sistem memproses validasi masuk. |
| | | | | | | 4 | Periksa pesan error di layar. | Login gagal, pengguna tetap di halaman Login, dan muncul pesan error: "Kredensial tidak cocok dengan data kami". |
| F01 | TC.Auth.006 | Mengakses halaman internal secara langsung tanpa login | Negative | Sistem memblokir akses tamu (Guest) ke halaman beranda/dashboard | Pengguna belum melakukan login (Guest) | 1 | Buka tab baru atau mode samaran (Incognito) di browser. | Browser bersih tanpa cookie aktif. |
| | | | | | | 2 | Masukkan URL halaman dashboard admin secara langsung (misal: `/admin/dashboard`). | URL dimasukkan ke address bar. |
| | | | | | | 3 | Tekan Enter untuk memuat halaman. | Browser mengirimkan request ke server. |
| | | | | | | 4 | Periksa pemindahan halaman otomatis. | Akses diblokir oleh middleware auth, dan pengguna dialihkan ke halaman `/login` dengan pesan peringatan. |
| F01 | TC.Auth.007 | Validasi format email pada form pendaftaran | Validation | Memastikan input email harus berformat standar | Berada di halaman Register | 1 | Buka halaman Register PALAPA. | Form pendaftaran dimuat. |
| | | | | | | 2 | Isi Nama, No Telepon, Password secara valid, namun isi Email dengan format salah (misal: `wargabaru@`). | Email tidak mengandung domain yang lengkap. |
| | | | | | | 3 | Klik tombol "Daftar". | Browser / sistem memeriksa format email input. |
| | | | | | | 4 | Verifikasi respons validasi form. | Form tidak dapat dikirim (blocked), muncul tooltip bawaan HTML5 browser "Sertakan domain setelah '@'". |
| F01 | TC.Auth.008 | Validasi batas minimum panjang password | Validation | Memastikan password minimal memiliki panjang 8 karakter | Berada di halaman Register | 1 | Akses halaman Register. | Form pendaftaran dimuat. |
| | | | | | | 2 | Isi data lengkap, namun masukkan password pendek kurang dari 8 karakter (contoh: `abc12`). | Form terisi. |
| | | | | | | 3 | Klik tombol "Daftar". | Server memproses validasi request pendaftaran. |
| | | | | | | 4 | Periksa kemunculan pesan error validasi. | Pendaftaran ditolak, muncul pesan error di bawah input password: "Password minimal harus berisi 8 karakter". |
| F01 | TC.Auth.009 | Validasi nomor telepon harus unik dan numerik | Validation | Memastikan nomor telepon hanya diisi angka dan belum pernah terdaftar | Berada di halaman Register | 1 | Buka form pendaftaran (Register). | Form pendaftaran dimuat. |
| | | | | | | 2 | Masukkan nomor telepon yang mengandung karakter non-angka (misal: `0812abc34`) atau nomor yang sudah terpakai. | Input telepon terisi. |
| | | | | | | 3 | Klik tombol "Daftar". | Sistem mengirim data registrasi untuk divalidasi. |
| | | | | | | 4 | Periksa pesan validasi kesalahan. | Pendaftaran gagal, muncul pesan error: "Nomor telepon harus berupa angka dan harus unik". |

---

## 2. Fitur: Pelaporan Kebakaran (Masyarakat) - Feature ID: F02

| Feature ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Step Number | Steps Description | Expected Result |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| F02 | TC.Report.001 | Membuat laporan kebakaran baru dengan data lengkap & valid | Positive | Warga mengirimkan laporan kebakaran baru ke sistem | Warga telah login dan berada di halaman Buat Laporan | 1 | Klik menu "Buat Laporan" pada navbar utama. | Form pembuatan laporan ditampilkan. |
| | | | | | | 2 | Isi Judul, Deskripsi Kejadian, Alamat Lengkap, dan pilih tingkat keparahan (Critical/High/Medium/Low). | Semua field tekstual dan dropdown terisi. |
| | | | | | | 3 | Cari titik koordinat di peta interaktif Kalimantan (geser pin ke lokasi kejadian) dan unggah foto kejadian berformat gambar (.png/.jpg). | Pin peta bergeser, koordinat terisi, dan file foto terunggah. |
| | | | | | | 4 | Klik tombol "Kirim Laporan". | Laporan tersimpan di database dengan status awal "Pending", dialihkan ke riwayat laporan dengan pesan sukses. |
| F02 | TC.Report.002 | Melakukan pratinjau (Preview) sebelum menyimpan laporan | Positive | Warga melihat draf laporan untuk memverifikasi data sebelum disimpan | Warga berada di halaman Buat Laporan dan telah mengisi data | 1 | Isi seluruh kolom data pada form pelaporan kebakaran secara lengkap. | Kolom data terisi. |
| | | | | | | 2 | Klik tombol "Pratinjau Laporan" di bawah form. | Sistem merender draf laporan di halaman baru. |
| | | | | | | 3 | Periksa kesesuaian data judul, deskripsi, alamat, status foto, dan titik koordinat di peta preview. | Tampilan draf akurat sesuai data yang baru saja dimasukkan di form. |
| | | | | | | 4 | Klik "Simpan Laporan" dari halaman pratinjau. | Laporan berhasil disimpan ke database secara permanen dengan status "Pending". |
| F02 | TC.Report.003 | Mengedit laporan kebakaran yang belum diproses (Pending) | Positive | Warga mengubah informasi laporan yang berstatus Pending | Laporan berstatus "Pending" dan milik warga yang bersangkutan | 1 | Akses menu "Riwayat Laporan". | Tampil daftar riwayat laporan yang pernah dibuat oleh warga. |
| | | | | | | 2 | Klik tombol "Edit" pada salah satu laporan yang berstatus "Pending". | Form edit dimuat dengan data lama yang telah terisi. |
| | | | | | | 3 | Ubah judul laporan, deskripsi, dan geser pin peta ke koordinat baru, lalu klik "Simpan Perubahan". | Perubahan disubmit ke server. |
| | | | | | | 4 | Periksa halaman detail laporan untuk melihat data terbaru. | Data laporan berhasil diperbarui, dan perubahan tercermin secara akurat di detail laporan. |
| F02 | TC.Report.004 | Mengirim laporan kebakaran dengan mengosongkan field wajib | Negative | Sistem menolak laporan jika Judul atau Alamat dikosongkan | Warga berada di halaman Buat Laporan | 1 | Buka menu "Buat Laporan". | Form pelaporan dimuat. |
| | | | | | | 2 | Kosongkan field input Judul Laporan dan Alamat Lengkap. | Field wajib dibiarkan kosong. |
| | | | | | | 3 | Isi deskripsi, tingkat keparahan, dan koordinat peta secara lengkap, lalu klik "Kirim Laporan". | Form mencoba melakukan submit. |
| | | | | | | 4 | Periksa respon validasi sistem. | Submit gagal, pengguna tetap di halaman form, dan muncul pesan error merah: "Judul Laporan dan Alamat Lengkap wajib diisi". |
| F02 | TC.Report.005 | Mengunggah foto kejadian dengan tipe file yang salah | Negative | Sistem menolak unggahan berkas non-gambar (misal: PDF/DOCX) | Warga berada di halaman Buat Laporan | 1 | Buka form pelaporan kebakaran baru. | Form dimuat. |
| | | | | | | 2 | Isi field judul, deskripsi, alamat, dan koordinat dengan lengkap. | Data umum terisi. |
| | | | | | | 3 | Pada kolom upload foto bukti kejadian, pilih berkas bertipe `.pdf` atau `.docx`. | File dokumen terpilih. |
| | | | | | | 4 | Klik tombol "Kirim Laporan". | Pendaftaran laporan ditolak dengan pesan kesalahan: "Format file tidak didukung. Unggah gambar berformat .png, .jpg, atau .jpeg". |
| F02 | TC.Report.006 | Mencoba mengedit laporan yang sudah diproses petugas | Negative | Sistem memblokir pengeditan laporan yang berstatus Valid, Diproses, atau Selesai | Laporan milik warga sudah diverifikasi oleh admin (status: Valid) | 1 | Masuk ke menu "Riwayat Laporan". | Daftar riwayat laporan dimuat. |
| | | | | | | 2 | Cari laporan yang memiliki badge status "Valid", "Diproses", atau "Selesai". | Tombol "Edit Laporan" tidak ditampilkan/disembunyikan pada baris laporan tersebut. |
| | | | | | | 3 | Masukkan URL edit laporan tersebut secara paksa di address bar (misal: `/reports/12/edit`). | Browser memuat URL edit secara manual. |
| | | | | | | 4 | Tekan Enter dan periksa respons halaman. | Sistem memblokir akses dan mengalihkan kembali ke dashboard dengan pesan kesalahan: "Laporan yang sedang ditangani tidak dapat diedit". |
| F02 | TC.Report.007 | Validasi koordinat peta harus berada di wilayah Kalimantan | Validation | Memastikan koordinat lokasi laporan berada di dalam cakupan pulau Kalimantan | Berada di halaman Buat Laporan | 1 | Akses form pelaporan kebakaran baru. | Form dimuat. |
| | | | | | | 2 | Isi seluruh kolom data secara valid. | Kolom data terisi. |
| | | | | | | 3 | Masukkan koordinat Latitude dan Longitude di luar pulau Kalimantan secara manual (misal koordinat daerah Pulau Jawa). | Koordinat luar wilayah dimasukkan. |
| | | | | | | 4 | Klik tombol "Kirim Laporan". | Sistem menolak laporan dan menampilkan pesan error validasi: "Koordinat lokasi harus berada di wilayah cakupan Kalimantan". |
| F02 | TC.Report.008 | Validasi pemilihan tingkat keparahan laporan | Validation | Memastikan tingkat keparahan dipilih dari opsi yang ditentukan | Berada di halaman Buat Laporan | 1 | Masuk ke halaman Pelaporan Baru. | Form pelaporan dimuat. |
| | | | | | | 2 | Isi judul, alamat, koordinat, dan unggah foto secara lengkap. | Data terisi. |
| | | | | | | 3 | Kosongkan dropdown pemilihan tingkat keparahan (severity) tanpa memilih opsi apa pun. | Dropdown dalam keadaan kosong. |
| | | | | | | 4 | Klik tombol "Kirim Laporan". | Pengiriman diblokir dengan pesan kesalahan: "Tingkat keparahan wajib dipilih (Low/Medium/High/Critical)". |
| F02 | TC.Report.009 | Validasi sinkronisasi pencarian alamat pada peta interaktif | Validation | Memastikan pencarian lokasi otomatis memindahkan pin koordinat pada peta | Berada di halaman Buat Laporan | 1 | Buka form pelaporan baru, pastikan peta Leaflet berhasil ter-render. | Peta interaktif termuat. |
| | | | | | | 2 | Ketik nama lokasi spesifik pada bar pencarian peta (contoh: "Kantor Gubernur Kalimantan Barat"). | Nama lokasi terisi di kolom pencarian. |
| | | | | | | 3 | Klik tombol cari atau pilih hasil dari daftar dropdown pencarian. | Peta secara otomatis memindahkan pin lokasi ke alamat tersebut. |
| | | | | | | 4 | Periksa kolom koordinat Latitude dan Longitude di bawah peta. | Angka Latitude dan Longitude terisi secara otomatis dan akurat sesuai koordinat lokasi baru di Kalimantan Barat. |

---

## 3. Fitur: Riwayat Status & Notifikasi (Masyarakat) - Feature ID: F03

| Feature ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Step Number | Steps Description | Expected Result |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| F03 | TC.History.001 | Melihat riwayat penanganan laporan (Timeline/Status) | Positive | Warga memantau progres penanganan laporannya melalui timeline status | Warga telah login dan memiliki laporan yang terdaftar | 1 | Akses web PALAPA dan login sebagai warga. | Dashboard warga dimuat. |
| | | | | | | 2 | Klik menu "Riwayat Laporan" pada navbar. | Daftar riwayat laporan ditampilkan beserta ringkasan status saat ini. |
| | | | | | | 3 | Pilih salah satu laporan dan klik tombol "Lihat Detail". | Halaman detail laporan dimuat dengan sukses. |
| | | | | | | 4 | Periksa bagian timeline status penanganan laporan. | Timeline menampilkan tahapan status secara terperinci (Laporan Masuk -> Diverifikasi -> Ditugaskan -> Selesai Dipadamkan) lengkap dengan tanggal/waktu. |
| F03 | TC.History.002 | Menerima notifikasi perubahan status laporan secara real-time | Positive | Warga mendapat pemberitahuan saat petugas memperbarui status laporan | Warga login dan laporan miliknya baru saja diubah statusnya menjadi Valid oleh admin | 1 | Buka halaman beranda warga atau tetap di halaman aktif. | Halaman beranda termuat. |
| | | | | | | 2 | Perhatikan ikon lonceng notifikasi pada navbar bagian kanan atas. | Muncul indikator badge angka merah (notifikasi belum dibaca). |
| | | | | | | 3 | Klik ikon lonceng notifikasi tersebut. | Menu dropdown notifikasi terbuka menampilkan pemberitahuan terbaru. |
| | | | | | | 4 | Periksa isi pesan notifikasi yang ditampilkan. | Pesan menampilkan info akurat: "Laporan kebakaran Anda [Judul] telah diverifikasi Valid dan siap ditangani". |
| F03 | TC.History.003 | Menandai notifikasi sebagai telah dibaca | Positive | Warga mengubah status notifikasi menjadi sudah dibaca | Warga berada di halaman/panel notifikasi | 1 | Klik ikon lonceng notifikasi untuk membuka panel dropdown, lalu pilih "Lihat Semua Notifikasi". | Halaman daftar notifikasi dimuat lengkap. |
| | | | | | | 2 | Cari notifikasi yang memiliki indikator tebal/belum dibaca. | Notifikasi belum dibaca ditemukan. |
| | | | | | | 3 | Klik tombol "Tandai Telah Dibaca" pada notifikasi tersebut. | Sistem mengirim request perubahan status notifikasi ke server. |
| | | | | | | 4 | Periksa visual notifikasi dan badge counter di navbar. | Tampilan notifikasi berubah menjadi redup (telah dibaca) dan badge angka di navbar berkurang secara otomatis. |
| F03 | TC.History.004 | Mencoba melihat detail/timeline laporan milik warga lain | Negative | Sistem memblokir warga dari melihat laporan milik pengguna lain | Warga login sebagai User A, mengetahui ID laporan milik User B (misal ID: 15) | 1 | Login ke sistem menggunakan kredensial User A. | Berhasil masuk sebagai User A. |
| | | | | | | 2 | Masukkan URL riwayat laporan milik User B secara langsung di address bar (contoh: `/reports/15/history`). | URL manual diketik di browser. |
| | | | | | | 3 | Tekan Enter untuk mengirim request halaman. | Server memproses hak akses atas laporan ID 15. |
| | | | | | | 4 | Periksa halaman error yang ditampilkan. | Sistem memblokir akses dan memunculkan halaman error "403 Forbidden" atau pesan "Akses ditolak. Anda tidak berhak melihat laporan ini". |
| F03 | TC.History.005 | Menandai notifikasi milik pengguna lain sebagai dibaca secara ilegal | Negative | Sistem menolak request penandaan notifikasi jika notifikasi bukan miliknya | Pengguna login sebagai User A | 1 | Login sebagai User A dan buka tool pengembang browser (Network tab). | Developer tools aktif. |
| | | | | | | 2 | Dapatkan ID notifikasi milik User B melalui database/sumber lain (misal ID: 99). | ID didapatkan. |
| | | | | | | 3 | Kirim request POST palsu ke URL `/notifikasi/99/read` menggunakan client API/fetch command. | Request dikirim ke server. |
| | | | | | | 4 | Periksa respons HTTP status dari server. | Server menolak dengan kode status 403 Forbidden atau 404 Not Found, memastikan status notifikasi User B tetap aman. |
| F03 | TC.History.006 | Menampilkan halaman riwayat ketika database notifikasi kosong | Negative | Sistem menampilkan visualisasi yang rapi (empty state) jika tidak ada notifikasi | Warga login dan belum pernah mendapatkan notifikasi apa pun | 1 | Login menggunakan akun warga yang baru terdaftar. | Berhasil login ke sistem. |
| | | | | | | 2 | Klik menu "Notifikasi" di navbar. | Halaman notifikasi dimuat. |
| | | | | | | 3 | Tunggu hingga data selesai ditarik dari server. | Halaman selesai dimuat. |
| | | | | | | 4 | Periksa elemen visual yang muncul di halaman. | Sistem menampilkan gambar/ikon lonceng abu-abu dan pesan informatif: "Belum ada notifikasi baru untuk Anda". |
| F03 | TC.History.007 | Validasi kesesuaian kronologis timeline status laporan | Validation | Memastikan timeline status tersusun kronologis sesuai tanggal pembaruan | Warga berada di halaman detail riwayat laporan | 1 | Buka halaman "Riwayat Laporan". | Daftar riwayat laporan ditampilkan. |
| | | | | | | 2 | Pilih laporan yang telah berstatus "Selesai" dan klik "Lihat Detail". | Detail laporan terbuka. |
| | | | | | | 3 | Periksa setiap titik timeline status penanganan dari atas ke bawah. | Alur timeline tersusun urut: Pending (Tanggal Lapor) -> Valid (Tanggal Verifikasi) -> Diproses (Tanggal Ditugaskan) -> Selesai (Tanggal Selesai). |
| | | | | | | 4 | Bandingkan data waktu di timeline dengan timestamp riil perubahan status di database. | Seluruh timestamp di database cocok persis dengan jam dan menit yang ditampilkan di halaman timeline warga. |
| F03 | TC.History.008 | Validasi pengurangan otomatis counter notifikasi belum dibaca | Validation | Memastikan counter notifikasi di navbar sinkron dengan status dibaca | Berada di halaman utama | 1 | Perhatikan counter angka merah notifikasi di navbar saat ada notifikasi baru masuk (misal bernilai "3"). | Counter menampilkan angka 3. |
| | | | | | | 2 | Klik ikon lonceng untuk menampilkan dropdown, lalu klik salah satu notifikasi. | Halaman detail laporan tujuan notifikasi dimuat. |
| | | | | | | 3 | Kembali ke halaman beranda/Dashboard. | Dashboard dimuat ulang. |
| | | | | | | 4 | Periksa kembali counter angka merah notifikasi di navbar. | Counter angka merah notifikasi berhasil berkurang otomatis menjadi "2". |
| F03 | TC.History.009 | Validasi kemunculan foto bukti penanganan di akhir timeline | Validation | Memastikan foto penanganan dari petugas tampil di halaman riwayat saat laporan berstatus selesai | Laporan berstatus "Selesai" dan petugas telah mengunggah foto bukti penanganan | 1 | Login sebagai warga yang laporannya telah selesai ditangani. | Dashboard warga termuat. |
| | | | | | | 2 | Navigasi ke detail riwayat laporan yang berstatus "Selesai". | Halaman detail laporan terbuka. |
| | | | | | | 3 | Scroll halaman hingga ke bagian bawah timeline. | Bagian "Bukti Penanganan Kebakaran" ditemukan. |
| | | | | | | 4 | Periksa keterpajangan foto bukti dan teks catatan akhir dari petugas. | Foto bukti pemadaman ter-render dengan jelas dan catatan akhir penanganan (misal: "Pemadaman tuntas") tampil dengan rapi. |

---

## 4. Fitur: Verifikasi & Penugasan Laporan (Admin & Petugas) - Feature ID: F04

| Feature ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Step Number | Steps Description | Expected Result |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| F04 | TC.Assign.001 | Melakukan verifikasi laporan masuk (Pending -> Valid) | Positive | Admin menyetujui keaslian laporan kebakaran masuk | Admin login dan masuk ke detail laporan Pending | 1 | Login sebagai Admin utama. | Dashboard admin dimuat menampilkan statistik laporan. |
| | | | | | | 2 | Navigasi ke daftar laporan dan pilih laporan berstatus "Pending". | Detail laporan Pending terbuka. |
| | | | | | | 3 | Pada panel "Verifikasi Laporan" di sidebar, klik tombol "Terima & Validasi". | Request dikirim untuk memperbarui status laporan menjadi Valid. |
| | | | | | | 4 | Periksa pembaruan status laporan dan kemunculan panel penugasan. | Halaman refresh otomatis menampilkan pesan sukses, status laporan berubah menjadi "Valid", dan panel daftar "Petugas Tersedia" kini aktif. |
| F04 | TC.Assign.002 | Menugaskan petugas lapangan berstatus Available | Positive | Admin menugaskan petugas yang lowong ke lokasi kebakaran | Laporan berstatus "Valid", terdapat petugas berstatus "Available" | 1 | Buka detail laporan yang baru saja diverifikasi menjadi "Valid". | Detail laporan Valid terbuka. |
| | | | | | | 2 | Scroll ke panel "Petugas Tersedia" di sidebar kanan. | Menampilkan daftar petugas yang dikelompokkan berdasarkan pos terdekat. |
| | | | | | | 3 | Cari petugas dengan badge hijau "Available" pada pos terdekat dan klik tombol "[Tugaskan]". | Request penugasan diproses oleh server. |
| | | | | | | 4 | Periksa tabel status penugasan dan status laporan. | Halaman refresh dengan pesan sukses, status laporan menjadi "Diproses", dan nama petugas tercatat di panel penugasan aktif. |
| F04 | TC.Assign.003 | Mengalihkan penugasan petugas lapangan (Reassign) | Positive | Admin mengalihkan tugas penanganan ke petugas lain yang berstatus Available | Laporan berstatus "Diproses" (ditugaskan ke petugas A), terdapat petugas B yang "Available" | 1 | Buka detail laporan yang sedang berstatus "Diproses" (petugas penanggung jawab saat ini: petugas A). | Detail laporan Diproses terbuka. |
| | | | | | | 2 | Cari petugas B (Available) pada daftar petugas di sidebar. | Petugas B berstatus Available (badge hijau) ditemukan beserta tombol "[Ubah]" di sampingnya. |
| | | | | | | 3 | Klik tombol "[Ubah]" pada baris petugas B, lalu klik "OK" pada dialog konfirmasi yang muncul. | Sistem memproses perubahan penugasan. |
| | | | | | | 4 | Periksa tabel status penugasan aktif pada halaman detail. | Penugasan sukses dialihkan ke petugas B, petugas A dibebaskan menjadi Available, dan laporan tetap berstatus "Diproses". |
| F04 | TC.Assign.004 | Menolak laporan masuk tanpa menyertakan alasan penolakan wajib | Negative | Sistem memblokir penolakan laporan jika alasan penolakan kosong | Laporan berstatus "Pending" | 1 | Buka detail laporan masuk yang berstatus "Pending". | Detail laporan Pending terbuka. |
| | | | | | | 2 | Pada panel verifikasi, klik tombol "Tolak Laporan". | Form isian "Alasan Penolakan" (textarea) muncul di bawah tombol verifikasi. |
| | | | | | | 3 | Kosongkan textarea alasan penolakan, lalu klik tombol "Konfirmasi Tolak". | Request penolakan dicoba dikirim. |
| | | | | | | 4 | Periksa pesan kesalahan validasi. | Browser memblokir submit form dengan pesan error "Please fill out this field", status laporan di sistem tetap "Pending". |
| F04 | TC.Assign.005 | Menugaskan petugas yang sedang sibuk (On Duty) secara langsung | Negative | Admin diblokir dari langsung menugaskan petugas yang statusnya "On Duty" | Laporan berstatus "Valid", petugas A berstatus "On Duty" | 1 | Buka detail laporan yang berstatus "Valid". | Detail laporan Valid terbuka. |
| | | | | | | 2 | Periksa daftar petugas tersedia di sidebar (posisi default: toggle menyembunyikan petugas On Duty). | Petugas A (On Duty) tidak tampil di tabel. |
| | | | | | | 3 | Aktifkan toggle "Tampilkan On Duty". | Petugas A muncul di tabel dengan badge orange "On Duty". |
| | | | | | | 4 | Periksa kolom aksi pada baris petugas A. | Tombol "[Tugaskan]" tidak ada / disembunyikan untuk petugas A yang "On Duty" (hanya tertulis teks "On Duty"). Admin tidak dapat menugaskannya secara langsung. |
| F04 | TC.Assign.006 | Akses penghapusan laporan oleh Petugas non-Admin | Negative | Tombol hapus laporan diblokir/disembunyikan dari pengguna selain Administrator utama | Pengguna login sebagai Petugas Pemadam (bukan Admin) | 1 | Login ke sistem sebagai Petugas Pemadam Kebakaran. | Dashboard petugas dimuat. |
| | | | | | | 2 | Navigasi ke daftar laporan masuk dan klik salah satu laporan. | Detail laporan terbuka. |
| | | | | | | 3 | Cari tombol merah "Hapus Laporan" yang biasanya ada di pojok atas/bawah halaman detail laporan admin. | Tombol "Hapus Laporan" tidak ter-render/disembunyikan dari halaman. |
| | | | | | | 4 | Coba kirim request DELETE secara manual ke URL hapus laporan (misal: `/admin/reports/12`). | Request diblokir oleh middleware dengan respons error "403 Unauthorized" atau "Akses ditolak". |
| F04 | TC.Assign.007 | Validasi fungsi toggle penyaringan petugas "Tampilkan/Sembunyikan On Duty" | Validation | Memastikan toggle menyembunyikan atau menampilkan petugas sibuk secara instan | Berada di halaman detail laporan valid | 1 | Buka halaman detail laporan Valid. | Daftar petugas tersedia ditampilkan. |
| | | | | | | 2 | Periksa daftar petugas (posisi awal toggle mati). | Hanya petugas berbadge "Available" (hijau) yang ditampilkan. Petugas berbadge "On Duty" disembunyikan. |
| | | | | | | 3 | Klik tombol toggle "Tampilkan On Duty". | Daftar petugas diperbarui secara instan (melalui AlpineJS) untuk menampilkan semua petugas baik "Available" maupun "On Duty". |
| | | | | | | 4 | Klik kembali tombol toggle ("Sembunyikan On Duty"). | Daftar petugas "On Duty" langsung disembunyikan kembali secara instan dari tabel tanpa reload halaman. |
| F04 | TC.Assign.008 | Validasi konfirmasi modal popup sebelum reassign | Validation | Memastikan modal konfirmasi muncul untuk mencegah ketidaksengajaan pengalihan tugas | Laporan berstatus "Diproses" | 1 | Buka detail laporan berstatus "Diproses" yang ditugaskan ke petugas A. | Detail laporan terbuka. |
| | | | | | | 2 | Klik tombol "[Ubah]" pada baris petugas B (Available) di sidebar. | Pop-up modal konfirmasi muncul di layar browser. |
| | | | | | | 3 | Klik tombol "Batal" (Cancel) pada modal. | Modal tertutup, form tidak dikirim ke server, dan petugas A tetap memegang penugasan. |
| | | | | | | 4 | Klik tombol "[Ubah]" kembali pada petugas B, lalu klik "OK" pada modal. | Form dikirim ke server, halaman refresh sukses, dan penugasan berhasil dialihkan ke petugas B. |
| F04 | TC.Assign.009 | Validasi status petugas terupdate secara real-time di database | Validation | Memastikan perubahan status petugas di tabel sinkron dengan database | Setelah melakukan penugasan petugas | 1 | Buka halaman detail laporan Valid. | Daftar petugas tersedia ditampilkan. |
| | | | | | | 2 | Tugaskan petugas A (status awal: Available) ke laporan tersebut. | Penugasan diproses, status laporan menjadi "Diproses". |
| | | | | | | 3 | Buka tab database atau akses panel Admin Pengguna. | Periksa data petugas A di database/dashboard. |
| | | | | | | 4 | Verifikasi kolom status penugasan petugas A. | Petugas A terdaftar aktif dalam tugas (is_busy = true) dan otomatis berstatus "On Duty" di laporan manapun. |

---

## 5. Fitur: Penyelesaian Laporan & Pembaruan Status (Petugas Lapangan) - Feature ID: F05

| Feature ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Step Number | Steps Description | Expected Result |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| F05 | TC.Complete.001 | Petugas lapangan memperbarui status laporan menjadi diproses | Positive | Petugas mencatat tindakan awal penanganan kebakaran | Petugas login dan telah ditugaskan ke suatu laporan | 1 | Login ke sistem sebagai Petugas Lapangan yang ditugaskan. | Dashboard petugas dimuat menampilkan daftar penugasan aktif. |
| | | | | | | 2 | Klik judul laporan yang ditugaskan kepada Anda. | Halaman penugasan terbuka menampilkan form "Catatan Awal". |
| | | | | | | 3 | Masukkan catatan tindakan awal (misal: "Unit pemadam pos terdekat bergerak menuju lokasi dengan 5 personel"). | Catatan awal terisi. |
| | | | | | | 4 | Klik tombol "Proses Laporan". | Halaman refresh dengan pesan sukses, status laporan berubah dari "Valid" menjadi "Diproses". |
| F05 | TC.Complete.002 | Petugas menyelesaikan laporan penanganan dengan bukti lengkap | Positive | Petugas menandai laporan sebagai selesai dengan mengunggah foto bukti & catatan akhir | Laporan berstatus "Diproses" oleh petugas yang login | 1 | Buka detail laporan berstatus "Diproses" yang sedang Anda tangani. | Halaman penanganan aktif terbuka. |
| | | | | | | 2 | Pada panel "Selesaikan Laporan", isi textarea catatan akhir penanganan (misal: "Api berhasil padam total pukul 20:30 WITA"). | Catatan akhir terisi. |
| | | | | | | 3 | Klik input file foto bukti kejadian, lalu pilih foto bukti pemadaman tuntas (.jpg/.png). | Foto terpilih untuk diunggah. |
| | | | | | | 4 | Klik tombol "Kirim & Selesaikan". | Laporan tersimpan dengan status "Selesai", status penugasan tuntas, dan status petugas kembali menjadi "Available". |
| F05 | TC.Complete.003 | Petugas melihat dashboard ringkasan statistik | Positive | Petugas memantau statistik penanganan tugasnya | Petugas login dan berada di Dashboard | 1 | Login ke sistem sebagai Petugas Lapangan. | Masuk ke dashboard utama petugas. |
| | | | | | | 2 | Perhatikan panel statistik utama yang ditampilkan. | Tampil rangkuman visual angka laporan baru, laporan sedang ditangani, dan laporan selesai. |
| | | | | | | 3 | Periksa kecocokan data penugasan Anda pada tabel di bawah grafik statistik. | Daftar penugasan yang ditangani oleh Anda muncul secara akurat. |
| | | | | | | 4 | Klik menu navigasi untuk memperbarui (refresh) dashboard. | Angka statistik termuat ulang dengan data paling mutakhir dari database. |
| F05 | TC.Complete.004 | Menyelesaikan laporan tanpa mengunggah berkas foto bukti penanganan | Negative | Sistem memblokir penyelesaian tugas jika foto bukti dikosongkan | Laporan berstatus "Diproses" | 1 | Buka halaman penanganan laporan aktif berstatus "Diproses". | Halaman penanganan dimuat. |
| | | | | | | 2 | Isi catatan akhir penanganan pada kolom yang tersedia. | Catatan akhir terisi. |
| | | | | | | 3 | Kosongkan bagian input upload foto bukti penanganan (jangan pilih berkas). | Input file dibiarkan kosong. |
| | | | | | | 4 | Klik tombol "Kirim & Selesaikan". | Submit gagal, sistem menampilkan pesan error: "Foto bukti penanganan kebakaran wajib diunggah". Status laporan tetap "Diproses". |
| F05 | TC.Complete.005 | Petugas mencoba mengakses/mengedit tugas milik petugas lain | Negative | Petugas dilarang keras memproses laporan yang bukan miliknya | Petugas A login, laporan ditugaskan kepada Petugas B | 1 | Login ke sistem sebagai Petugas A. | Dashboard Petugas A dimuat. |
| | | | | | | 2 | Dapatkan URL halaman penanganan laporan aktif milik Petugas B (misal ID: `/petugas/reports/9`). | URL didapatkan. |
| | | | | | | 3 | Masukkan URL tersebut secara langsung pada address bar browser Petugas A dan tekan Enter. | Browser mencoba memuat halaman penanganan petugas B. |
| | | | | | | 4 | Periksa respons sistem. | Akses diblokir oleh sistem, menampilkan error "403 Forbidden" atau pesan "Anda tidak ditugaskan untuk menangani laporan ini". |
| F05 | TC.Complete.006 | Mengirim catatan akhir penyelesaian laporan yang kosong | Negative | Sistem menolak penyelesaian laporan jika catatan penanganan kosong | Laporan berstatus "Diproses" | 1 | Buka halaman penanganan laporan aktif berstatus "Diproses". | Halaman penanganan dimuat. |
| | | | | | | 2 | Unggah berkas gambar bukti pemadaman kebakaran yang valid pada kolom foto. | File foto terpilih dan siap diunggah. |
| | | | | | | 3 | Kosongkan textarea catatan akhir penanganan (tidak mengetik apa pun). | Catatan akhir dibiarkan kosong. |
| | | | | | | 4 | Klik tombol "Kirim & Selesaikan". | Submit gagal, muncul pesan kesalahan validasi: "Catatan penanganan akhir wajib diisi". Laporan tetap berstatus "Diproses". |
| F05 | TC.Complete.007 | Validasi perekaman otomatis timestamp penyelesaian laporan | Validation | Memastikan kolom database `completed_at` terisi tanggal saat laporan selesai | Setelah laporan diselesaikan petugas | 1 | Login sebagai petugas lapangan dan buka laporan aktif. | Halaman penanganan dimuat. |
| | | | | | | 2 | Selesaikan laporan dengan mengunggah foto bukti dan mengisi catatan akhir, lalu klik "Kirim & Selesaikan". | Laporan berhasil diselesaikan. |
| | | | | | | 3 | Buka antarmuka database (misal phpMyAdmin) dan periksa tabel `penugasans`. | Baris penugasan laporan terkait dicari. |
| | | | | | | 4 | Periksa nilai kolom `completed_at`. | Kolom `completed_at` terisi otomatis dengan format datetime yang akurat sesuai waktu pengiriman form selesai. |
| F05 | TC.Complete.008 | Validasi link akses foto bukti penanganan petugas | Validation | Memastikan foto bukti penanganan dapat dibuka dan diakses publik/admin | Laporan berstatus selesai | 1 | Login sebagai Admin utama dan navigasi ke daftar laporan. | Dashboard admin termuat. |
| | | | | | | 2 | Pilih laporan yang sudah berstatus "Selesai" dan buka halaman detailnya. | Detail laporan Selesai terbuka. |
| | | | | | | 3 | Temukan tautan "Lihat Bukti Foto" pada panel status penugasan petugas. | Tautan bukti foto ditemukan. |
| | | | | | | 4 | Klik tautan tersebut dan pastikan file foto terbuka di browser. | Foto bukti penanganan berhasil dimuat di tab baru dengan resolusi asli tanpa error 404/403. |
| F05 | TC.Complete.009 | Validasi pembaruan angka statistik dashboard secara otomatis | Validation | Memastikan angka di dashboard berubah setelah laporan diselesaikan | Sebelum dan setelah menyelesaikan tugas | 1 | Login sebagai petugas dan perhatikan angka statistik "Sedang Ditangani" dan "Selesai" pada dashboard utama. | Angka statistik tertera (misal: Sedang Ditangani = 1, Selesai = 3). |
| | | | | | | 2 | Masuk ke detail laporan aktif, lakukan proses penyelesaian laporan dengan data valid, dan submit. | Laporan sukses diselesaikan. |
| | | | | | | 3 | Kembali ke halaman Dashboard utama petugas. | Dashboard dimuat ulang. |
| | | | | | | 4 | Verifikasi perubahan angka statistik. | Statistik otomatis terupdate: angka "Sedang Ditangani" berkurang menjadi 0 dan "Selesai" bertambah menjadi 4 secara akurat. |

---

## 6. Fitur: Manajemen Pengguna & Import Petugas (Admin) - Feature ID: F06

| Feature ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Step Number | Steps Description | Expected Result |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| F06 | TC.Manage.001 | Menambah akun petugas baru secara manual | Positive | Admin membuat akun petugas pemadam baru lewat form input | Admin login, berada di menu Manajemen Pengguna | 1 | Login sebagai admin utama dan klik menu "Kelola Pengguna". | Halaman manajemen pengguna dimuat menampilkan tabel daftar user. |
| | | | | | | 2 | Klik tombol "Tambah Petugas Lapangan". | Jendela modal form pendaftaran petugas baru ditampilkan. |
| | | | | | | 3 | Isi Nama Lengkap, Email unik, No Telepon unik, Pos Penempatan (dropdown), Password, dan klik "Simpan". | Data terisi dengan lengkap dan valid. |
| | | | | | | 4 | Periksa daftar pengguna di tabel utama. | Akun petugas baru berhasil ditambahkan dan tercantum di dalam tabel daftar pengguna. |
| F06 | TC.Manage.002 | Mengimpor data petugas lapangan secara massal | Positive | Admin mengunggah file CSV/Excel untuk mendaftarkan banyak petugas sekaligus | Admin berada di halaman Manajemen Pengguna | 1 | Buka menu "Kelola Pengguna". | Halaman manajemen pengguna terbuka. |
| | | | | | | 2 | Klik tombol "Import Petugas". | Modal pengunggahan berkas import muncul. |
| | | | | | | 3 | Klik tombol pilih file, lalu pilih berkas template `.csv` yang berisi 10 baris data petugas pemadam baru. | Berkas CSV terpilih. |
| | | | | | | 4 | Klik tombol "Upload". | Sistem memproses file, mengimpor data, menutup modal, dan menampilkan pesan sukses "10 Petugas berhasil diimpor". |
| F06 | TC.Manage.003 | Mengedit data profil pengguna (warga/petugas) | Positive | Admin memperbarui data email atau nama milik pengguna | Admin berada di daftar pengguna | 1 | Navigasi ke halaman "Kelola Pengguna". | Daftar pengguna ditampilkan di tabel. |
| | | | | | | 2 | Cari pengguna (misal: warga Budi), lalu klik tombol "Edit" di samping namanya. | Form edit user ditampilkan berisi data lama. |
| | | | | | | 3 | Ubah email warga dan nama lengkapnya, lalu klik tombol "Simpan Perubahan". | Request update dikirim ke server. |
| | | | | | | 4 | Periksa pembaruan baris data Budi di tabel. | Halaman memuat ulang, data Budi terupdate secara akurat dengan email dan nama yang baru. |
| F06 | TC.Manage.004 | Mengimpor petugas lapangan menggunakan format berkas salah | Negative | Sistem menolak proses import jika format berkas tidak sesuai template | Admin berada di modal Import Petugas | 1 | Buka modal "Import Petugas" pada halaman manajemen pengguna. | Modal import terbuka. |
| | | | | | | 2 | Siapkan berkas Excel yang kolomnya tidak sesuai dengan template resmi (contoh: tidak ada kolom email). | Berkas salah format disiapkan. |
| | | | | | | 3 | Pilih berkas salah format tersebut dan klik tombol "Upload". | File diunggah dan diverifikasi oleh parser server. |
| | | | | | | 4 | Periksa pesan kesalahan yang tampil di layar. | Unggahan ditolak, sistem menampilkan pesan error detail: "Format kolom tidak sesuai template. Pastikan kolom nama, email, telepon, dan pos tertera". |
| F06 | TC.Manage.005 | Membuat akun petugas baru dengan email yang sudah digunakan | Negative | Sistem menolak pembuatan petugas manual jika email duplikat | Admin berada di form Tambah Petugas | 1 | Buka modal form "Tambah Petugas Lapangan". | Form isian manual terbuka. |
| | | | | | | 2 | Masukkan nama, nomor telepon, pos penempatan, dan password. | Kolom terisi. |
| | | | | | | 3 | Pada kolom email, masukkan email yang sudah dimiliki oleh admin/warga lain, lalu klik "Simpan". | Sistem memvalidasi email di database. |
| | | | | | | 4 | Periksa pesan error validasi. | Pembuatan akun ditolak, sistem memunculkan pesan error: "Email sudah digunakan oleh pengguna lain". |
| F06 | TC.Manage.006 | Admin mencoba menghapus akunnya sendiri (Self-Deletion) | Negative | Sistem memblokir admin dari menghapus akunnya yang sedang login | Admin berada di daftar pengguna | 1 | Navigasi ke daftar pengguna pada halaman Kelola Pengguna. | Tabel daftar pengguna ditampilkan. |
| | | | | | | 2 | Temukan baris nama akun Admin Anda sendiri yang sedang aktif digunakan. | Baris akun admin aktif ditemukan. |
| | | | | | | 3 | Periksa ketersediaan tombol aksi hapus pada baris tersebut. | Tombol "Hapus" dalam kondisi tidak aktif (disabled) atau berwarna abu-abu. |
| | | | | | | 4 | Coba kirim request DELETE ke endpoint hapus user ID Anda secara manual. | Sistem menolak penghapusan diri sendiri dan mengembalikan pesan error: "Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif". |
| F06 | TC.Manage.007 | Validasi ekstensi berkas pada fitur import petugas | Validation | Memastikan berkas yang diunggah wajib berupa berkas CSV/Excel | Admin berada di modal Import Petugas | 1 | Buka modal "Import Petugas". | Modal import terbuka. |
| | | | | | | 2 | Coba pilih berkas bertipe arsip `.zip` atau gambar `.png` pada pencarian file. | Berkas salah ekstensi dipilih. |
| | | | | | | 3 | Klik tombol "Upload". | Sistem memeriksa ekstensi file yang diunggah. |
| | | | | | | 4 | Periksa respon validasi sistem. | Unggahan diblokir secara instan dengan pesan kesalahan: "Format berkas wajib berekstensi .csv, .xls, atau .xlsx". |
| F06 | TC.Manage.008 | Validasi kecocokan jumlah data terimpor dari CSV ke database | Validation | Memastikan seluruh baris data dalam file CSV terdaftar tanpa ada yang terlewat | Setelah proses import sukses | 1 | Hitung jumlah total data petugas di file CSV (misal: terdapat 15 baris data petugas pemadam). | Jumlah data di CSV diidentifikasi. |
| | | | | | | 2 | Jalankan proses import file CSV tersebut hingga selesai. | Pesan sukses impor muncul. |
| | | | | | | 3 | Periksa database tabel `users` filter role `petugas`. | Tampilkan seluruh data petugas terbaru. |
| | | | | | | 4 | Bandingkan data baru di database dengan CSV. | Terverifikasi bahwa jumlah total petugas baru bertambah tepat sebanyak 15 data dan semua datanya cocok persis dengan baris di CSV. |
| F06 | TC.Manage.009 | Validasi konfirmasi modal sebelum menghapus pengguna | Validation | Memastikan modal konfirmasi muncul untuk mencegah ketidaksengajaan penghapusan user | Admin berada di daftar pengguna | 1 | Akses tabel daftar pengguna di menu Kelola Pengguna. | Tabel user ditampilkan. |
| | | | | | | 2 | Klik tombol ikon sampah / "Hapus" pada salah satu baris akun warga. | Sistem memicu dialog konfirmasi. |
| | | | | | | 3 | Pastikan modal konfirmasi muncul dan berbunyi: "Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini permanen". | Modal konfirmasi muncul dengan pilihan Konfirmasi dan Batal. |
| | | | | | | 4 | Klik tombol "Batal" dan pastikan baris data user tersebut tidak hilang dari tabel. | Dialog tertutup, form delete dibatalkan, dan data pengguna tetap aman tersimpan di database. |

---

## 7. Fitur: Analisis Tren & Distribusi Laporan (Admin) - Feature ID: F07

| Feature ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Step Number | Steps Description | Expected Result |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| F07 | TC.Trend.001 | Menguji tampilan default halaman Tren & Distribusi Laporan | Positive | Admin melihat visualisasi tren data 7 hari terakhir secara default | Admin login, berada di Dashboard | 1 | Login sebagai admin utama. | Dashboard admin termuat. |
| | | | | | | 2 | Klik menu "Tren & Distribusi" pada sidebar admin. | Halaman analisis tren dan distribusi dimuat dengan sukses. |
| | | | | | | 3 | Periksa visual grafik batang (Bar Chart) tren per periode & status. | Grafik batang ter-render dengan sukses menampilkan tren laporan harian. |
| | | | | | | 4 | Verifikasi rentang waktu default yang ditampilkan pada sumbu X grafik. | Sumbu X menampilkan 7 hari terakhir secara berturut-turut dikelompokkan berdasarkan status masing-masing. |
| F07 | TC.Trend.002 | Menguji filter perubahan periode analisis data tren | Positive | Admin menyaring tren data berdasarkan rentang waktu yang berbeda | Admin berada di halaman Tren & Distribusi Laporan | 1 | Cari dropdown pilihan periode filter di bagian atas grafik. | Dropdown pilihan periode ditemukan. |
| | | | | | | 2 | Klik dropdown, pilih opsi "30 Hari Terakhir", lalu klik tombol "Filter". | Halaman memproses query dan memuat ulang grafik batang dengan 30 titik data harian. |
| | | | | | | 3 | Klik dropdown periode kembali, pilih opsi "1 Tahun", lalu klik tombol "Filter". | Halaman memproses query bulanan tahun berjalan. |
| | | | | | | 4 | Verifikasi visual sumbu X setelah memfilter 1 tahun. | Grafik batang sukses diperbarui menampilkan 12 titik data bulanan secara kronologis (Januari - Desember). |
| F07 | TC.Trend.003 | Memverifikasi pemuatan grafik distribusi status dan wilayah | Positive | Admin melihat persentase status laporan dan sebaran wilayah laporan teratas | Admin berada di halaman Tren & Distribusi Laporan | 1 | Scroll ke bagian bawah halaman Tren & Distribusi. | Menampilkan modul grafik distribusi tambahan. |
| | | | | | | 2 | Periksa grafik lingkaran (Pie Chart) distribusi status laporan. | Grafik lingkaran berhasil memuat proporsi status laporan secara melingkar (Pending, Valid, Diproses, Selesai, Ditolak). |
| | | | | | | 3 | Periksa bagian visualisasi distribusi wilayah laporan kebakaran. | Sistem menampilkan sebaran wilayah laporan teratas di wilayah Kalimantan. |
| | | | | | | 4 | Hover/arahkan kursor pada salah satu irisan pie chart status atau bar chart wilayah. | Tooltip muncul menampilkan angka jumlah laporan yang akurat untuk kategori/wilayah tersebut. |
| F07 | TC.Trend.004 | Menguji pembatasan hak akses halaman tren oleh non-Admin | Negative | Sistem memblokir peran non-Admin (Warga/Petugas) dari mengakses halaman tren | Pengguna login menggunakan peran Warga atau Petugas lapangan | 1 | Login ke sistem menggunakan akun Warga biasa. | Berhasil login sebagai warga. |
| | | | | | | 2 | Masukkan URL halaman tren secara manual pada address bar browser (contoh: `/admin/tren-distribusi`). | URL diketikkan. |
| | | | | | | 3 | Tekan Enter untuk mengirim request navigasi ke server. | Server memvalidasi middleware hak akses admin. |
| | | | | | | 4 | Periksa respons halaman yang ditampilkan browser. | Sistem memblokir akses dan mengembalikan respons "403 Forbidden" atau pesan error "Anda tidak memiliki akses ke halaman ini." |
| F07 | TC.Trend.005 | Mengirimkan parameter filter periode yang tidak valid/dimanipulasi | Negative | Sistem mengembalikan ke filter default jika dikirimi parameter periode yang salah | Admin berada di halaman Tren & Distribusi Laporan | 1 | Akses halaman Tren & Distribusi Laporan. | Halaman tren dimuat normal. |
| | | | | | | 2 | Ubah parameter periode di URL query secara manual (contoh: `/admin/tren-distribusi?period=invalid_value`). | URL termanipulasi disubmit ke browser. |
| | | | | | | 3 | Tekan Enter untuk memuat ulang halaman dengan parameter salah tersebut. | Server menerima request filter. |
| | | | | | | 4 | Periksa visual grafik dan query parameter setelah halaman termuat. | Sistem mendeteksi parameter tidak valid, otomatis kembali menerapkan filter default (7 hari terakhir), grafik dimuat normal, dan tidak memicu crash/error. |
| F07 | TC.Trend.006 | Mengakses halaman tren ketika database laporan masih kosong | Negative | Sistem memuat grafik dengan aman meskipun tidak memiliki data laporan | Database laporan dalam kondisi kosong (empty state) | 1 | Lakukan pembersihan database laporan sehingga tidak ada data laporan sama sekali. | Database laporan kosong. |
| | | | | | | 2 | Login sebagai admin dan klik menu "Tren & Distribusi". | Halaman tren berhasil dimuat tanpa kendala/crash. |
| | | | | | | 3 | Periksa visual grafik batang dan lingkaran Chart.js yang ter-render. | Grafik Chart.js ter-render dengan sukses menampilkan nilai nol (0) untuk semua kategori/kunci sumbu. |
| | | | | | | 4 | Buka Console tab pada Developer Tools browser dan periksa error log. | Tidak ada pesan error JavaScript ("undefined" atau "null pointer") yang terekam di konsol browser. |
| F07 | TC.Trend.007 | Validasi kecocokan jumlah total data status di Pie Chart dengan database | Validation | Memastikan data kuantitas status laporan yang disajikan Chart.js akurat | Admin berada di halaman Tren & Distribusi Laporan | 1 | Buka halaman Tren & Distribusi Laporan admin. | Halaman dimuat sukses. |
| | | | | | | 2 | Catat angka jumlah laporan pada setiap status di grafik (misal: Pending=2, Valid=5, Selesai=3 = total 10). | Angka dari grafik dicatat. |
| | | | | | | 3 | Buka tab database dan jalankan query penghitungan jumlah laporan per status secara langsung (COUNT group by status). | Hasil query database didapatkan. |
| | | | | | | 4 | Bandingkan data hasil query database dengan catatan angka dari grafik. | Angka total serta proporsi status di grafik terbukti sama persis dan akurat dengan jumlah data riil di database. |
| F07 | TC.Trend.008 | Validasi akurasi ekstraksi nama wilayah dari alamat lengkap | Validation | Memastikan parsing nama wilayah dari kolom alamat lengkap berfungsi dengan benar | Laporan memiliki alamat lengkap terstruktur (misal: "Samarinda, Kalimantan Timur") | 1 | Masuk ke form pembuatan laporan warga, isi alamat dengan teks: "Jl. Mulawarman, Tarakan, Kalimantan Utara". | Laporan dibuat dan disimpan di database. |
| | | | | | | 2 | Login sebagai admin dan buka menu "Tren & Distribusi". | Halaman tren termuat. |
| | | | | | | 3 | Perhatikan tabel/grafik distribusi wilayah laporan kebakaran di bagian bawah. | Grafik wilayah ter-render. |
| | | | | | | 4 | Periksa penambahan jumlah laporan pada kategori "Kalimantan Utara". | Laporan baru sukses terhitung di bawah kategori "Kalimantan Utara" (sistem mem-parsing alamat setelah koma terakhir dengan benar). |
| F07 | TC.Trend.009 | Validasi skalabilitas visual Chart.js pada berbagai resolusi layar | Validation | Memastikan grafik tren ter-render dengan rapi di perangkat mobile maupun desktop | Admin membuka halaman tren | 1 | Buka halaman Tren & Distribusi Laporan pada browser desktop. | Halaman dimuat dengan tata letak desktop standar. |
| | | | | | | 2 | Tekan F12 untuk membuka Developer Tools, lalu aktifkan mode simulasi layar perangkat mobile (Responsive / width: 375px). | Layout halaman berubah menyesuaikan lebar layar sempit. |
| | | | | | | 3 | Scroll halaman untuk melihat grafik Chart.js. | Grafik menyusut secara otomatis dan proporsional (responsive resize). |
| | | | | | | 4 | Periksa keterbacaan legenda (legend) dan label sumbu X/Y grafik. | Keterangan label dan legenda tetap terbaca dengan jelas tanpa tumpang tindih satu sama lain. |
