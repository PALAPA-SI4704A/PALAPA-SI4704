<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MencegahAksesRiwayatLaporanLainTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.004 - Mencoba melihat detail/timeline laporan milik warga lain
     */
    public function test_TC_History_004_prevent_accessing_other_user_report_history(): void
    {
        $userA = User::factory()->create([
            'email' => 'wargaA@palapa.com',
            'role' => 'masyarakat',
        ]);

        $userB = User::factory()->create([
            'email' => 'wargaB@palapa.com',
            'role' => 'masyarakat',
        ]);

        $reportB = Report::factory()->create([
            'user_id' => $userB->users_id,
            'title' => 'Laporan Rahasia B',
            'status' => 'pending',
        ]);

        $this->browse(function (Browser $browser) use ($userA, $reportB) {
            $browser->loginAs($userA)
                    ->visit('/reports/' . $reportB->report_id . '/history');
            $browser->waitForText('AKSES DITOLAK. INI BUKAN LAPORAN ANDA.')
                    ->assertSee('403')
                    ->assertSee('AKSES DITOLAK. INI BUKAN LAPORAN ANDA.');
        });
    }
}
