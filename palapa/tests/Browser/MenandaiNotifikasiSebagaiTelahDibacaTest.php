<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MenandaiNotifikasiSebagaiTelahDibacaTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.003 - Menandai notifikasi sebagai telah dibaca
     */
    public function test_TC_History_003_mark_notification_as_read(): void
    {
        $user = User::factory()->create([
            'email' => 'warga3@palapa.com',
            'role' => 'masyarakat',
        ]);

        $notif = Notifikasi::create([
            'user_id' => $user->users_id,
            'pesan' => 'Laporan Anda sedang diproses oleh petugas',
            'is_read' => 0,
        ]);

        $this->browse(function (Browser $browser) use ($user, $notif) {
            $browser->loginAs($user)
                    ->visit('/notifikasi')
                    ->waitForText('Notifikasi Saya')
                    ->assertSee($notif->pesan)
                    ->press('Tandai Dibaca')
                    ->waitForText('Notifikasi ditandai sudah dibaca.')
                    ->assertSee('Sudah dibaca')
                    ->assertDontSee('Tandai Dibaca');
        });
    }
}
