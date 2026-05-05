<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LaporanNullTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testSearchLaporanTidakDitemukan(): void
    {
        $user = User::factory()->create([
            'email' => 'pelapor@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports') 
                    ->type('search', 'KataKunciLaporanFiktif123') 
                    ->keys('input[name="search"]', '{enter}')
                    ->pause(1000)
                    ->assertSee('Belum ada laporan.'); 
        });
    }
}