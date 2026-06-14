<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MenerimaNotifikasiRealtimeTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.002 - Menerima notifikasi perubahan status laporan secara real-time
     */
    public function test_TC_History_002_receive_realtime_notification(): void
    {
        $user = User::factory()->create([
            'email' => 'warga2@palapa.com',
            'role' => 'masyarakat',
        ]);

        $notif = Notifikasi::create([
            'user_id' => $user->users_id,
            'pesan' => 'Laporan kebakaran Anda telah diverifikasi Valid',
            'is_read' => 0,
        ]);

        $this->browse(function (Browser $browser) use ($user, $notif) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->waitFor('.profile-bell')
                    ->assertPresent('.notif-badge')
                    ->click('.profile-bell')
                    ->waitForLocation('/notifikasi')
                    ->assertRouteIs('notifikasi.index')
                    ->assertSee($notif->pesan);
        });
    }
}
