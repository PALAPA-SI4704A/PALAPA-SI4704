<?php

namespace Tests\Browser;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PetugasBuatBuktiPenangananTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testPetugasBuktiPenanganan(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'email' => 'petugas-bukti@example.com',
        ]);

        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
            'email' => 'pelapor-bukti@example.com',
        ]);

        $report = Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'diproses',
            'title' => 'Laporan sedang ditangani petugas',
            'description' => 'Laporan yang sedang dalam proses penanganan.',
            'latitude' => -2.548900,
            'longitude' => 118.014900,
        ]);

        $photoPath = base_path('public/images/logo-palapa.png');

        $this->browse(function (Browser $browser) use ($petugas, $report, $photoPath): void {
            $browser->loginAs($petugas)
                ->visit('/petugas/reports/' . $report->report_id)
                ->assertSee('Update Status Penanganan')
                ->click('input[name="status"][value="selesai"]')
                ->type('catatan', 'Bukti penanganan berhasil diunggah oleh petugas.')
                ->attach('bukti_foto', $photoPath)
                ->press('Simpan Perubahan Status')
                ->assertPathIs('/petugas/reports/' . $report->report_id);
        });

        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'status' => 'selesai',
        ]);
    }
}
