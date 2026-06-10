<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;

class AdminOfficerDutyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_assign_on_duty_officer(): void
    {
        // 1. Setup Admin & Petugas
        $admin = User::create([
            'users_name' => 'Admin Test',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567899'
        ]);

        $petugas = User::create([
            'users_name' => 'Petugas Sibuk',
            'email' => 'petugas.sibuk@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567898'
        ]);

        $pelapor = User::create([
            'users_name' => 'Warga Test',
            'email' => 'warga.test@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'masyarakat',
            'phone' => '081234567897'
        ]);

        // Report 1 is already handled by Petugas Sibuk
        $report1 = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Lahan 1',
            'description' => 'Ada asap tebal di koordinat berikut',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'address' => 'Jalan Sudirman',
            'status' => 'diproses',
            'assigned_petugas_id' => $petugas->users_id,
        ]);

        $penugasan = Penugasan::create([
            'report_id' => $report1->report_id,
            'petugas_id' => $petugas->users_id,
            'assigned_at' => now(),
        ]);

        // Report 2 is a new valid report
        $report2 = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Lahan 2',
            'description' => 'Kebakaran baru terjadi',
            'latitude' => '-6.300000',
            'longitude' => '106.820000',
            'address' => 'Jalan Thamrin',
            'status' => 'valid',
        ]);

        // Act: Try to assign the already busy Petugas Sibuk to Report 2
        $response = $this->actingAs($admin)
            ->post(route('admin.reports.assign', ['report' => $report2->report_id, 'petugas' => $petugas->users_id]));

        // Assert: Redirection back with errors and DB state remains unchanged
        $response->assertRedirect();
        $response->assertSessionHasErrors(['error']);
        $this->assertDatabaseMissing('reports', [
            'report_id' => $report2->report_id,
            'assigned_petugas_id' => $petugas->users_id,
            'status' => 'diproses'
        ]);
    }

    public function test_admin_dashboard_shows_unassigned_reports_count_and_filters_correctly(): void
    {
        $admin = User::create([
            'users_name' => 'Admin Test',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567899'
        ]);

        $pelapor = User::create([
            'users_name' => 'Warga Test',
            'email' => 'warga.test@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'masyarakat',
            'phone' => '081234567897'
        ]);

        // Report 1: valid & unassigned
        $report1 = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Unassigned',
            'description' => 'Kebakaran belum ditugaskan',
            'latitude' => '-6.300000',
            'longitude' => '106.820000',
            'address' => 'Jalan Thamrin',
            'status' => 'valid',
        ]);

        $petugasLain = User::create([
            'users_name' => 'Petugas Lain',
            'email' => 'petugas.lain@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567895'
        ]);

        // Report 2: valid but assigned to someone else
        $report2 = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Assigned',
            'description' => 'Kebakaran sudah ditugaskan',
            'latitude' => '-6.300000',
            'longitude' => '106.820000',
            'address' => 'Jalan Thamrin',
            'status' => 'diproses',
            'assigned_petugas_id' => $petugasLain->users_id,
        ]);

        // Request: View Dashboard
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Laporan Belum Ditugaskan');

        // Request: Filter by unassigned
        $responseFilter = $this->get(route('admin.dashboard', ['status' => 'unassigned']));
        $responseFilter->assertStatus(200);
        $responseFilter->assertSee('Kebakaran belum ditugaskan');
        $responseFilter->assertDontSee('Kebakaran sudah ditugaskan');
    }
}
