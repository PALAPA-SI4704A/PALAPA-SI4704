<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UbahPenugasanAdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_dapat_mengubah_penugasan_petugas_lapangan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugasA = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas A PBI32',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);
        $petugasB = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas B PBI32',
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
                    ->assertSee($petugasB->users_name)
                    ->click('form[action*="/admin/reports/' . $report->report_id . '/reassign/' . $petugasB->users_id . '"] button')
                    ->waitForText('Konfirmasi Tindakan')
                    ->click('#confirm-modal-submit')
                    ->waitForText('Penugasan berhasil diubah ke ' . $petugasB->users_name)
                    ->assertSee('Diproses')
                    ->assertSee($petugasB->users_name);
        });
    }

    public function test_admin_tidak_dapat_mengubah_penugasan_ke_petugas_yang_sama(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugasA = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas A PBI32',
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

        $this->browse(function (Browser $browser) use ($admin, $report, $petugasA) {
            $browser->loginAs($admin)
                    ->visit('/admin/dashboard') // Halaman yang bisa diakses untuk mendapat CSRF token
                    ->script("
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/admin/reports/" . $report->report_id . "/reassign/" . $petugasA->users_id . "';
                        
                        let csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('input[name=\"_token\"]').value;
                        form.appendChild(csrf);
                        
                        document.body.appendChild(form);
                        form.submit();
                    ");

            $browser->pause(2000)
                    ->assertSee('Petugas ini sudah ditugaskan pada laporan ini.');
        });
    }

    public function test_admin_tidak_dapat_mengubah_penugasan_ke_petugas_on_duty_di_laporan_lain(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugasA = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas A PBI32',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);
        $petugasB = User::factory()->create([
            'role' => 'petugas',
            'users_name' => 'Petugas B Busy PBI32',
            'latitude' => -1.2654,
            'longitude' => 116.8312,
            'pos_name' => 'Pos Induk Balikpapan',
        ]);

        $report1 = Report::factory()->create([
            'status' => 'diproses',
            'assigned_petugas_id' => $petugasA->users_id,
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);
        Penugasan::create([
            'report_id' => $report1->report_id,
            'petugas_id' => $petugasA->users_id,
            'assigned_at' => now(),
        ]);

        // Laporan lain dimana petugas B bertugas
        $report2 = Report::factory()->create([
            'status' => 'diproses',
            'assigned_petugas_id' => $petugasB->users_id,
            'latitude' => -1.2654,
            'longitude' => 116.8312,
        ]);
        Penugasan::create([
            'report_id' => $report2->report_id,
            'petugas_id' => $petugasB->users_id,
            'assigned_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin, $report1, $petugasB) {
            $browser->loginAs($admin)
                    ->visit('/admin/reports/' . $report1->report_id)
                    ->waitForText('Petugas Tersedia')
                    ->assertDontSee($petugasB->users_name)
                    // Tampilkan petugas sibuk
                    ->click('div[title="Klik untuk menampilkan/menyembunyikan petugas yang sedang sibuk"]')
                    ->waitForText($petugasB->users_name)
                    ->assertSee($petugasB->users_name)
                    ->assertSee('On Duty')
                    // Form action reassign tidak boleh ada atau jika dikirim paksa akan error
                    ->assertMissing('form[action*="/admin/reports/' . $report1->report_id . '/reassign/' . $petugasB->users_id . '"]');
        });
    }

    public function test_non_admin_tidak_dapat_mengakses_fitur_ubah_penugasan(): void
    {
        $pelapor = User::factory()->create(['role' => 'masyarakat']);
        $petugasA = User::factory()->create(['role' => 'petugas']);
        $petugasB = User::factory()->create(['role' => 'petugas']);
        
        $report = Report::factory()->create([
            'status' => 'diproses',
            'assigned_petugas_id' => $petugasA->users_id,
        ]);
        Penugasan::create([
            'report_id' => $report->report_id,
            'petugas_id' => $petugasA->users_id,
            'assigned_at' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($pelapor, $report, $petugasB) {
            $browser->loginAs($pelapor)
                    ->visit('/reports') // Halaman yang bisa diakses untuk mendapat CSRF token
                    ->script("
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/admin/reports/" . $report->report_id . "/reassign/" . $petugasB->users_id . "';
                        
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
