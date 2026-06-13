<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MenugaskanPetugasLapanganAvailableTest extends DuskTestCase
{
    use DatabaseMigrations;
    public function test_menugaskan_petugas_yang_tersedia(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $report = Report::factory()->create([
            'status' => 'valid',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas Available',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report, $petugas) {
            $browser->loginAs($admin)
                    ->visit('/admin/reports/' . $report->report_id)
                    ->waitForText('Petugas Tersedia')
                    ->assertSee($petugas->users_name)
                    ->assertSee('Available')
                    ->click('form[action*="/admin/reports/' . $report->report_id . '/assign/' . $petugas->users_id . '"] button')
                    ->waitForText('berhasil ditugaskan')
                    ->assertSee('Diproses')
                    ->assertSee('Status Penugasan Petugas')
                    ->assertSee($petugas->users_name);
        });
    }
}
