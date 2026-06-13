<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class VerifikasiLaporanMasukTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_verfikasi_laporan_masuk(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $report = Report::factory()->create([
            'status' => 'pending',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report) {
            $browser->loginAs($admin)
                    ->visit('/admin/reports')
                    ->waitForText('Daftar Seluruh Laporan')
                    ->click('a[href*="/admin/reports/' . $report->report_id . '"]')
                    ->waitForText('Detail Laporan')
                    ->assertSee('Verifikasi Laporan')
                    ->click('.btn-accept')
                    ->waitForText('Laporan berhasil diverifikasi menjadi: Valid')
                    ->assertSee('Valid')
                    ->assertSee('Petugas Tersedia');
        });
    }
}
