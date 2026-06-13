<?php

namespace Tests\Browser;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TidakBisaEditLaporanValidTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testTidakBisaEditLaporanValid(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
        ]);

        $report = Report::factory()->create([
            'user_id' => $user->users_id,
            'status' => 'valid',
            'title' => 'Kebakaran yang sudah diproses',
            'description' => 'Laporan ini sudah diverifikasi.',
            'latitude' => -2.548900,
            'longitude' => 118.014900,
        ]);

        $this->browse(function (Browser $browser) use ($user, $report): void {
            $browser->loginAs($user)
                ->visit('/profile')
                ->assertSee('Laporan Saya')
                ->assertDontSee('Edit')
                ->visit('/reports/' . $report->report_id . '/edit')
                ->assertPathIs('/profile');
        });
    }
}
