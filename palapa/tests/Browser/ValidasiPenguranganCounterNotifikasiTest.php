<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ValidasiPenguranganCounterNotifikasiTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.008 - Validasi pengurangan otomatis counter notifikasi belum dibaca
     */
    public function test_TC_History_008_unread_notification_counter(): void
    {
        $user = User::factory()->create([
            'email' => 'warga8@palapa.com',
            'role' => 'masyarakat',
        ]);

        // Create 2 notifications
        $notif1 = Notifikasi::create([
            'user_id' => $user->users_id,
            'pesan' => 'Notifikasi 1',
            'is_read' => 0,
        ]);
        $notif2 = Notifikasi::create([
            'user_id' => $user->users_id,
            'pesan' => 'Notifikasi 2',
            'is_read' => 0,
        ]);

        $this->browse(function (Browser $browser) use ($user, $notif1, $notif2) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->waitFor('.profile-bell')
                    ->assertPresent('.notif-badge') // Badge "+" exists since we have unread notifs
                    ->click('.profile-bell')
                    ->waitForLocation('/notifikasi')
                    ->press('Tandai Dibaca') // Reads the first notification
                    ->waitForText('Notifikasi ditandai sudah dibaca.')
                    ->visit('/profile')
                    ->waitFor('.profile-bell')
                    ->assertPresent('.notif-badge'); // Badge "+" still exists because of $notif2
            
            // Now mark the second notification as read
            $browser->visit('/notifikasi')
                    ->press('Tandai Dibaca') // Reads the second notification
                    ->waitForText('Notifikasi ditandai sudah dibaca.')
                    ->visit('/profile')
                    ->waitFor('.profile-bell')
                    ->assertMissing('.notif-badge'); // Badge "+" is now gone!
        });
    }
}
