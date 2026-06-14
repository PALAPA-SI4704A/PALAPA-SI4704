<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HalamanNotifikasiKosongTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.006 - Menampilkan halaman riwayat ketika database notifikasi kosong
     */
    public function test_TC_History_006_empty_notification_state(): void
    {
        $user = User::factory()->create([
            'email' => 'warganew@palapa.com',
            'role' => 'masyarakat',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/notifikasi')
                    ->waitForText('Notifikasi Saya')
                    ->assertSee('Anda belum memiliki notifikasi.');
        });
    }
}
