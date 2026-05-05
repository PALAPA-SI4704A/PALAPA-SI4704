<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FilterLaporanPetugasTest extends DuskTestCase
{
    public function test_filter_laporan_petugas(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'petugas@example.com')
                ->type('password', 'password')
                ->press('MASUK');

            $browser->visit('/petugas/dashboard')
                ->type('input[type=date]', '05/05/2026');

            $browser->select('select', 'Pending');

            $browser->type('input[placeholder="Cari Lokasi (Tekan Enter)"]', '-6.99089600, 107.64562400')
                ->keys('input[placeholder="Cari Lokasi (Tekan Enter)"]', '{enter}');

            $browser->assertSee('-6.99089600, 107.64562400');
        });
    }
}