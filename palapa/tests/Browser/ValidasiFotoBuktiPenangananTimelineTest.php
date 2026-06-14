<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\StatusHistory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ValidasiFotoBuktiPenangananTimelineTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.009 - Validasi kemunculan foto bukti penanganan di akhir timeline
     */
    public function test_TC_History_009_handling_evidence_in_timeline(): void
    {
        $user = User::factory()->create([
            'email' => 'warga9@palapa.com',
            'role' => 'masyarakat',
        ]);

        // Create finished report with bukti_foto
        $report = Report::factory()->create([
            'user_id' => $user->users_id,
            'title' => 'Laporan Selesai dengan Bukti',
            'status' => 'selesai',
            'bukti_foto' => 'bukti_penanganan/test_bukti.jpg',
            'handling_note' => 'Padam tuntas pukul 21:00.',
        ]);

        StatusHistory::create([
            'report_id' => $report->report_id,
            'status_awal' => 'diproses',
            'status_baru' => 'selesai',
            'catatan' => 'Kebakaran dipadamkan total.',
            'diubah_oleh' => 'Petugas Pemadam (Petugas)',
            'tanggal_ubah' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($user, $report) {
            $browser->loginAs($user)
                    ->visit('/reports/' . $report->report_id . '/history')
                    ->waitForText('Riwayat Status')
                    ->assertSee('selesai')
                    ->assertPresent('.bukti-foto-img');
        });
    }
}
