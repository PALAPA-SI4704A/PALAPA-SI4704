<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardPetugasTest extends DuskTestCase
{
    public function test_dashboard_petugas(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'petugas@example.com')
                ->type('password', 'password')
                ->press('MASUK');

            $browser->visit('/petugas/dashboard')
                ->assertSee('Laporan Masuk Hari Ini')
                ->assertSee('Diproses')
                ->assertSee('Selesai Di tangani')
                ->assertSee('Total Laporan');

            $browser->assertSee('Laporan Masuk')
                ->assertSee('Diproses')
                ->assertSee('Selesai Di tangani')
                ->assertSee('Total Laporan');
        });
    }
}