<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * AdminDashboardTest
 *
 * Skenario yang diuji:
 *  1. Admin dapat melihat dashboard dan jumlah laporan (stat cards).
 *  2. Admin dapat melihat statistik laporan (grafik & distribusi status).
 *  3. Admin dapat melihat status penugasan petugas pada halaman detail laporan.
 */
class AdminDashboardTest extends DuskTestCase
{
    use DatabaseMigrations;

    // TEST 1 — Admin dapat melihat dashboard jumlah laporan (stat cards)

    public function test_admin_dapat_melihat_dashboard_jumlah_laporan(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Dashboard',
            'email'      => 'admindash@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'admin',
            'phone'      => '081211111101',
        ]);

        $pelapor = User::factory()->create([
            'users_name' => 'Warga Satu',
            'email'      => 'warga1@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'masyarakat',
            'phone'      => '081211111102',
        ]);

        Report::factory()->count(2)->create(['user_id' => $pelapor->users_id, 'status' => 'pending']);
        Report::factory()->count(2)->create(['user_id' => $pelapor->users_id, 'status' => 'valid']);
        Report::factory()->create(['user_id' => $pelapor->users_id, 'status' => 'diproses']);
        Report::factory()->create(['user_id' => $pelapor->users_id, 'status' => 'selesai']);
        Report::factory()->create(['user_id' => $pelapor->users_id, 'status' => 'ditolak']);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit(route('admin.dashboard'))
                    ->pause(2000)
                    ->assertSee('Laporan Masuk Hari Ini')
                    ->assertSee('Laporan Menunggu Verifikasi')
                    ->assertSee('Laporan Sedang Ditangani')
                    ->assertSee('Laporan Valid')
                    ->assertSee('Laporan Selesai')
                    ->assertSee('Laporan Ditolak')
                    ->assertSee('Laporan Belum Ditugaskan')
                    ->assertSee('Total Laporan')
                    ->screenshot('admin-dashboard-jumlah-laporan');
        });
    }

    // TEST 2 — Admin dapat melihat statistik laporan (grafik & distribusi status)

    public function test_admin_dapat_melihat_statistik_laporan(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Statistik',
            'email'      => 'adminstat@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'admin',
            'phone'      => '081211111103',
        ]);

        $pelapor = User::factory()->create([
            'users_name' => 'Warga Dua',
            'email'      => 'warga2@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'masyarakat',
            'phone'      => '081211111104',
        ]);

        Report::factory()->count(2)->create([
            'user_id'    => $pelapor->users_id,
            'status'     => 'pending',
            'fire_level' => 'high',
            'address'    => 'Jl. Mawar, Balikpapan',
        ]);

        Report::factory()->count(2)->create([
            'user_id'    => $pelapor->users_id,
            'status'     => 'diproses',
            'fire_level' => 'medium',
            'address'    => 'Jl. Melati, Pontianak',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit(route('admin.dashboard'))
                    ->pause(2000)
                    ->assertSee('Laporan Karhutla Per Periode')
                    ->assertSee('Distribusi Status Laporan')
                    ->assertSee('Insight Kondisi Utama')
                    ->assertSee('Laporan Masuk')
                    ->assertSee('DESKRIPSI')
                    ->assertSee('URGENSI')
                    ->assertSee('LOKASI')
                    ->assertSee('STATUS')
                    ->assertSee('TANGGAL PELAPORAN')
                    ->assertSee('AKSI')
                    ->assertSeeIn('select[name="status"]', 'Status')
                    ->assertSeeIn('select[name="status"]', 'Pending (Menunggu)')
                    ->assertSeeIn('select[name="status"]', 'Valid')
                    ->assertSeeIn('select[name="status"]', 'Diproses')
                    ->assertSeeIn('select[name="status"]', 'Ditolak')
                    ->assertSeeIn('select[name="status"]', 'Selesai')
                    ->screenshot('admin-dashboard-statistik-laporan');
        });
    }

    // TEST 3 — Admin dapat melihat status penugasan petugas (sedang bertugas)

    public function test_admin_dapat_melihat_status_penugasan(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Penugasan',
            'email'      => 'admintugas@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'admin',
            'phone'      => '081211111105',
        ]);

        $pelapor = User::factory()->create([
            'users_name' => 'Warga Tiga',
            'email'      => 'warga3@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'masyarakat',
            'phone'      => '081211111106',
        ]);

        $petugas = User::factory()->create([
            'users_name' => 'Petugas Satu',
            'email'      => 'petugas1@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'petugas',
            'phone'      => '081211111107',
            'pos_name'   => 'Pos Induk Balikpapan',
            'latitude'   => -1.2654,
            'longitude'  => 116.8312,
        ]);

        $report = Report::factory()->create([
            'user_id'             => $pelapor->users_id,
            'title'               => 'Asap Tebal di Hutan',
            'description'         => 'Terdeteksi asap tebal dari kawasan hutan.',
            'status'              => 'diproses',
            'fire_level'          => 'high',
            'latitude'            => -1.2654,
            'longitude'           => 116.8312,
            'address'             => 'Kawasan Hutan Balikpapan',
            'assigned_petugas_id' => $petugas->users_id,
        ]);

        Penugasan::create([
            'report_id'    => $report->report_id,
            'petugas_id'   => $petugas->users_id,
            'assigned_at'  => now(),
            'completed_at' => null,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report, $petugas) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit(route('admin.reports.show', $report->report_id))
                    ->pause(2000)
                    ->assertSee('Asap Tebal di Hutan')
                    ->assertSee('#' . $report->report_id)
                    ->assertSee('Status Penugasan Petugas')
                    ->assertSee('Daftar petugas yang ditugaskan beserta status pekerjaannya.')
                    ->assertSee($petugas->users_name)
                    ->assertSee('Sedang Bertugas')
                    ->assertSee('Petugas Tersedia')
                    ->screenshot('admin-detail-status-penugasan');
        });
    }

    // TEST 4 — Admin melihat status penugasan "Selesai" jika petugas sudah selesai

    public function test_admin_melihat_status_penugasan_selesai(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Selesai',
            'email'      => 'adminselesai@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'admin',
            'phone'      => '081211111108',
        ]);

        $pelapor = User::factory()->create([
            'users_name' => 'Warga Empat',
            'email'      => 'warga4@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'masyarakat',
            'phone'      => '081211111109',
        ]);

        $petugas = User::factory()->create([
            'users_name' => 'Petugas Dua',
            'email'      => 'petugas2@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'petugas',
            'phone'      => '081211111110',
            'pos_name'   => 'Pos Daops Pontianak',
            'latitude'   => 0.0,
            'longitude'  => 109.34,
        ]);

        $report = Report::factory()->create([
            'user_id'             => $pelapor->users_id,
            'title'               => 'Kebakaran Lahan Gambut',
            'description'         => 'Lahan gambut terbakar di sekitar desa.',
            'status'              => 'selesai',
            'fire_level'          => 'medium',
            'latitude'            => 0.0,
            'longitude'           => 109.34,
            'address'             => 'Desa Gambut, Pontianak',
            'assigned_petugas_id' => $petugas->users_id,
        ]);

        Penugasan::create([
            'report_id'    => $report->report_id,
            'petugas_id'   => $petugas->users_id,
            'assigned_at'  => now()->subHours(3),
            'completed_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report, $petugas) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit(route('admin.reports.show', $report->report_id))
                    ->pause(2000)
                    ->assertSee('Status Penugasan Petugas')
                    ->assertSee($petugas->users_name)
                    ->assertSee('Selesai')
                    ->screenshot('admin-detail-penugasan-selesai');
        });
    }
}
