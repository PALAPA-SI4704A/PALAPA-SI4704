<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MenugaskanPetugasOnDutyTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_menugaskan_petugas_lapangan_berstatus_on_duty(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas On Duty',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);

        $otherReport = Report::factory()->create([
            'status' => 'diproses',
            'assigned_petugas_id' => $petugas->users_id,
        ]);
        Penugasan::create([
            'report_id' => $otherReport->report_id,
            'petugas_id' => $petugas->users_id,
            'assigned_at' => now(),
        ]);

        $report = Report::factory()->create([
            'status' => 'valid',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report, $petugas) {
            $browser->loginAs($admin)
                    ->visit('/admin/reports/' . $report->report_id)
                    ->waitForText('Petugas Tersedia')
                    ->assertDontSee($petugas->users_name)
                    ->click('div[title="Klik untuk menampilkan/menyembunyikan petugas yang sedang sibuk"]')
                    ->waitForText($petugas->users_name)
                    ->assertSee($petugas->users_name)
                    ->assertSee('On Duty')
                    ->assertMissing('form[action*="/admin/reports/' . $report->report_id . '/assign/' . $petugas->users_id . '"]');
        });
    }
}
