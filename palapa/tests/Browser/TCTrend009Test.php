<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCTrend009Test extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_trend_009_validasi_grafik_responsive_mobile_dan_desktop(): void
    {
        // 1. Arrange: Persiapan data Admin dan Pelapor
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
            // 2. Act (Desktop Mode)
            $browser->loginAs($admin)
                    ->resize(1280, 800) // Ukuran layar desktop standar
                    ->visit('/admin/tren-distribusi')
                    ->waitForText('Tren & Distribusi Laporan')
                    ->assertPresent('#trenChart')
                    ->screenshot('F07_TC_Trend_009_Desktop_Layout');

            // 3. Act (Mobile Mode)
            $browser->resize(375, 812) // Ukuran layar mobile standar (lebar 375px)
                    ->pause(1000) // Berikan waktu bagi Chart.js untuk re-resize otomatis
                    ->assertPresent('#trenChart')
                    ->assertPresent('#statusChart')
                    ->assertPresent('#wilayahChart')
                    ->screenshot('F07_TC_Trend_009_Mobile_Layout');
        });
    }
}
