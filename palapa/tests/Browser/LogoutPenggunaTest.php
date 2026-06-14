<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LogoutPenggunaTest extends DuskTestCase
{
    use DatabaseMigrations;


    public function test_pengguna_berhasil_logout_dan_tidak_bisa_kembali_ke_dashboard(): void
    {
        $user = User::create([
            'users_name' => 'Pindwa Keluar',
            'email' => 'keluar@palapa.com',
            'phone' => '081299998888',
            'role' => 'masyarakat',
            'password' => Hash::make('password123'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            
            $browser->loginAs($user)
                    ->maximize()
                    ->visit('/beranda')
                    ->pause(1000)
                    ->assertPathIs('/beranda');

            $browser->assertSee('Keluar');

            $browser->script("
                let formLogout = document.querySelector('form[action*=\"logout\"]');
                if (formLogout) {
                    formLogout.submit();
                } else {
                    let btnKeluar = Array.from(document.querySelectorAll('button, a')).find(el => el.textContent.trim().includes('Keluar'));
                    if (btnKeluar) btnKeluar.click();
                }
            ");

            $browser->waitForLocation('/login', 7)
                    ->assertPathIs('/login');
            
            $browser->back();


            $browser->refresh()
                    ->pause(1000) 
                    ->assertPathIs('/login'); 
        });
    }
}