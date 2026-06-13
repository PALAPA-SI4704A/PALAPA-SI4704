<?php

namespace Tests\Browser;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PetugasUbahStatusTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testPetugasUbahStatus(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'email' => 'petugas@example.com',
        ]);

        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
            'email' => 'pelapor@example.com',
        ]);

        $report = Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'diproses',
            'title' => 'Kebakaran sedang ditangani',
            'description' => 'Laporan dalam proses penanganan.',
            'latitude' => -2.548900,
            'longitude' => 118.014900,
        ]);

        $photoPath = base_path('public/images/logo-palapa.png');

        $this->browse(function (Browser $browser) use ($petugas, $report, $photoPath): void {
            $browser->loginAs($petugas)
                ->visit('/petugas/reports/' . $report->report_id)
                ->assertSee('Update Status Penanganan')
                ->click('input[name="status"][value="selesai"]')
                ->type('catatan', 'Laporan berhasil ditangani dan dokumentasi telah dilampirkan.')
                ->attach('bukti_foto', $photoPath)
                ->press('Simpan Perubahan Status')
                ->assertPathIs('/petugas/reports/' . $report->report_id)
                ->assertSee('Status laporan berhasil diperbarui');
        });

        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'status' => 'selesai',
        ]);
    }
}
