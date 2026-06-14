<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MelihatRiwayatLaporanTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.001 - Melihat riwayat penanganan laporan (Timeline/Status)
     */
    public function test_TC_History_001_view_timeline(): void
    {
        $user = User::factory()->create([
            'email' => 'warga1@palapa.com',
            'role' => 'masyarakat',
        ]);

        $report = Report::factory()->create([
            'user_id' => $user->users_id,
            'title' => 'Kebakaran Lahan Gambut',
            'status' => 'pending',
        ]);

        $this->browse(function (Browser $browser) use ($user, $report) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->waitForText('Laporan Saya')
                    ->assertSee($report->title)
                    ->script("document.querySelector('a[href*=\"/reports/" . $report->report_id . "/history\"]').click();");

            $browser->waitFor('div[x-show="modalOpen"] .modal-content')
                    ->waitForText('Laporan berhasil dibuat oleh pelapor.')
                    ->assertSee('Riwayat Status')
                    ->assertSee('pending');
        });
    }
}
