<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testLoginMasyarakatBerhasil(): void
    {
        $user = User::factory()->create([
            'email' => 'warga@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->logout() 
                    ->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password123')
                    ->click('button[type="submit"]')
                    ->waitForRoute('beranda') 
                    ->assertRouteIs('beranda');
        });
    }
}