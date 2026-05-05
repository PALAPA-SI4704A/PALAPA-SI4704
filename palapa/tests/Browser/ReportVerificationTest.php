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
            'users_name' => 'Admin Sistem', 
            'email' => 'admin@palapa.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'phone' => '081234567890'
        ]);

        $pelapor = User::create([
            'users_name' => 'Aska', 
            'email' => 'aska@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
            'phone' => '089876543210'
        ])->fresh();

        $report = Report::create([
            'user_id' => $pelapor->users_id, 
            'title' => 'Asap Tebal', 
            'description' => 'Terlihat ada asap tebal di sekitar lokasi.',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'address' => 'Lokasi Asap Tebal',
            'status' => 'pending',
        ]);

        $this->actingAs($petugas);
        
        $this->get(route('petugas.dashboard'))
             ->assertStatus(200)
             ->assertSee('#' . $report->report_id) 
             ->assertSee($report->latitude); 
        
        $this->get(route('petugas.reports.show', $report->report_id))
             ->assertStatus(200)
             ->assertSee('Terlihat ada asap tebal'); 

        $response = $this->withSession(['_token' => 'test_token'])
             ->post(route('petugas.reports.verify', $report->report_id), [
            '_token' => 'test_token',
            'status' => 'valid'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Laporan berhasil diverifikasi menjadi: Valid'); 

    }
}