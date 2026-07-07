<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCTrend002Test extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_trend_002_filter_perubahan_periode_analisis_data_tren(): void
    {
        // Arrange
        $admin = User::factory()->create([
            'role' => 'admin',
            'users_name' => 'Admin Utama',
        ]);
        
        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
            'users_name' => 'Warga Biasa',
        ]);

        Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'pending',
            'address' => 'Pontianak, Kalimantan Barat',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    // Pilih 30 Hari Terakhir
                    ->visit('/admin/tren-distribusi?period=30days')
                    ->waitForText('Tren Laporan Per Periode & Status')
                    ->assertSelected('select[name="period"]', '30days')
                    ->screenshot('F07_TC_Trend_002_Filter_30Days')

                    // Pilih Tahun Ini
                    ->visit('/admin/tren-distribusi?period=year')
                    ->waitForText('Tren Laporan Per Periode & Status')
                    ->assertSelected('select[name="period"]', 'year')
                    ->screenshot('F07_TC_Trend_002_Filter_Year');
        });
    }
}
