<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginPetugasTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testLoginPetugasBerhasil(): void
    {
        $user = User::factory()->create([
            'email' => 'petugas@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->logout() 
                    ->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password123')
                    ->click('button[type="submit"]')
                    ->waitForRoute('petugas.dashboard') 
                    ->assertRouteIs('petugas.dashboard');
        });
    }
}