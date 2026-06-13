<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class KonfirmasiModalPopupReassignTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_reassign_petugas_lapangan_dengan_modal_konfirmasi(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugasA = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas A 8',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);
        $petugasB = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas B 8',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);

        $report = Report::factory()->create([
            'status' => 'diproses',
            'assigned_petugas_id' => $petugasA->users_id,
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);
        Penugasan::create([
            'report_id' => $report->report_id,
            'petugas_id' => $petugasA->users_id,
            'assigned_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report, $petugasA, $petugasB) {
            $browser->loginAs($admin)
                    ->visit('/admin/reports/' . $report->report_id)
                    ->waitForText('Petugas Tersedia')
                    ->click('form[action*="/admin/reports/' . $report->report_id . '/reassign/' . $petugasB->users_id . '"] button')
                    ->waitForText('Konfirmasi Tindakan')
                    ->click('#confirm-modal-cancel')
                    ->pause(500)
                    ->assertDontSee('Penugasan berhasil diubah')
                    ->click('form[action*="/admin/reports/' . $report->report_id . '/reassign/' . $petugasB->users_id . '"] button')
                    ->waitForText('Konfirmasi Tindakan')
                    ->click('#confirm-modal-submit')
                    ->waitForText('Penugasan berhasil diubah ke ' . $petugasB->users_name);
        });
    }
}
