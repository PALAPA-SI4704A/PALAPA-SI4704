<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

class ValidasiPembaruanStatistikTest extends DuskTestCase
{
    use DatabaseMigrations;

    #[Test]
    public function skenario_update_status_ke_diproses_langsung_cek_dashboard()
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $petugasId = $petugas->users_id ?? $petugas->id;

        $report = Report::factory()->create([
            'title' => 'Kebakaran',
            'status' => 'assigned',
            'assigned_petugas_id' => $petugasId,
        ]);

        $reportId = $report->report_id ?? $report->id;

        Penugasan::create([
            'report_id'   => $reportId,
            'petugas_id'  => $petugasId,
            'assigned_at' => now(),
            'completed_at'=> null,
        ]);

        $this->browse(function (Browser $browser) use ($petugas, $report) {

            $browser->loginAs($petugas)
                    ->visit('/petugas/reports/' . $report->report_id)
                    ->waitForText('Update Status Penanganan')
                    ->radio('status', 'diproses')
                    ->type('catatan', 'Laporan diproses.')
                    ->press('Simpan Perubahan Status')
                    ->pause(2000);

            $browser->visit('/petugas/dashboard')
                    ->waitForText('Diproses', 10);

            dump(
                Report::count(),
                Report::where('status', 'diproses')->count(),
                $browser->text('.stats-grid')
            );

            $browser->waitUsing(
                10,
                1,
                function () use ($browser) {
                    return trim(
                        $browser->text('.stats-grid .stat-card:nth-child(2) .value')
                    ) === '1';
                },
                'Angka statistik tidak berubah menjadi 1 setelah 10 detik'
            );

            $browser->assertSeeIn(
                '.stats-grid .stat-card:nth-child(2) .value',
                '1'
            );
            $browser->screenshot('statistik');
        });

        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'status'    => 'diproses',
        ]);
    }
}