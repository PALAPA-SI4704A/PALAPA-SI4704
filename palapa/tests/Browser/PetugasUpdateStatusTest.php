<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PetugasUpdateStatusTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.Complete.001 - Petugas lapangan memperbarui status laporan menjadi diproses
     */
    public function test_TC_Complete_001_petugas_memperbarui_status_laporan_menjadi_diproses(): void
    {
        $petugas = User::factory()->create([
            'users_name' => 'Petugas Lapangan Satu',
            'email'      => 'petugas1@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'petugas',
        ]);
        $petugasId = $petugas->users_id ?? $petugas->id;

        $report = Report::factory()->create([
            'status' => 'assigned', 
            'title'  => 'Kebakaran di Hutan Kota',
            'assigned_petugas_id' => $petugasId,
        ]);
        $reportId = $report->report_id ?? $report->id;

        Penugasan::create([
            'report_id'   => $reportId,
            'petugas_id'  => $petugasId,
            'assigned_at' => now(),
            'completed_at'=> null,
        ]);

        $formattedDate = now()->format('d/m/Y'); 

        $this->browse(function (Browser $browser) use ($petugas, $formattedDate) {
            $browser->logout()
                    ->loginAs($petugas)
                    ->visit(route('petugas.dashboard'))
                    ->waitForText('Laporan Masuk')
                    ->waitForText($formattedDate, 10)
                    ->assertSee($formattedDate)
                    ->assertSee('Assigned') 
                    ->clickLink('[Lihat]')
                    ->waitForText('Update Status Penanganan')
                    ->assertSee('Assigned') 
                    ->radio('status', 'diproses')
                    ->type('catatan', 'Unit pemadam pos terdekat bergerak menuju lokasi dengan 5 personel')
                    ->press('Simpan Perubahan Status')
                    ->waitForText('Status laporan berhasil diperbarui menjadi: In Progress')
                    ->assertSee('In Progress')
                    ->screenshot('tc-complete-001-success');
        });
    }
}