<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;

class AdminDashboardFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can view dashboard statistics and filter by period.
     */
    public function test_admin_can_view_dashboard_stats_and_filter_periods(): void
    {
        // 1. Setup Admin
        $admin = User::create([
            'users_name' => 'Admin Test',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567899'
        ]);

        // 2. Setup Pelapor and Reports
        $pelapor = User::create([
            'users_name' => 'Warga Test',
            'email' => 'warga.test@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'masyarakat',
            'phone' => '081234567898'
        ]);

        $report1 = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Lahan 1',
            'description' => 'Ada asap tebal di koordinat berikut',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'address' => 'Jalan Sudirman',
            'status' => 'pending',
        ]);

        $report2 = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Lahan 2',
            'description' => 'Asap hitam membumbung tinggi',
            'latitude' => '-6.300000',
            'longitude' => '106.820000',
            'address' => 'Jalan Thamrin',
            'status' => 'diproses',
        ]);

        // 3. Request Admin Dashboard
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Assert stats exist on page
        $response->assertSee('Total Laporan');
        $response->assertSee('Laporan Valid');
        $response->assertSee('Laporan Selesai');
        $response->assertSee('Laporan Ditolak');

        // 4. Request with period filters
        $response7Days = $this->get(route('admin.dashboard', ['period' => '7days']));
        $response7Days->assertStatus(200);

        $response30Days = $this->get(route('admin.dashboard', ['period' => '30days']));
        $response30Days->assertStatus(200);

        $responseMonth = $this->get(route('admin.dashboard', ['period' => 'month']));
        $responseMonth->assertStatus(200);

        $responseYear = $this->get(route('admin.dashboard', ['period' => 'year']));
        $responseYear->assertStatus(200);
    }

    /**
     * Test admin can view assignment status on report details.
     */
    public function test_admin_can_view_assignment_status_on_report_detail(): void
    {
        // 1. Setup Admin and Petugas
        $admin = User::create([
            'users_name' => 'Admin Test 2',
            'email' => 'admin2@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567897'
        ]);

        $petugas = User::create([
            'users_name' => 'Petugas Test',
            'email' => 'petugas.test@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567896'
        ]);

        $pelapor = User::create([
            'users_name' => 'Warga Test',
            'email' => 'warga2@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'masyarakat',
            'phone' => '081234567895'
        ]);

        // 2. Setup Report
        $report = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Hutan Jati',
            'description' => 'Kebakaran hutan luas',
            'latitude' => '-6.400000',
            'longitude' => '106.830000',
            'address' => 'Hutan Jati',
            'status' => 'diproses',
        ]);

        // 3. Create Penugasan
        $penugasan = Penugasan::create([
            'report_id' => $report->report_id,
            'petugas_id' => $petugas->users_id,
            'assigned_at' => now(),
        ]);

        // 4. Request Admin Report Show Page
        $response = $this->actingAs($admin)->get(route('admin.reports.show', $report->report_id));
        $response->assertStatus(200);

        // Assert penugasan details exist on page
        $response->assertSee('Status Penugasan Petugas');
        $response->assertSee($petugas->users_name);
        $response->assertSee('Sedang Bertugas');

        // 5. Complete assignment and verify
        $penugasan->update([
            'completed_at' => now(),
            'bukti_photo' => 'photos/test_bukti.jpg'
        ]);

        $responseCompleted = $this->get(route('admin.reports.show', $report->report_id));
        $responseCompleted->assertStatus(200);
        $responseCompleted->assertSee('Selesai');
        $responseCompleted->assertSee('Lihat Bukti Foto');
    }

    /**
     * Test admin can filter reports by region, status, and date.
     */
    public function test_admin_can_filter_reports_by_region_status_and_date(): void
    {
        $admin = User::create([
            'users_name' => 'Admin Filter Test',
            'email' => 'admin.filter@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567899'
        ]);

        $pelapor = User::create([
            'users_name' => 'Warga Filter Test',
            'email' => 'warga.filter@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'masyarakat',
            'phone' => '081234567898'
        ]);

        // Report 1: Pontianak, valid, created today
        $report1 = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Pontianak',
            'description' => 'Kebakaran di Pontianak Barat',
            'latitude' => '-0.026330',
            'longitude' => '109.342500',
            'address' => 'Jl. Gajah Mada, Pontianak',
            'status' => 'valid',
            'created_at' => now(),
        ]);

        // Report 2: Samarinda, selesai, created yesterday
        $report2 = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Samarinda',
            'description' => 'Kebakaran di Samarinda Ulu',
            'latitude' => '-0.502106',
            'longitude' => '117.153661',
            'address' => 'Jl. Bhayangkara, Samarinda',
            'status' => 'selesai',
        ]);
        $report2->created_at = now()->subDay();
        $report2->save();

        $this->actingAs($admin);

        // 1. Filter by Region (Pontianak)
        $responseRegion = $this->get(route('admin.dashboard', ['region' => 'Pontianak']));
        $responseRegion->assertStatus(200);
        $responseRegion->assertSee('Kebakaran di Pontianak Barat');
        $responseRegion->assertDontSee('Kebakaran di Samarinda Ulu');

        // 2. Filter by Status (selesai)
        $responseStatus = $this->get(route('admin.dashboard', ['status' => 'selesai']));
        $responseStatus->assertStatus(200);
        $responseStatus->assertSee('Kebakaran di Samarinda Ulu');
        $responseStatus->assertDontSee('Kebakaran di Pontianak Barat');

        // 3. Filter by Date (yesterday)
        $responseDate = $this->get(route('admin.dashboard', ['date' => now()->subDay()->format('Y-m-d'), 'status' => 'selesai']));
        $responseDate->assertStatus(200);
        $responseDate->assertSee('Kebakaran di Samarinda Ulu');
        $responseDate->assertDontSee('Kebakaran di Pontianak Barat');

        // 4. Test on index page too
        $responseIndex = $this->get(route('admin.reports.index', ['region' => 'Samarinda']));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Kebakaran di Samarinda Ulu');
        $responseIndex->assertDontSee('Kebakaran di Pontianak Barat');
    }

    /**
     * Test admin can reassign petugas and updates database state properly.
     */
    public function test_admin_can_reassign_petugas_and_creates_status_history(): void
    {
        // 1. Setup Admin
        $admin = User::create([
            'users_name' => 'Admin Utama',
            'email' => 'admin@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567899'
        ]);

        // 2. Setup 2 Petugas
        $petugas1 = User::create([
            'users_name' => 'Petugas Satu',
            'email' => 'petugas1@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567891'
        ]);

        $petugas2 = User::create([
            'users_name' => 'Petugas Dua',
            'email' => 'petugas2@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567892'
        ]);

        // 3. Setup Pelapor and Report
        $pelapor = User::create([
            'users_name' => 'Warga Pelapor',
            'email' => 'warga@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
            'phone' => '089876543210'
        ]);

        $report = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Hutan Kalimantan',
            'description' => 'Titik api terdeteksi sangat besar',
            'latitude' => '-1.250000',
            'longitude' => '116.830000',
            'address' => 'Balikpapan',
            'status' => 'diproses',
            'assigned_petugas_id' => $petugas1->users_id,
        ]);

        // Create initial penugasan for petugas1
        $penugasan1 = Penugasan::create([
            'report_id' => $report->report_id,
            'petugas_id' => $petugas1->users_id,
            'assigned_at' => now(),
        ]);

        // Act: Reassign to petugas2
        $response = $this->actingAs($admin)
                         ->post(route('admin.reports.reassign', [$report->report_id, $petugas2->users_id]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert that the assigned petugas in reports is now petugas2
        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'assigned_petugas_id' => $petugas2->users_id,
            'status' => 'diproses'
        ]);

        // Assert that the old penugasan for petugas1 has been deleted or completed
        $this->assertDatabaseMissing('penugasan', [
            'report_id' => $report->report_id,
            'petugas_id' => $petugas1->users_id,
            'completed_at' => null
        ]);

        // Assert that a new penugasan for petugas2 is created
        $this->assertDatabaseHas('penugasan', [
            'report_id' => $report->report_id,
            'petugas_id' => $petugas2->users_id,
            'completed_at' => null
        ]);

        // Assert status history records the change
        $this->assertDatabaseHas('status_histories', [
            'report_id' => $report->report_id,
            'status_awal' => 'diproses',
            'status_baru' => 'diproses',
            'catatan' => 'Penugasan petugas diubah dari Petugas Satu menjadi Petugas Dua.',
            'diubah_oleh' => 'Admin Utama (Admin Sistem)'
        ]);
    }
}

