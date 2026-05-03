# Test Case Scenario

| FEATURE ID | Case ID | Test Scenario | Type | Test Case | Pre Condition | Steps | Steps Description | Expected Result |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| PBI #2 | TC.History.001 | Menguji fungsionalitas halaman Riwayat Laporan | Positive | User melihat daftar riwayat laporan yang sudah dibuat | User berhasil login dan berada di Dashboard | 1 | Tap/Click menu Riwayat Laporan pada sidebar/navbar. | Menu dapat diklik dan mengarahkan user ke halaman Riwayat Laporan. |
| | | | | | | 2 | View report list. | Riwayat Laporan screen displayed. Terdapat daftar laporan yang pernah dibuat berisi informasi singkat (contoh: tanggal, judul laporan, dan status). |
| PBI #2 | TC.History.002 | Menguji fungsionalitas halaman Riwayat Laporan kosong | Positive | User melihat halaman riwayat laporan ketika belum pernah membuat laporan | User berhasil login dan belum pernah membuat laporan sama sekali | 1 | Tap/Click menu Riwayat Laporan pada sidebar/navbar. | Menu dapat diklik dan mengarahkan user ke halaman Riwayat Laporan. |
| | | | | | | 2 | View report list. | Riwayat Laporan screen displayed. Menampilkan state kosong dengan pesan informatif (contoh: "Belum ada laporan yang dibuat"). |
| PBI #2 | TC.History.003 | Menguji keamanan akses halaman Riwayat Laporan | Negative | User (Guest) mengakses halaman riwayat laporan secara langsung tanpa login | User belum melakukan login / Guest | 1 | Input direct URL to Riwayat Laporan page on browser. | The page is not accessible. |
| | | | | | | 2 | Press Enter. | User is redirected to Login screen and an error message "Silakan login terlebih dahulu" is displayed. |
| PBI #3 | TC.Detail.001 | Menguji fungsionalitas melihat Detail Laporan | Positive | User melihat detail spesifik dari sebuah laporan | User berada di halaman Riwayat Laporan dan terdapat minimal 1 daftar laporan | 1 | Tap/Click salah satu item atau tombol "Lihat Detail" pada daftar laporan. | Tombol detail dapat diklik dan mengarahkan user ke halaman Detail Laporan. |
| | | | | | | 2 | View detail information. | Detail Laporan screen displayed. Menampilkan informasi detail laporan secara lengkap dan akurat (foto bukti, isi laporan lengkap, tanggal, status saat ini, dan tanggapan dari petugas). |
| PBI #3 | TC.Detail.002 | Menguji akses URL Detail Laporan dengan ID tidak valid | Negative | User mengakses URL detail laporan menggunakan ID yang tidak terdaftar di sistem | User berhasil login | 1 | Input direct URL to Detail Laporan page with an invalid/non-existent ID (e.g. /detail/9999). | URL entered. |
| | | | | | | 2 | Press Enter. | System displays a 404 Not Found error page or an error message "Laporan tidak ditemukan". |
| PBI #3 | TC.Detail.003 | Menguji keamanan akses Detail Laporan milik user lain | Negative | User mencoba melihat detail laporan milik akun pengguna lain | User berhasil login dan mengetahui ID laporan milik user lain | 1 | Input direct URL to Detail Laporan page using the ID of a report created by another user. | URL entered. |
| | | | | | | 2 | Press Enter. | System blocks access, redirects user, or displays a 403 Forbidden / "Akses ditolak" error message. |
