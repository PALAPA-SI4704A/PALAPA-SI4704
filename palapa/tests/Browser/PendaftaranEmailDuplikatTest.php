<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PendaftaranEmailDuplikatTest extends DuskTestCase
{
    use DatabaseMigrations;


    public function test_pendaftaran_gagal_karena_email_sudah_digunakan(): void
    {
        User::create([
            'users_name' => 'Warga Lama',
            'email' => 'duplikat@palapa.com',
            'phone' => '081111111111',
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
                "document.querySelector('input[name=\"email\"]').value = 'duplikat@palapa.com';",
                "document.querySelector('input[name=\"phone\"]').value = '082222222222';",
                "document.querySelector('input[name=\"password\"]').value = 'password123';",
                "document.querySelector('input[name=\"password_confirmation\"]').value = 'password123';"
            ]);

            $browser->pause(500);

            $browser->script("document.querySelector('form').submit();");

            $browser->waitForText('Email sudah terdaftar.', 7)
                    ->assertPathIs('/register')
                    ->assertSee('Email sudah terdaftar.');
        });
    }
}