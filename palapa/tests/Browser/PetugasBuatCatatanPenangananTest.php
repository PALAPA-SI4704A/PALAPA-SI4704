<?php

namespace Tests\Browser;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PetugasBuatCatatanPenangananTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testPetugasBuatCatatanPenanganan(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'email' => 'petugas-catatan@example.com',
        ]);

        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
            'email' => 'pelapor-catatan@example.com',
        ]);

        $report = Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'diproses',
            'title' => 'Laporan sedang ditangani petugas',
            'description' => 'Laporan yang sedang dalam penanganan petugas.',
            'latitude' => -2.548900,
            'longitude' => 118.014900,
        ]);

        $catatan = 'Petugas telah mendatangi lokasi dan menyiapkan peralatan pemadaman.';

        $this->browse(function (Browser $browser) use ($petugas, $report, $catatan): void {
            $browser->loginAs($petugas)
                ->visit('/petugas/reports/' . $report->report_id)
                ->assertSee('Update Status Penanganan')
                ->assertSee('Catatan Tindakan / Deskripsi Penanganan')
                ->click('input[name="status"][value="diproses"]')
                ->type('catatan', $catatan)
                ->press('Simpan Perubahan Status')
                ->assertPathIs('/petugas/reports/' . $report->report_id)
                ->assertSee('Status laporan berhasil diperbarui');
        });

        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
            'status' => 'diproses',
        ]);

        $this->assertDatabaseHas('status_histories', [
            'report_id' => $report->report_id,
            'catatan' => $catatan,
        ]);
    }
}
