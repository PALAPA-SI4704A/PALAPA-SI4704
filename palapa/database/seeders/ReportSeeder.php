<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use App\Models\Penugasan;
use App\Models\StatusHistory;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data users yang diperlukan
        $wargaAktif = User::where('email', 'warga.aktif@example.com')->first();
        $wargaBaru = User::where('email', 'warga.baru@example.com')->first();
        $wargaValid = User::where('email', 'warga.valid@example.com')->first();
        $wargaProses = User::where('email', 'warga.proses@example.com')->first();
        $wargaSelesai = User::where('email', 'warga.selesai@example.com')->first();

        $admin = User::where('email', 'admin@example.com')->first();
        $petugasList = User::where('role', 'petugas')->orderBy('users_id', 'asc')->get();

        if (!$wargaAktif || !$wargaBaru || !$wargaValid || !$wargaProses || !$wargaSelesai || !$admin || $petugasList->isEmpty()) {
            $this->command->error('Pastikan AdminSeeder, PetugasSeeder, dan WargaSeeder sudah dijalankan terlebih dahulu!');
            return;
        }

        // ==========================================
        // 1. DATA LAPORAN UNTUK WARGA AKTIF (10 Laporan di Wilayah Kalimantan)
        // ==========================================

        // Laporan 1: Pending (Kebakaran Ruko Gajah Mada Pontianak)
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kebakaran Ruko di Jalan Gajah Mada Pontianak',
            'description' => 'Terjadi kebakaran besar di deretan ruko Gajah Mada, api terlihat membesar di lantai 2 ruko nomor 12.',
            'fire_level' => 'high',
            'status' => 'pending',
            'address' => 'Jl. Gajah Mada No. 12, Benua Melayu Darat, Kec. Pontianak Selatan, Kota Pontianak, Kalimantan Barat',
            'latitude' => -0.026330,
            'longitude' => 109.342503,
            'photo' => 'photos/seeder_ruko_pontianak.jpg',
            'creator_name' => $wargaAktif->users_name,
        ]);

        // Laporan 2: Valid/Verified (Kebakaran Kabel Listrik Samarinda)
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kabel Listrik Korslet dekat Kantor Gubernur Kaltim',
            'description' => 'Kabel tiang listrik mengeluarkan percikan api besar dan mulai membakar dahan pohon di sekitarnya.',
            'fire_level' => 'medium',
            'status' => 'valid',
            'address' => 'Jl. Gajah Mada No. 2, Jawa, Kec. Samarinda Ulu, Kota Samarinda, Kalimantan Timur',
            'latitude' => -0.499824,
            'longitude' => 117.138865,
            'photo' => 'photos/seeder_kabel_samarinda.jpg',
            'creator_name' => $wargaAktif->users_name,
            'admin_id' => $admin->users_id,
        ]);

        // Laporan 3: Diproses/In Progress (Kebakaran Gudang Kayu Balikpapan)
        $petugas1 = $petugasList->get(0) ?? $petugasList->first();
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kebakaran Gudang Kayu di Balikpapan',
            'description' => 'Asap hitam tebal membubung tinggi dari gudang penyimpanan kayu dekat pelabuhan. Potensi merambat ke pemukiman warga sangat tinggi.',
            'fire_level' => 'critical',
            'status' => 'diproses',
            'address' => 'Jl. Jenderal Sudirman No. 88, Klandasan Ulu, Kec. Balikpapan Kota, Kota Balikpapan, Kalimantan Timur',
            'latitude' => -1.265386,
            'longitude' => 116.831200,
            'photo' => 'photos/seeder_gudang_balikpapan.jpg',
            'creator_name' => $wargaAktif->users_name,
            'admin_id' => $admin->users_id,
            'assigned_petugas_id' => $petugas1->users_id,
            'handling_note' => 'Tim armada regu 1 BPBD Kota Balikpapan sedang meluncur ke lokasi kejadian membawa 3 unit mobil pemadam.',
        ]);

        // Laporan 4: Selesai/Resolved (Lahan Gambut Palangka Raya)
        $petugas2 = $petugasList->get(1) ?? $petugasList->first();
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kebakaran Lahan Gambut di Palangka Raya',
            'description' => 'Lahan gambut kering terbakar hebat dekat pemukiman warga, asap pekat mulai menutupi jalan raya utama.',
            'fire_level' => 'critical',
            'status' => 'selesai',
            'address' => 'Jl. Yos Sudarso No. 2, Menteng, Kec. Jekan Raya, Kota Palangka Raya, Kalimantan Tengah',
            'latitude' => -2.208222,
            'longitude' => 113.916861,
            'photo' => 'photos/seeder_lahan_palangkaraya.jpg',
            'creator_name' => $wargaAktif->users_name,
            'admin_id' => $admin->users_id,
            'assigned_petugas_id' => $petugas2->users_id,
            'handling_note' => 'Api di atas permukaan gambut berhasil dipadamkan sepenuhnya. Dilakukan penyiraman air mendalam ke dalam tanah gambut untuk memutus bara api tersembunyi.',
            'bukti_foto' => 'bukti_penanganan/seeder_bukti_gambut.jpg',
        ]);

        // Laporan 5: Ditolak/Invalid (Kebakaran Palsu Siring Banjarmasin)
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kebakaran di Siring Menara Pandang Banjarmasin',
            'description' => 'Tolong pak ada kebakaran hebat di menara pandang Siring Pierre Tendean sekarang juga!',
            'fire_level' => 'low',
            'status' => 'ditolak',
            'address' => 'Siring Menara Pandang, Jl. Kapten Piere Tendean, Gadang, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan',
            'latitude' => -3.319875,
            'longitude' => 114.596001,
            'photo' => 'photos/seeder_palsu_banjarmasin.jpg',
            'creator_name' => $wargaAktif->users_name,
            'admin_id' => $admin->users_id,
            'rejection_reason' => 'Laporan palsu. Petugas pemadam di pos terdekat telah mengecek langsung ke Siring dan situasi aman kondusif, hanya ada anak-anak bermain kembang api/flare.',
        ]);

        // Laporan 6: Pending (Tabung Gas Singkawang)
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kebakaran Tabung Gas Warung Makan Singkawang',
            'description' => 'Kebakaran kecil terjadi di bagian dapur warung makan akibat selang tabung gas melon bocor saat memasak.',
            'fire_level' => 'medium',
            'status' => 'pending',
            'address' => 'Jl. Diponegoro No. 4, Pasiran, Kec. Singkawang Barat, Kota Singkawang, Kalimantan Barat',
            'latitude' => 0.906944,
            'longitude' => 108.971944,
            'photo' => 'photos/seeder_tabung_singkawang.jpg',
            'creator_name' => $wargaAktif->users_name,
        ]);

        // Laporan 7: Valid/Verified (Ruko Banjarbaru)
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kebakaran Ruko Toko Elektronik Banjarbaru',
            'description' => 'Korsleting listrik menyebabkan kebakaran di bagian panel listrik ruko elektronik lantai 1.',
            'fire_level' => 'high',
            'status' => 'valid',
            'address' => 'Jl. Jenderal Ahmad Yani Km. 33, Loktabat Utara, Kec. Banjarbaru Utara, Kota Banjarbaru, Kalimantan Selatan',
            'latitude' => -3.442222,
            'longitude' => 114.830278,
            'photo' => 'photos/seeder_ruko_banjarbaru.jpg',
            'creator_name' => $wargaAktif->users_name,
            'admin_id' => $admin->users_id,
        ]);

        // Laporan 8: Diproses/In Progress (Lahan Gambut Ketapang)
        $petugas3 = $petugasList->get(2) ?? $petugasList->first();
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kebakaran Lahan Gambut di Ketapang',
            'description' => 'Kebakaran lahan gambut kering meluas akibat cuaca panas ekstrem, kepulan asap tebal mengganggu jarak pandang warga.',
            'fire_level' => 'high',
            'status' => 'diproses',
            'address' => 'Jl. Jenderal Sudirman, Mulia Baru, Kec. Delta Pawan, Kabupaten Ketapang, Kalimantan Barat',
            'latitude' => -1.848834,
            'longitude' => 109.982245,
            'photo' => 'photos/seeder_lahan_ketapang.jpg',
            'creator_name' => $wargaAktif->users_name,
            'admin_id' => $admin->users_id,
            'assigned_petugas_id' => $petugas3->users_id,
            'handling_note' => 'Regu pemadam sedang membuat sekat kanal air di sekitar lokasi agar api tidak menjalar ke lahan kelapa sawit warga.',
        ]);

        // Laporan 9: Selesai/Resolved (Kebakaran Semak Bandara Tarakan)
        $petugas4 = $petugasList->get(3) ?? $petugasList->first();
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kebakaran Hutan Dekat Bandara Tarakan',
            'description' => 'Terjadi kebakaran semak belukar dekat pagar pembatas bandara, asap pekat masuk ke area landasan pacu penerbangan.',
            'fire_level' => 'high',
            'status' => 'selesai',
            'address' => 'Jl. Mulawarman, Karang Anyar Pantai, Kec. Tarakan Barat, Kota Tarakan, Kalimantan Utara',
            'latitude' => 3.321876,
            'longitude' => 117.576822,
            'photo' => 'photos/seeder_semak_tarakan.jpg',
            'creator_name' => $wargaAktif->users_name,
            'admin_id' => $admin->users_id,
            'assigned_petugas_id' => $petugas4->users_id,
            'handling_note' => 'Kebakaran semak belukar berhasil dipadamkan total dengan bantuan 2 unit armada water cannon pemadam bandara dan BPBD.',
            'bukti_foto' => 'bukti_penanganan/seeder_bukti_tarakan.jpg',
        ]);

        // Laporan 10: Ditolak/Invalid (Kebakaran Daun Kering Tanjung Selor)
        $this->createReport([
            'user_id' => $wargaAktif->users_id,
            'title' => 'Kebakaran Daun Kering di Tanjung Selor',
            'description' => 'Tumpukan daun kering terbakar merembet ke pagar bambu pembatas rumah warga.',
            'fire_level' => 'low',
            'status' => 'ditolak',
            'address' => 'Jl. Kolonel Soetadji No. 8, Tanjung Selor Hilir, Kec. Tanjung Selor, Kabupaten Bulungan, Kalimantan Utara',
            'latitude' => 2.836111,
            'longitude' => 117.364444,
            'photo' => 'photos/seeder_daun_tanjungselor.jpg',
            'creator_name' => $wargaAktif->users_name,
            'admin_id' => $admin->users_id,
            'rejection_reason' => 'Laporan ditolak karena pemilik rumah telah memadamkan api secara mandiri menggunakan ember sebelum unit pemadam tiba di lokasi.',
        ]);


        // ==========================================
        // 2. DATA LAPORAN UNTUK WARGA BARU (1 Laporan - Pending)
        // ==========================================
        $this->createReport([
            'user_id' => $wargaBaru->users_id,
            'title' => 'Kebakaran Rumah Panggung di Samarinda Seberang',
            'description' => 'Kebakaran rumah kayu/panggung warga di pinggir Sungai Mahakam, api berkobar sangat besar dan rawan merembet cepat.',
            'fire_level' => 'high',
            'status' => 'pending',
            'address' => 'Jl. Bung Tomo, Sungai Keledang, Kec. Samarinda Seberang, Kota Samarinda, Kalimantan Timur',
            'latitude' => -0.518290,
            'longitude' => 117.124503,
            'photo' => 'photos/seeder_rumah_samarinda.jpg',
            'creator_name' => $wargaBaru->users_name,
        ]);


        // ==========================================
        // 3. DATA LAPORAN UNTUK WARGA VALID (1 Laporan - Valid)
        // ==========================================
        $this->createReport([
            'user_id' => $wargaValid->users_id,
            'title' => 'Asap Tebal dari Gudang Ban Balikpapan',
            'description' => 'Terlihat asap hitam pekat tebal membumbung dari dalam gudang penyimpanan ban bekas industri.',
            'fire_level' => 'medium',
            'status' => 'valid',
            'address' => 'Jl. Soekarno Hatta Km. 2, Muara Rapak, Kec. Balikpapan Utara, Kota Balikpapan, Kalimantan Timur',
            'latitude' => -1.238923,
            'longitude' => 116.842738,
            'photo' => 'photos/seeder_ban_balikpapan.jpg',
            'creator_name' => $wargaValid->users_name,
            'admin_id' => $admin->users_id,
        ]);


        // ==========================================
        // 4. DATA LAPORAN UNTUK WARGA PROSES (1 Laporan - Diproses)
        // ==========================================
        $petugas5 = $petugasList->get(4) ?? $petugasList->first();
        $this->createReport([
            'user_id' => $wargaProses->users_id,
            'title' => 'Kebakaran Hutan Lindung Bukit Soeharto',
            'description' => 'Titik panas terpantau membakar vegetasi semak belukar di kawasan konservasi Hutan Lindung Bukit Soeharto.',
            'fire_level' => 'critical',
            'status' => 'diproses',
            'address' => 'Kawasan Tahura Bukit Soeharto, Batuah, Kec. Loa Janan, Kabupaten Kutai Kartanegara, Kalimantan Timur',
            'latitude' => -0.852934,
            'longitude' => 117.039234,
            'photo' => 'photos/seeder_bukit_soeharto.jpg',
            'creator_name' => $wargaProses->users_name,
            'admin_id' => $admin->users_id,
            'assigned_petugas_id' => $petugas5->users_id,
            'handling_note' => 'Regu pemadam kebakaran hutan Manggala Agni dikerahkan ke titik koordinat kebakaran untuk penyemprotan air intensif dan pembuatan sekat bakar.',
        ]);


        // ==========================================
        // 5. DATA LAPORAN UNTUK WARGA SELESAI (1 Laporan - Selesai)
        // ==========================================
        $petugas6 = $petugasList->get(5) ?? $petugasList->first();
        $this->createReport([
            'user_id' => $wargaSelesai->users_id,
            'title' => 'Korsleting Listrik Kantor Dinas Pontianak',
            'description' => 'Muncul percikan api dari mesin AC di ruang staf lantai 2 kantor dinas, asap putih mulai memenuhi ruangan kantor.',
            'fire_level' => 'medium',
            'status' => 'selesai',
            'address' => 'Jl. Ahmad Yani No. 111, Bansir Darat, Kec. Pontianak Tenggara, Kota Pontianak, Kalimantan Barat',
            'latitude' => -0.054923,
            'longitude' => 109.356238,
            'photo' => 'photos/seeder_kantor_pontianak.jpg',
            'creator_name' => $wargaSelesai->users_name,
            'admin_id' => $admin->users_id,
            'assigned_petugas_id' => $petugas6->users_id,
            'handling_note' => 'Mesin AC korslet berhasil dipadamkan menggunakan tabung APAR CO2 oleh sekuriti kantor dibantu dinas pemadam. Aliran listrik ruangan diputus sementara.',
            'bukti_foto' => 'bukti_penanganan/seeder_bukti_kantor.jpg',
        ]);
    }

    /**
     * Helper to create report and populate associated tables (StatusHistory, Penugasan)
     */
    private function createReport(array $data): void
    {
        // 1. Buat Laporan utama
        // Booted event akan otomatis membuat status history awal 'pending' dengan pesan 'Laporan berhasil dibuat oleh pelapor.'
        // Kita sesuaikan hal tersebut dengan status awal yang diinginkan.
        
        $reportData = [
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'fire_level' => $data['fire_level'],
            'status' => $data['status'],
            'address' => $data['address'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'photo' => $data['photo'],
            'admin_id' => $data['admin_id'] ?? null,
            'rejection_reason' => $data['rejection_reason'] ?? null,
            'assigned_petugas_id' => $data['assigned_petugas_id'] ?? null,
            'handling_note' => $data['handling_note'] ?? null,
            'bukti_foto' => $data['bukti_foto'] ?? null,
        ];

        $report = Report::create($reportData);

        // Hapus history awal yang dibuat otomatis agar kita bisa menyusun timeline yang rapi secara kronologis
        $report->statusHistories()->delete();

        // 2. Tambah History Awal (Pending)
        $report->statusHistories()->create([
            'status_awal' => null,
            'status_baru' => 'pending',
            'catatan' => 'Laporan berhasil dibuat oleh pelapor.',
            'diubah_oleh' => $data['creator_name'] . ' (Pelapor)',
            'tanggal_ubah' => now()->subHours(5),
            'created_at' => now()->subHours(5),
            'updated_at' => now()->subHours(5),
        ]);

        // 3. Jika status bukan pending, buat transisi selanjutnya
        if ($data['status'] === 'valid' || $data['status'] === 'diproses' || $data['status'] === 'selesai') {
            $adminUser = User::find($data['admin_id']);
            $adminName = $adminUser ? $adminUser->users_name : 'Admin Utama';
            
            $report->statusHistories()->create([
                'status_awal' => 'pending',
                'status_baru' => 'valid',
                'catatan' => 'Laporan telah diverifikasi dan dinyatakan valid.',
                'diubah_oleh' => $adminName . ' (Admin Sistem)',
                'tanggal_ubah' => now()->subHours(4),
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ]);
        }

        if ($data['status'] === 'diproses' || $data['status'] === 'selesai') {
            $petugasUser = User::find($data['assigned_petugas_id']);
            $petugasName = $petugasUser ? $petugasUser->users_name : 'Petugas Lapangan';

            // Tambah penugasan petugas ke tabel penugasan
            Penugasan::create([
                'report_id' => $report->report_id,
                'petugas_id' => $data['assigned_petugas_id'],
                'assigned_at' => now()->subHours(3),
                'completed_at' => ($data['status'] === 'selesai') ? now()->subHours(1) : null,
                'bukti_photo' => ($data['status'] === 'selesai') ? ($data['bukti_foto'] ?? null) : null,
            ]);

            $report->statusHistories()->create([
                'status_awal' => 'valid',
                'status_baru' => 'diproses',
                'catatan' => $data['handling_note'] ?? 'Laporan sedang diverifikasi oleh admin dan diteruskan ke petugas lapangan.',
                'diubah_oleh' => $petugasName . ' (Petugas Pemadam)',
                'tanggal_ubah' => now()->subHours(3),
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ]);
        }

        if ($data['status'] === 'selesai') {
            $petugasUser = User::find($data['assigned_petugas_id']);
            $petugasName = $petugasUser ? $petugasUser->users_name : 'Petugas Lapangan';

            $report->statusHistories()->create([
                'status_awal' => 'diproses',
                'status_baru' => 'selesai',
                'catatan' => 'Laporan selesai ditangani. Status: Selesai/Resolved.',
                'diubah_oleh' => $petugasName . ' (Petugas Pemadam)',
                'tanggal_ubah' => now()->subHours(1),
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ]);
        }

        if ($data['status'] === 'ditolak') {
            $adminUser = User::find($data['admin_id']);
            $adminName = $adminUser ? $adminUser->users_name : 'Admin Utama';

            $report->statusHistories()->create([
                'status_awal' => 'pending',
                'status_baru' => 'ditolak',
                'catatan' => 'Laporan ditolak. Alasan: ' . ($data['rejection_reason'] ?? 'Informasi tidak valid.'),
                'diubah_oleh' => $adminName . ' (Admin Sistem)',
                'tanggal_ubah' => now()->subHours(4),
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ]);
        }
    }
}
