<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCTrend006Test extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_trend_006_halaman_tren_database_kosong(): void
    {
        // Arrange
        $admin = User::factory()->create([
            'role' => 'admin',
            'users_name' => 'Admin Utama',
        ]);

        // Pastikan tidak ada data laporan (database kosong)
        Report::truncate();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/tren-distribusi')
                    ->waitForText('Tren & Distribusi Laporan')
                    // Memverifikasi grafik ter-render dengan aman (tidak crash)
                    ->assertPresent('#trenChart')
                    ->assertPresent('#statusChart')
                    ->assertPresent('#wilayahChart')
                    ->screenshot('F07_TC_Trend_006_EmptyDatabase');
        });
    }
}
