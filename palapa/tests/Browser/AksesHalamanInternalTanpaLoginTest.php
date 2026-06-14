<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AksesHalamanInternalTanpaLoginTest extends DuskTestCase
{
    use DatabaseMigrations;


    public function test_guest_diblokir_dan_dialihkan_ke_halaman_login(): void
    {
        $this->browse(function (Browser $browser) {
            
            $browser->logout()
                    ->maximize()
                    ->pause(500);

            $browser->visit('/admin/dashboard');

            $browser->waitForLocation('/login', 7)
                    ->assertPathIs('/login');
                    

        });
    }
}