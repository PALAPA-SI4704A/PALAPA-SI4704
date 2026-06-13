<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ValidasiTingkatKeparahanTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testValidasiTingkatKeparahan(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
            'email' => 'pengguna-validasi@example.com',
        ]);

        $photoPath = base_path('public/images/logo-palapa.png');

        $this->browse(function (Browser $browser) use ($user, $photoPath): void {
            $browser->loginAs($user)
                ->visit('/reports/create')
                ->assertSee('Buat Laporan')
                ->type('title', 'Kebakaran lahan di dekat pemukiman')
                ->type('description', 'Asap tebal dan api mulai menjalar di area semak belukar.')
                ->type('latitude', '-2.548900')
                ->type('longitude', '118.014900')
                ->attach('photo', $photoPath)
                ->press('Lanjutkan Preview')
                ->assertPathIs('/reports/create');
        });
    }
}
