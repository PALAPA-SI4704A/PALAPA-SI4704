<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ValidasiTimestampTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.Complete.007 - Validasi perekaman otomatis timestamp penyelesaian laporan
     */
    public function test_TC_Complete_007_validasi_timestamp_penyelesaian_laporan(): void
    {
        $petugas = User::factory()->create([
            'users_name' => 'Petugas Tujuh',
            'email'      => 'petugas7@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'petugas',
        ]);

        $report = Report::factory()->create([
            'status' => 'diproses',
            'title'  => 'Kebakaran Gudang',
            'assigned_petugas_id' => $petugas->users_id,
        ]);

        $penugasan = Penugasan::create([
            'report_id'  => $report->report_id,
            'petugas_id' => $petugas->users_id,
            'assigned_at'=> now()->subHours(2),
            'completed_at'=> null,
        ]);

        $this->browse(function (Browser $browser) use ($petugas, $report, $penugasan) {
            $browser->logout()
                    ->loginAs($petugas)
                    ->visit(route('petugas.reports.show', $report->report_id))
                    ->waitForText('Update Status Penanganan')
                    ->radio('status', 'selesai')
                    ->type('catatan', 'Penanganan selesai secara tuntas.')
                    ->attach('bukti_foto', public_path('images/logo-palapa.png'))
                    ->press('Simpan Perubahan Status')
                    ->waitForText('Status laporan berhasil diperbarui menjadi: Resolved');

            $this->assertDatabaseMissing('penugasan', [
                'penugasan_id' => $penugasan->penugasan_id,
                'completed_at' => null,
            ]);

            $updatedPenugasan = Penugasan::find($penugasan->penugasan_id);
            $this->assertNotNull($updatedPenugasan->completed_at);
            
            $browser->screenshot('tc-complete-007-success');
        });
    }
}
