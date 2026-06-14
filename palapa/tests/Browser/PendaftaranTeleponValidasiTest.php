<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PendaftaranTeleponValidasiTest extends DuskTestCase
{
    use DatabaseMigrations;


    public function test_pendaftaran_ditolak_jika_nomor_telepon_sudah_terdaftar(): void
    {
        User::create([
            'users_name' => 'Warga Lama',
            'email' => 'lama@palapa.com',
            'phone' => '081234567890', 
            'role' => 'masyarakat',
            'password' => Hash::make('password123'),
        ]);

        $this->browse(function (Browser $browser) {
            
            $browser->maximize()
                    ->visit('/register')
                    ->pause(1000)
                    ->assertPathIs('/register');

            $browser->script([
                "document.querySelector('input[name=\"users_name\"]').value = 'Warga Baru';",
                "document.querySelector('input[name=\"email\"]').value = 'baru@palapa.com';",
                "document.querySelector('input[name=\"phone\"]').value = '081234567890';",
                "document.querySelector('input[name=\"password\"]').value = 'password123';",
                "document.querySelector('input[name=\"password_confirmation\"]').value = 'password123';"
            ]);

            $browser->pause(500);

            $browser->script("document.querySelector('form').submit();");

            $browser->pause(1000)
                    ->assertPathIs('/register')
                    ->assertSee('Nomor telepon sudah terdaftar.');
        });
    }
}