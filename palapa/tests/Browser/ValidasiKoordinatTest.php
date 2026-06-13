<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ValidasiKoordinatTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testValidasiKoordinat(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
        ]);

        $this->browse(function (Browser $browser) use ($user): void {
            $browser->loginAs($user)
                ->visit('/reports/create')
                ->assertSee('Buat Laporan')
                ->type('title', 'Kebakaran di area uji validasi')
                ->type('description', 'Koordinat ini berada di luar wilayah Kalimantan.')
                ->type('latitude', '-6.200000')
                ->type('longitude', '106.816666')
                ->click('label.fire-level-card.level-critical')
                ->press('Lanjutkan Preview')
                ->assertPathIs('/reports/create')
                ->assertSee('Koordinat lokasi harus berada di wilayah cakupan Kalimantan');
        });
    }
}
