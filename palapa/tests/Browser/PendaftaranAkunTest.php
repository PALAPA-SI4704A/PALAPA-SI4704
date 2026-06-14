<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PendaftaranAkunTest extends DuskTestCase
{
    use DatabaseMigrations;


    public function test_pendaftaran_akun_masyarakat_baru_dengan_data_lengkap_dan_valid(): void
    {
        $this->browse(function (Browser $browser) {
            
            $browser->resize(1920, 1080)
            
                    ->visit('/')
                    ->pause(1000)
                    ->assertSee('Palapa')
                    ->script("document.querySelector('a[href*=\"/register\"]').click();");
            
            $browser->waitForLocation('/register', 7)
                    ->assertPathIs('/register')
                    ->pause(1000)
                    
                    ->script([
                        "document.querySelector('input[name=\"users_name\"]').value = 'Pindwa Warga Baru';",
                        "document.querySelector('input[name=\"email\"]').value = 'pindwa.baru@example.com';",
                        "document.querySelector('input[name=\"phone\"]').value = '081234567890';",
                        "document.querySelector('input[name=\"password\"]').value = 'password123';",
                        "document.querySelector('input[name=\"password_confirmation\"]').value = 'password123';"
                    ]);
            
            $browser->pause(500)
                    
                    ->script("document.querySelector('form').submit();");
                    
            $browser->waitForLocation('/beranda', 10)
                    ->assertPathIs('/beranda'); 
        });
    }
}