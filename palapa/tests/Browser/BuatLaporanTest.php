<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BuatLaporanTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testUserDapatMembuatLaporan(): void
    {
        $user = User::factory()->create([
            'email' => 'pelapor@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports/create')
                    ->type('title', 'Kebakaran Hutan Jati')
                    ->type('latitude', '-6.200000')
                    ->type('longitude', '106.816666')
                    ->type('description', 'Asap tebal dan api mulai membesar di area tengah hutan jati.')
                    ->press('Lanjutkan Preview')
                    ->waitForText('Preview Laporan')
                    ->press('Confirm dan Simpan')
                    ->waitForRoute('profile')
                    ->assertRouteIs('profile');
        });
    }
}