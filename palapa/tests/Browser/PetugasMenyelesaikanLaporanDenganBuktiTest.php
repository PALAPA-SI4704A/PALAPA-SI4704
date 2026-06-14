<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PetugasMenyelesaikanLaporanDenganBuktiTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.Complete.002 - Petugas menyelesaikan laporan penanganan dengan bukti lengkap
     */
    public function test_TC_Complete_002_petugas_menyelesaikan_laporan_dengan_bukti_lengkap(): void
    {
        $petugas = User::factory()->create([
            'users_name' => 'Petugas Lapangan Dua',
            'email'      => 'petugas2@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'petugas',
        ]);

        $report = Report::factory()->create([
            'status' => 'diproses',
            'title'  => 'Kebakaran Ruko',
            'assigned_petugas_id' => $petugas->users_id,
        ]);

        Penugasan::create([
            'report_id'  => $report->report_id,
            'petugas_id' => $petugas->users_id,
            'assigned_at'=> now()->subHours(2),
            'completed_at'=> null,
        ]);

        $this->browse(function (Browser $browser) use ($petugas, $report) {
            $browser->logout()
                    ->loginAs($petugas)
                    ->visit(route('petugas.reports.show', $report->report_id))
                    ->waitForText('Update Status Penanganan')
                    ->assertSee('In Progress')
                    ->radio('status', 'selesai')
                    ->type('catatan', 'Api berhasil padam total pukul 20:30 WITA')
                    ->attach('bukti_foto', public_path('images/logo-palapa.png'))
                    ->press('Simpan Perubahan Status')
                    ->waitForText('Status laporan berhasil diperbarui menjadi: Resolved')
                    ->assertSee('Resolved')
                    ->screenshot('tc-complete-002-success');
        });
    }
}
