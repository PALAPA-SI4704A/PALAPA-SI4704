<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HapusLaporanNonAdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_petugas_non_admin_menghapus_laporan_masuk(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $report = Report::factory()->create([
            'status' => 'pending',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);

        $this->browse(function (Browser $browser) use ($petugas, $report) {
            $browser->loginAs($petugas)
                    ->visit('/petugas/reports/' . $report->report_id)
                    ->waitForText($report->title ?? 'Detail Laporan')
                    ->assertDontSee('Hapus Laporan')
                    ->script("
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/admin/reports/" . $report->report_id . "';
                        
                        let csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('input[name=\"_token\"]').value;
                        form.appendChild(csrf);
                        
                        let method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        form.appendChild(method);
                        
                        document.body.appendChild(form);
                        form.submit();
                    ");
            
            $browser->pause(2000)
                    ->assertSee('ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        });
    }
}
