<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MencegahPetugasAksesLaporanLainTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.Complete.005 - Petugas mencoba mengakses/mengedit tugas milik petugas lain
     */
    public function test_TC_Complete_005_petugas_akses_tugas_petugas_lain(): void
    {
        $petugasA = User::factory()->create([
            'users_name' => 'Petugas A',
            'email'      => 'petugasA@palapa.com',
            'role'       => 'petugas',
        ]);

        $petugasB = User::factory()->create([
            'users_name' => 'Petugas B',
            'email'      => 'petugasB@palapa.com',
            'role'       => 'petugas',
        ]);

        $reportB = Report::factory()->create([
            'status' => 'diproses',
            'title'  => 'Kebakaran Lahan B',
            'assigned_petugas_id' => $petugasB->users_id,
        ]);

        $this->browse(function (Browser $browser) use ($petugasA, $reportB) {
            $browser->logout()
                    ->loginAs($petugasA)
                    ->visit(route('petugas.reports.show', $reportB->report_id))
                    ->waitForText('ANDA TIDAK DITUGASKAN UNTUK MENANGANI LAPORAN INI.')
                    ->assertSee('403')
                    ->assertSee('ANDA TIDAK DITUGASKAN UNTUK MENANGANI LAPORAN INI.')
                    ->screenshot('tc-complete-005-success');
        });
    }
}
