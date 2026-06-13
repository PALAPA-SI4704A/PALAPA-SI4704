<?php

namespace Tests\Browser;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditLaporanPendingTest extends DuskTestCase
{
    public function testWargaEditLaporanPending(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
        ]);

        $report = Report::factory()->create([
            'user_id' => $user->users_id,
            'status' => 'pending',
            'title' => 'Kebakaran awal',
            'description' => 'Deskripsi awal laporan.',
            'latitude' => -2.548900,
            'longitude' => 118.014900,
            'fire_level' => 'high',
        ]);

        $this->browse(function (Browser $browser) use ($user, $report) {
            $browser->loginAs($user)
                ->visit('/profile')
                ->assertSee('Profil Saya')
                ->clickLink('Edit')
                ->assertPathIs('/reports/' . $report->report_id . '/edit')
                ->assertSee('Edit Laporan')
                ->type('title', 'Kebakaran di Hutan Lindung yang Diperbarui')
                ->type('description', 'Deskripsi telah diperbarui untuk penanganan lebih cepat.')
                ->type('latitude', '-2.600000')
                ->type('longitude', '118.100000')
                ->click('label.fire-level-card.level-critical')
                ->press('Lanjutkan Preview')
                ->assertPathIs('/reports/' . $report->report_id . '/preview')
                ->assertSee('Preview Laporan')
                ->press('Confirm dan Simpan Perubahan')
                ->assertPathIs('/profile')
                ->assertSee('Laporan berhasil diperbarui');
        });

        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'user_id' => $user->users_id,
            'title' => 'Kebakaran di Hutan Lindung yang Diperbarui',
            'description' => 'Deskripsi telah diperbarui untuk penanganan lebih cepat.',
            'status' => 'pending',
        ]);
    }
}
