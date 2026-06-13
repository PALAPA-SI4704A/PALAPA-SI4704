<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MenolakLaporanTanpaAlasanTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_menolak_laporan_masuk_tanpa_alasan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $report = Report::factory()->create([
            'status' => 'pending',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report) {
            $browser->loginAs($admin)
                    ->visit('/admin/reports/' . $report->report_id)
                    ->waitForText('Verifikasi Laporan')
                    ->click('.btn-verify.btn-reject')
                    ->waitFor('textarea[name="rejection_reason"]')
                    ->assertAttribute('textarea[name="rejection_reason"]', 'required', 'true')
                    ->press('Konfirmasi Tolak')
                    ->assertSee('Menunggu Verifikasi');
        });
    }
}
