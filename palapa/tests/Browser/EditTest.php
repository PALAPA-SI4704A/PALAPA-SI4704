<?php

namespace Tests\Browser;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Report;

class EditTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testEditReport(): void
    {
        $user = User::factory()->create();
        $report = Report::factory()->create([
            'user_id' => $user->users_id,
            'status' => 'pending',
        ]);

        $this->browse(function ($browser) use ($user, $report): void {
            $browser->loginAs($user)
                ->visit('/profile')
                ->assertSee('Riwayat Laporan')
                ->assertSee('Edit')
                ->clickLink('Edit')
                ->assertPathIs('/reports/' . $report->report_id . '/edit')
                ->type('latitude', '-7.00017400')
                ->type('longitude', '107.64543600')
                ->press('Lanjutkan Preview')
                ->assertPathIs('/reports/' . $report->report_id . '/preview')
                ->press('Confirm dan Simpan Perubahan')
                ->assertPathIs('/profile')
                ->assertSee('-7.00017400, 107.64543600');
        });
    }
}
