<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateStatusPetugasDatabaseTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_pengecekan_status_petugas_terupdate_secara_real_time_di_database(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $report = Report::factory()->create([
            'status' => 'valid',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas Sembilan',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report, $petugas) {
            $browser->loginAs($admin)
                    ->visit('/admin/reports/' . $report->report_id)
                    ->waitForText('Petugas Tersedia')
                    ->click('form[action*="/admin/reports/' . $report->report_id . '/assign/' . $petugas->users_id . '"] button')
                    ->waitForText('berhasil ditugaskan');
            
            $this->assertDatabaseHas('penugasan', [
                'report_id' => $report->report_id,
                'petugas_id' => $petugas->users_id,
                'completed_at' => null,
            ]);
        });
    }
}
