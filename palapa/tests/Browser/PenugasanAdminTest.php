<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PenugasanAdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_dapat_menugaskan_petugas_yang_tersedia(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $report = Report::factory()->create([
            'status' => 'valid',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas Available PBI31',
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
                    ->assertSee($petugas->users_name);
        });
    }

    public function test_admin_tidak_dapat_menugaskan_petugas_on_duty(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas On Duty PBI31',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);

        // Jadikan petugas sibuk (On Duty) pada laporan lain
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
                    // Tampilkan petugas sibuk
                    ->click('div[title="Klik untuk menampilkan/menyembunyikan petugas yang sedang sibuk"]')
                    ->waitForText($petugas->users_name)
                    ->assertSee($petugas->users_name)
                    ->assertSee('On Duty')
                    // Cek bahwa form action penugasan untuk petugas ini tidak ada (tidak bisa diklik)
                    ->assertMissing('form[action*="/admin/reports/' . $report->report_id . '/assign/' . $petugas->users_id . '"]');
        });
    }

    public function test_non_admin_tidak_dapat_mengakses_fitur_penugasan(): void
    {
        $pelapor = User::factory()->create(['role' => 'masyarakat']);
        $report = Report::factory()->create([
            'status' => 'valid',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas Target PBI31',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);

        $this->browse(function (Browser $browser) use ($pelapor, $report, $petugas) {
            // Login sebagai non-admin
            $browser->loginAs($pelapor)
                    ->visit('/reports') // Halaman yang bisa diakses untuk mendapat CSRF token
                    ->script("
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/admin/reports/" . $report->report_id . "/assign/" . $petugas->users_id . "';
                        
                        let csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('input[name=\"_token\"]').value;
                        form.appendChild(csrf);
                        
                        document.body.appendChild(form);
                        form.submit();
                    ");

            $browser->pause(2000)
                    ->assertSee('ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        });
    }
}
