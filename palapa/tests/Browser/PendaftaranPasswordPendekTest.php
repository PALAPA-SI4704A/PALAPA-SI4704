<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PendaftaranPasswordPendekTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_pendaftaran_ditolak_jika_password_kurang_dari_8_karakter(): void
    {
        $this->browse(function (Browser $browser) {
            
            $browser->maximize()
                    ->visit('/register')
                    ->pause(1000)
                    ->assertPathIs('/register');

            $browser->script([
                "document.querySelector('input[name=\"users_name\"]').value = 'Warga Baru';",
                "document.querySelector('input[name=\"email\"]').value = 'wargabaru@palapa.com';",
                "document.querySelector('input[name=\"phone\"]').value = '081234567890';",
                "document.querySelector('input[name=\"password\"]').value = 'abc12';",
                "document.querySelector('input[name=\"password_confirmation\"]').value = 'abc12';"
            ]);

            $browser->pause(500);

            $browser->script("document.querySelector('form').submit();");

            $browser->pause(1000)
                    ->assertPathIs('/register');
                    
            $browser->assertSee('Registrasi gagal:');
            
            $browser->assertSee('8 characters'); 
        });
    }
}