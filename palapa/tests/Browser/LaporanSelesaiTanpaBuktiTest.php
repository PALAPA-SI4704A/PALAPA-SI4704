<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LaporanSelesaiTanpaBuktiTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.Complete.004 - Menyelesaikan laporan tanpa mengunggah berkas foto bukti penanganan
     */
    public function test_TC_Complete_004_menyelesaikan_laporan_tanpa_foto_bukti(): void
    {
        $petugas = User::factory()->create([
            'users_name' => 'Petugas Empat',
            'email'      => 'petugas4@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'petugas',
        ]);

        $report = Report::factory()->create([
            'status' => 'diproses',
            'title'  => 'Kebakaran Lahan',
            'assigned_petugas_id' => $petugas->users_id,
        ]);

        Penugasan::create([
            'report_id'  => $report->report_id,
            'petugas_id' => $petugas->users_id,
            'assigned_at'=> now()->subHours(1),
            'completed_at'=> null,
        ]);

        $this->browse(function (Browser $browser) use ($petugas, $report) {
            $browser->logout()
                    ->loginAs($petugas)
                    ->visit(route('petugas.reports.show', $report->report_id))
                    ->waitForText('Update Status Penanganan')
                    ->assertSee('In Progress')
                    ->radio('status', 'selesai')
                    ->type('catatan', 'Api berhasil padam total.')
                    ->press('Simpan Perubahan Status')
                    ->pause(1000)
                    ->assertSee('In Progress')
                    ->assertDontSee('Status laporan berhasil diperbarui menjadi: Resolved')
                    ->screenshot('tc-complete-004-failure');
        });
    }
}
