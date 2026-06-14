<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginPasswordSalahTest extends DuskTestCase
{
    use DatabaseMigrations;


    public function test_login_gagal_karena_password_salah(): void
    {
        User::create([
            'users_name' => 'Warga Palapa',
            'email' => 'warga.test@palapa.com',
            'phone' => '081234567899',
            'role' => 'masyarakat',
            'password' => Hash::make('passwordAsli123'), 
        ]);

        $this->browse(function (Browser $browser) {
            
            $browser->resize(1920, 1080)
                    ->visit('/login')
                    ->waitFor('input[name="login"]', 5)
                    ->assertPathIs('/login')
                    ->pause(1000);

            $browser->script([
                "document.querySelector('input[name=\"login\"]').value = 'warga.test@palapa.com';",
                "document.querySelector('input[name=\"password\"]').value = 'passwordSalah123';"
            ]);

            $browser->pause(500)
                    
                    ->script("document.querySelector('form').submit();");

               
            $browser->waitForText('Email/Nomor Telepon atau password yang dimasukkan salah.', 7)
                    ->assertPathIs('/login')
                    ->assertSee('Email/Nomor Telepon atau password yang dimasukkan salah.');
        });
    }
}