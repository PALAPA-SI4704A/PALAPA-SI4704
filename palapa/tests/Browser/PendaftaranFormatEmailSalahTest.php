<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PendaftaranFormatEmailSalahTest extends DuskTestCase
{
    use DatabaseMigrations;


    public function test_form_diblokir_oleh_browser_jika_format_email_tidak_lengkap(): void
    {
        $this->browse(function (Browser $browser) {
            
            $browser->resize(1920, 1080)
                    ->visit('/register')
                    ->pause(1000)
                    ->assertPathIs('/register');

            $browser->script([
                "document.querySelector('input[name=\"users_name\"]').value = 'Warga Baru';",
                "document.querySelector('input[name=\"email\"]').value = 'wargabaru@';",
                "document.querySelector('input[name=\"phone\"]').value = '081234567890';",
                "document.querySelector('input[name=\"password\"]').value = 'password123';",
                "document.querySelector('input[name=\"password_confirmation\"]').value = 'password123';"
            ]);

            $browser->pause(500);

           
            $browser->script("document.querySelector('button[type=\"submit\"]').click();");

  
            $browser->pause(1000)
                    ->assertPathIs('/register');

            $isEmailInvalid = $browser->script("return !document.querySelector('input[name=\"email\"]').validity.valid;")[0];

            $this->assertTrue($isEmailInvalid, 'Browser gagal memblokir format email yang salah.');
        });
    }
}