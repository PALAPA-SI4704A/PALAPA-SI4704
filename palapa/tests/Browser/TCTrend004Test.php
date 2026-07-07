<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCTrend004Test extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_trend_004_pembatasan_hak_akses_halaman_tren_oleh_non_admin(): void
    {
        // Arrange
        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
            'users_name' => 'Warga Biasa',
        ]);

        $this->browse(function (Browser $browser) use ($pelapor) {
            $browser->loginAs($pelapor)
                    ->visit('/reports') // Halaman biasa masyarakat untuk set token/session
                    ->visit('/admin/tren-distribusi')
                    ->pause(1500)
                    ->assertSee('ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.')
                    ->screenshot('F07_TC_Trend_004_AccessBlocked');
        });
    }
}
