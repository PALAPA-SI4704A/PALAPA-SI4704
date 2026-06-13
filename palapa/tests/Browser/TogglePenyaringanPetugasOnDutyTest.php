<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TogglePenyaringanPetugasOnDutyTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_toggle_penyaringan_petugas_tampilkan_atau_sembunyikan_on_duty(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugasAvailable = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas Available 7',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);
        $petugasOnDuty = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas On Duty 7',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);

        $otherReport = Report::factory()->create(['status' => 'diproses']);
        Penugasan::create([
            'report_id' => $otherReport->report_id,
            'petugas_id' => $petugasOnDuty->users_id,
            'assigned_at' => now(),
        ]);

        $report = Report::factory()->create([
            'status' => 'valid',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report, $petugasAvailable, $petugasOnDuty) {
            $browser->loginAs($admin)
                    ->visit('/admin/reports/' . $report->report_id)
                    ->waitForText('Petugas Tersedia')
                    ->assertSee($petugasAvailable->users_name)
                    ->assertDontSee($petugasOnDuty->users_name)
                    ->click('div[title="Klik untuk menampilkan/menyembunyikan petugas yang sedang sibuk"]')
                    ->waitForText($petugasOnDuty->users_name)
                    ->assertSee($petugasAvailable->users_name)
                    ->click('div[title="Klik untuk menampilkan/menyembunyikan petugas yang sedang sibuk"]')
                    ->pause(500)
                    ->assertDontSee($petugasOnDuty->users_name)
                    ->assertSee($petugasAvailable->users_name);
        });
    }
}
