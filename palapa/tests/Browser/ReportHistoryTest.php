<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Report;

class ReportHistoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Menguji fungsionalitas halaman riwayat (TC.History.001)
     * Test Case: User melihat daftar riwayat laporan yang telah dibuat
     */
    public function test_user_can_view_report_history_list(): void
    {
        
        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
        ])->fresh();

        $report = Report::factory()->create([
            'user_id' => $pelapor->users_id, 
            'title' => 'Jalan Rusak',
            'status' => 'pending'
        ]);

        $this->actingAs($pelapor);
        
        $this->get(route('profile'))
             ->assertStatus(200)
             ->assertSee('Profil saya')
             ->assertSee('Riwayat Laporan')
             ->assertSee('#' . $report->id)
             ->assertSee('Diproses'); 
    }
}
