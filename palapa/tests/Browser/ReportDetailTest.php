<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Report;

class ReportDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Menguji fungsionalitas halaman detail riwayat (TC.Detail.001)
     * Test Case: User melihat detail laporan yang telah dibuat
     */
    public function test_user_can_view_report_history_detail(): void
    {
        
        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
        ])->fresh();

        $report = Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'title' => 'Laporan Kerusakan Jalan Test',
            'status' => 'pending'
        ]);

        $this->actingAs($pelapor);
        
        
        $this->get(route('reports.index'))
             ->assertStatus(200)
             ->assertSee('Laporan Saya')
             ->assertSee($report->title);
             
        
        $this->get(route('reports.history', $report->report_id))
             ->assertStatus(200)
             ->assertSee('Riwayat Status')
             ->assertSee($report->title)
             ->assertSee('Kembali');
    }
}
