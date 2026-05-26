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
}
