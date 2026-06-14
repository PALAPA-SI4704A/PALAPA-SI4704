<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MencegahTandaiNotifikasiLainTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.005 - Menandai notifikasi milik pengguna lain sebagai dibaca secara ilegal
     */
    public function test_TC_History_005_prevent_marking_other_user_notification_as_read(): void
    {
        $userA = User::factory()->create([
            'email' => 'wargaA5@palapa.com',
            'role' => 'masyarakat',
        ]);

        $userB = User::factory()->create([
            'email' => 'wargaB5@palapa.com',
            'role' => 'masyarakat',
        ]);

        $notifB = Notifikasi::create([
            'user_id' => $userB->users_id,
            'pesan' => 'Pesan privat warga B',
            'is_read' => 0,
        ]);

        $this->browse(function (Browser $browser) use ($userA, $notifB) {
            $browser->loginAs($userA)
                    ->visit('/profile')
                    ->waitForText('Laporan Saya')
                    ->script("
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/notifikasi/" . $notifB->notifikasi_id . "/read';
                        let csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('input[name=\"_token\"]').value;
                        form.appendChild(csrf);
                        document.body.appendChild(form);
                        form.submit();
                    ");
            
            $browser->pause(2000); // Wait for page reload/response
            $browser->assertSee('404'); // Controller query throws firstOrFail() causing a 404
        });
    }
}
