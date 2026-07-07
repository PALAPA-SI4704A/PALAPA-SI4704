<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCTrend003Test extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_trend_003_grafik_distribusi_status_dan_wilayah(): void
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

        // Buat beberapa laporan untuk populasi grafik
        Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'pending',
            'address' => 'Pontianak, Kalimantan Barat',
        ]);
        Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'valid',
            'address' => 'Balikpapan, Kalimantan Timur',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/tren-distribusi')
                    ->waitForText('Tren & Distribusi Laporan')
                    // Verifikasi elemen grafik status & wilayah
                    ->assertPresent('#statusChart')
                    ->assertPresent('#wilayahChart')
                    ->assertSee('Distribusi Per Status')
                    ->assertSee('Distribusi Per Wilayah')
                    ->screenshot('F07_TC_Trend_003_Distribusi');
        });
    }
}
