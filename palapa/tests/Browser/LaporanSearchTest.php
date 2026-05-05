<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LaporanSearchTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testSearchLaporanDitemukan(): void
    {
        $user = User::factory()->create([
            'email' => 'pelapor@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'masyarakat',
        ]);

        Report::create([
            'user_id' => $user->users_id ?? $user->id,
            'title' => 'Kebakaran Hutan Jati',
            'description' => 'Api membesar di area tengah hutan jati.',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
            'status' => 'pending',
        ]);

        Report::create([
            'user_id' => $user->users_id ?? $user->id,
            'title' => 'Asap Tebal di Lahan Gambut',
            'description' => 'Terlihat kepulan asap dari kejauhan.',
            'latitude' => '-0.789275',
            'longitude' => '113.921327',
            'status' => 'pending',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/reports') 
                    ->assertSee('Kebakaran Hutan Jati')
                    ->assertSee('Asap Tebal di Lahan Gambut')
                    ->type('search', 'Hutan Jati') 
                    ->press('Cari')
                    ->pause(1500)
                    ->assertSee('Kebakaran Hutan Jati')
                    ->assertDontSee('Asap Tebal di Lahan Gambut'); 
        });
    }
}