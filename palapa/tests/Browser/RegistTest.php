<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testRegistrasiBerhasil(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                    ->visit('/register')
                    ->type('users_name', 'Pengguna Baru')
                    ->type('email', 'pengguna.baru@palapa.com')
                    ->type('phone', '081234567890')
                    ->type('password', 'password123')
                    ->type('password_confirmation', 'password123')
                    ->press('DAFTAR')
                    ->waitForRoute('beranda')
                    ->assertRouteIs('beranda');
        });
    }
}