<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Report;

class ReportVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_can_verify_a_valid_report(): void
    {
        
        $petugas = User::create([
            'users_name' => 'Petugas',
            'email' => 'petugas@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567890'
        ]);

        
        $pelapor = User::create([
            'users_name' => 'Warga Pelapor',
            'email' => 'warga@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
            'phone' => '089876543210'
        ]);

        
        $report = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Pohon Tumbang',
            'description' => 'Ada pohon tumbang menghalangi jalan',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'address' => 'Jalan Sudirman',
            'status' => 'pending',
        ]);

        
        $response = $this->actingAs($petugas)
                         ->get(route('petugas.dashboard'));
        $response->assertStatus(200);
        
        $response->assertSee('#' . $report->report_id);

        
        
        $response = $this->get(route('petugas.reports.show', $report->report_id));
        $response->assertStatus(200);
        $response->assertSee($report->title);
        $response->assertSee('Verifikasi Laporan'); 

        
        
        $response = $this->post(route('petugas.reports.verify', $report->report_id), [
            'status' => 'valid'
        ]);

        
        $response->assertRedirect();
        $response->assertSessionHas('success');

        
        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'status' => 'valid'
        ]);
    }

    public function test_petugas_can_reject_an_invalid_report(): void
    {
        
        $petugas = User::create([
            'users_name' => 'Petugas Verifikator',
            'email' => 'petugas2@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'phone' => '081234567891'
        ]);

        $pelapor = User::create([
            'users_name' => 'Warga Pelapor',
            'email' => 'warga2@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
            'phone' => '089876543211'
        ]);

        $report = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Laporan Fiktif',
            'description' => 'Ini hanya laporan palsu',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'address' => 'Jalan Fiktif',
            'status' => 'pending',
        ]);

        
        $this->actingAs($petugas);

        
        $this->get(route('petugas.reports.show', $report->report_id));

        
        $response = $this->post(route('petugas.reports.verify', $report->report_id), [
                             'status' => 'ditolak',
                             'rejection_reason' => 'Laporan terindikasi fiktif'
                         ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'status' => 'ditolak',
            'rejection_reason' => 'Laporan terindikasi fiktif'
        ]);
    }

    public function test_admin_can_verify_a_valid_report_and_creates_status_history(): void
    {
        $admin = User::create([
            'users_name' => 'Admin Utama',
            'email' => 'admin@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567899'
        ]);

        $pelapor = User::create([
            'users_name' => 'Warga Pelapor',
            'email' => 'warga@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
            'phone' => '089876543210'
        ]);

        $report = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Semak',
            'description' => 'Ada api kecil di semak-semak',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'address' => 'Jalan Kebayoran',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
                         ->post(route('admin.reports.verify', $report->report_id), [
                             'status' => 'valid'
                         ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'status' => 'valid'
        ]);

        $this->assertDatabaseHas('status_histories', [
            'report_id' => $report->report_id,
            'status_awal' => 'pending',
            'status_baru' => 'valid',
            'catatan' => 'Laporan telah diverifikasi dan dinyatakan valid.',
            'diubah_oleh' => 'Admin Utama (Admin Sistem)'
        ]);
    }

    public function test_admin_can_assign_petugas_and_creates_status_history(): void
    {
        $admin = User::create([
            'users_name' => 'Admin Utama',
            'email' => 'admin@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567899'
        ]);

        $petugas = User::create([
            'users_name' => 'Petugas Lapangan',
            'email' => 'petugas@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567890'
        ]);

        $pelapor = User::create([
            'users_name' => 'Warga Pelapor',
            'email' => 'warga@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
            'phone' => '089876543210'
        ]);

        $report = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Semak',
            'description' => 'Ada api kecil di semak-semak',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'address' => 'Jalan Kebayoran',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
                         ->post(route('admin.reports.assign', [$report->report_id, $petugas->users_id]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'status' => 'diproses'
        ]);

        $this->assertDatabaseHas('status_histories', [
            'report_id' => $report->report_id,
            'status_awal' => 'pending',
            'status_baru' => 'diproses',
            'catatan' => 'Laporan sedang diverifikasi oleh admin dan diteruskan ke petugas lapangan.',
            'diubah_oleh' => 'Admin Utama (Admin Sistem)'
        ]);
    }

    public function test_petugas_can_view_handling_history_on_report_details(): void
    {
        
        $petugas = User::create([
            'users_name' => 'Petugas Lapangan 1',
            'email' => 'petugas.lapangan1@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567890'
        ]);

        
        $pelapor = User::create([
            'users_name' => 'Warga Pelapor 1',
            'email' => 'warga.pelapor1@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
            'phone' => '089876543210'
        ]);

        
        $report = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Lahan',
            'description' => 'Ada api melahap lahan kosong',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'address' => 'Jalan Sudirman',
            'status' => 'pending',
        ]);

        
        $report->statusHistories()->create([
            'status_awal' => 'pending',
            'status_baru' => 'valid',
            'catatan' => 'Laporan diverifikasi oleh petugas pertama.',
            'diubah_oleh' => 'Petugas Lapangan 1 (Petugas Pemadam)',
            'tanggal_ubah' => now(),
        ]);

        $response = $this->actingAs($petugas)
                         ->get(route('petugas.reports.show', $report->report_id));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Penanganan');
        $response->assertSee('Laporan diverifikasi oleh petugas pertama.');
        $response->assertSee('Petugas Lapangan 1 (Petugas Pemadam)');
    }
}
