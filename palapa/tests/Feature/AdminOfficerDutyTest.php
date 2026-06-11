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

    public function test_officer_becomes_available_after_completing_report(): void
    {
        // 1. Setup Petugas
        $petugas = User::create([
            'users_name' => 'Petugas Lapangan',
            'email' => 'petugas.lapangan@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567891'
        ]);

        $pelapor = User::create([
            'users_name' => 'Warga Test',
            'email' => 'warga.test@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'masyarakat',
            'phone' => '081234567892'
        ]);

        // 2. Report assigned to Petugas Lapangan
        $report = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Ruko',
            'description' => 'Ada asap tebal di ruko lantai 2',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'address' => 'Jalan Sudirman No 10',
            'status' => 'diproses',
            'assigned_petugas_id' => $petugas->users_id,
        ]);

        // 3. Create active assignment
        $penugasan = Penugasan::create([
            'report_id' => $report->report_id,
            'petugas_id' => $petugas->users_id,
            'assigned_at' => now(),
        ]);

        // Assert: Officer is busy (on duty) initially
        $this->assertTrue(Penugasan::where('petugas_id', $petugas->users_id)->whereNull('completed_at')->exists());

        // Fake file upload for bukti_foto
        \Illuminate\Support\Facades\Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->create('bukti_selesai.jpg', 100, 'image/jpeg');

        // 4. Officer completes the report via PetugasController@updateStatus
        $response = $this->actingAs($petugas)
            ->post(route('petugas.reports.updateStatus', ['report' => $report->report_id]), [
                'status' => 'selesai',
                'catatan' => 'Kebakaran berhasil dipadamkan dengan aman.',
                'bukti_foto' => $file,
            ]);

        $response->assertRedirect();
        
        // 5. Assert database has completed_at timestamp
        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'status' => 'selesai',
            'handling_note' => 'Kebakaran berhasil dipadamkan dengan aman.'
        ]);

        // Assert: Officer is no longer busy (available)
        $this->assertFalse(Penugasan::where('petugas_id', $petugas->users_id)->whereNull('completed_at')->exists());
        $this->assertNotNull(Penugasan::where('report_id', $report->report_id)->first()->completed_at);
     }

     public function test_multiple_officers_can_be_assigned_to_same_report_and_become_available_after_completion(): void
     {
         $admin = User::create([
             'users_name' => 'Admin Test',
             'email' => 'admin.test2@palapa.com',
             'password' => bcrypt('password'),
             'role' => 'admin',
             'phone' => '081234567899'
         ]);

         $petugas1 = User::create([
             'users_name' => 'Petugas 1',
             'email' => 'petugas1@palapa.com',
             'password' => bcrypt('password'),
             'role' => 'petugas',
             'phone' => '081234567891'
         ]);

         $petugas2 = User::create([
             'users_name' => 'Petugas 2',
             'email' => 'petugas2@palapa.com',
             'password' => bcrypt('password'),
             'role' => 'petugas',
             'phone' => '081234567892'
         ]);

         $pelapor = User::create([
             'users_name' => 'Warga Test',
             'email' => 'warga.test@palapa.com',
             'password' => bcrypt('password'),
             'role' => 'masyarakat',
             'phone' => '081234567893'
         ]);

         $report = Report::create([
             'user_id' => $pelapor->users_id,
             'title' => 'Kebakaran Ruko',
             'description' => 'Ada asap tebal di ruko lantai 2',
             'latitude' => '-6.200000',
             'longitude' => '106.816666',
             'address' => 'Jalan Sudirman No 10',
             'status' => 'valid',
         ]);

         // 1. Assign Petugas 1 to Report
         $this->actingAs($admin)
             ->post(route('admin.reports.assign', ['report' => $report->report_id, 'petugas' => $petugas1->users_id]));

         // Assert Petugas 1 is on duty
         $this->assertTrue(Penugasan::where('petugas_id', $petugas1->users_id)->whereNull('completed_at')->exists());

         // 2. Assign Petugas 2 (additional officer) to the same Report
         $this->actingAs($admin)
             ->post(route('admin.reports.assign', ['report' => $report->report_id, 'petugas' => $petugas2->users_id]));

         // Assert both Petugas 1 and Petugas 2 are on duty for the same report
         $this->assertTrue(Penugasan::where('petugas_id', $petugas1->users_id)->whereNull('completed_at')->exists());
         $this->assertTrue(Penugasan::where('petugas_id', $petugas2->users_id)->whereNull('completed_at')->exists());
         $this->assertEquals(2, Penugasan::where('report_id', $report->report_id)->whereNull('completed_at')->count());

         // 3. Try to assign Petugas 1 to another report - should fail
         $anotherReport = Report::create([
             'user_id' => $pelapor->users_id,
             'title' => 'Kebakaran Lain',
             'description' => 'Kebakaran lain',
             'latitude' => '-6.300000',
             'longitude' => '106.820000',
             'address' => 'Jalan Thamrin',
             'status' => 'valid',
         ]);
         $response = $this->actingAs($admin)
             ->post(route('admin.reports.assign', ['report' => $anotherReport->report_id, 'petugas' => $petugas1->users_id]));
         $response->assertSessionHasErrors(['error']);

         // 4. One of the officers completes the report
         \Illuminate\Support\Facades\Storage::fake('public');
         $file = \Illuminate\Http\UploadedFile::fake()->create('bukti.jpg', 100, 'image/jpeg');

         $this->actingAs($petugas1)
             ->post(route('petugas.reports.updateStatus', ['report' => $report->report_id]), [
                 'status' => 'selesai',
                 'catatan' => 'Kebakaran berhasil dipadamkan dengan aman.',
                 'bukti_foto' => $file,
             ]);

         // Assert both Petugas 1 and Petugas 2 are now available
         $this->assertFalse(Penugasan::where('petugas_id', $petugas1->users_id)->whereNull('completed_at')->exists());
         $this->assertFalse(Penugasan::where('petugas_id', $petugas2->users_id)->whereNull('completed_at')->exists());
     }
}
