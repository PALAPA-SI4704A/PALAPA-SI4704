<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PetugasLihatDashboardTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.Complete.003 - Petugas melihat dashboard ringkasan statistik
     */
    public function test_TC_Complete_003_petugas_melihat_dashboard_ringkasan_statistik(): void
    {
        $petugas = User::factory()->create([
            'users_name' => 'Petugas Tiga',
            'email'      => 'petugas3@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'petugas',
        ]);

        // Report 1: diproses
        Report::factory()->create([
            'status' => 'diproses',
            'title' => 'Laporan Diproses Petugas Tiga',
            'description' => 'Deskripsi pendek.', 
            'assigned_petugas_id' => $petugas->users_id,
            'created_at' => now(),
        ]);

        // Report 2: selesai
        Report::factory()->create([
            'status' => 'selesai',
            'title' => 'Laporan Selesai Petugas Tiga',
            'description' => 'Deskripsi pendek.', 
            'assigned_petugas_id' => $petugas->users_id,
            'created_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($petugas) {
            $browser->logout()
                    ->loginAs($petugas)
                    ->visit(route('petugas.dashboard'))
                    ->waitForText('Laporan Masuk')
                    ->assertSee('Laporan Masuk Hari Ini')
                    ->assertSeeIn('.stats-grid', '2')
                    ->assertSeeIn('.stats-grid', '1')
                    ->assertSeeIn('.stats-grid', '1') 
                    ->clickLink('Beranda')
                    ->waitForText('Laporan Masuk')
                    ->screenshot('tc-complete-003-success');
        });
    }
}
