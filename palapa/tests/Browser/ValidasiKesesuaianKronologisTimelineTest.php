<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\StatusHistory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ValidasiKesesuaianKronologisTimelineTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.007 - Validasi kesesuaian kronologis timeline status laporan
     */
    public function test_TC_History_007_chronological_timeline(): void
    {
        $user = User::factory()->create([
            'email' => 'warga7@palapa.com',
            'role' => 'masyarakat',
        ]);

        $report = Report::factory()->create([
            'user_id' => $user->users_id,
            'title' => 'Kebakaran Hutan Rakyat',
            'status' => 'pending',
        ]);

        // Add historical steps
        StatusHistory::create([
            'report_id' => $report->report_id,
            'status_awal' => 'pending',
            'status_baru' => 'valid',
            'catatan' => 'Laporan diverifikasi valid oleh admin.',
            'diubah_oleh' => 'Admin Sistem (Admin)',
            'tanggal_ubah' => now()->addMinutes(1),
        ]);

        StatusHistory::create([
            'report_id' => $report->report_id,
            'status_awal' => 'valid',
            'status_baru' => 'diproses',
            'catatan' => 'Petugas telah dikirim ke lapangan.',
            'diubah_oleh' => 'Petugas Pemadam (Petugas)',
            'tanggal_ubah' => now()->addMinutes(2),
        ]);

        StatusHistory::create([
            'report_id' => $report->report_id,
            'status_awal' => 'diproses',
            'status_baru' => 'selesai',
            'catatan' => 'Kebakaran berhasil dipadamkan total.',
            'diubah_oleh' => 'Petugas Pemadam (Petugas)',
            'tanggal_ubah' => now()->addMinutes(3),
        ]);

        $report->update(['status' => 'selesai']);

        $this->browse(function (Browser $browser) use ($user, $report) {
            $browser->loginAs($user)
                    ->visit('/reports/' . $report->report_id . '/history')
                    ->waitForText('Riwayat Status')
                    ->assertSee('pending')
                    ->assertSee('valid')
                    ->assertSee('diproses')
                    ->assertSee('selesai')
                    ->assertSee('Laporan berhasil dibuat oleh pelapor.')
                    ->assertSee('Laporan diverifikasi valid oleh admin.')
                    ->assertSee('Petugas telah dikirim ke lapangan.')
                    ->assertSee('Kebakaran berhasil dipadamkan total.');
        });
    }
}
