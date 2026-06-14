<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CatatanLaporanKosongTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.Complete.006 - Mengirim catatan akhir penyelesaian laporan yang kosong
     */
    public function test_TC_Complete_006_mengirim_catatan_akhir_kosong(): void
    {
        $petugas = User::factory()->create([
            'users_name' => 'Petugas Enam',
            'email'      => 'petugas6@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'petugas',
        ]);

        $report = Report::factory()->create([
            'status' => 'diproses',
            'title'  => 'Kebakaran Pemukiman',
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
                    ->attach('bukti_foto', public_path('images/logo-palapa.png'))
                    ->keys('textarea[name="catatan"]', ['{backspace}'])
                    ->press('Simpan Perubahan Status')
                    ->pause(1000)
                    ->assertSee('In Progress')
                    ->assertDontSee('Status laporan berhasil diperbarui menjadi: Resolved')
                    ->screenshot('tc-complete-006-failure');
        });
    }
}
