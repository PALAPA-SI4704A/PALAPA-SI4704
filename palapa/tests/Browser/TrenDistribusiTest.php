<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TrenDistribusiTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tren_dan_distribusi_laporan(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'users_name' => 'Admin Utama',
        ]);
        
        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
            'users_name' => 'Warga Biasa',
        ]);

        // Buat beberapa laporan untuk populasi grafik
        Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'pending',
            'address' => 'Pontianak, Kalimantan Barat',
        ]);
        Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'valid',
            'address' => 'Balikpapan, Kalimantan Timur',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $pelapor) {
            // ==========================================
            // TC.Trend.001: Tampilan Default Halaman Tren
            // ==========================================
            $browser->loginAs($admin)
                    ->visit('/admin/tren-distribusi')
                    ->waitForText('Tren & Distribusi Laporan')
                    ->assertSee('Tren Laporan Per Periode & Status')
                    ->assertPresent('#trenChart')
                    ->screenshot('F07_TC_Trend_001_Default');

            // ==========================================
            // TC.Trend.002: Filter Perubahan Periode
            // ==========================================
            // Pilih 30 Hari Terakhir
            $browser->visit('/admin/tren-distribusi?period=30days')
                    ->waitForText('Tren Laporan Per Periode & Status')
                    ->assertSelected('select[name="period"]', '30days')
                    ->screenshot('F07_TC_Trend_002_Filter_30Days');

            // Pilih Tahun Ini
            $browser->visit('/admin/tren-distribusi?period=year')
                    ->waitForText('Tren Laporan Per Periode & Status')
                    ->assertSelected('select[name="period"]', 'year')
                    ->screenshot('F07_TC_Trend_002_Filter_Year');

            // ==========================================
            // TC.Trend.003: Grafik Distribusi & Wilayah
            // ==========================================
            $browser->assertPresent('#statusChart')
                    ->assertPresent('#wilayahChart')
                    ->assertSee('Distribusi Per Status')
                    ->assertSee('Distribusi Per Wilayah')
                    ->screenshot('F07_TC_Trend_003_Distribusi');

            // ==========================================
            // TC.Trend.004: Pembatasan Hak Akses
            // ==========================================
            $browser->logout()
                    ->loginAs($pelapor)
                    ->visit('/reports') // dapatkan token halaman
                    // Akses halaman tren secara manual
                    ->visit('/admin/tren-distribusi')
                    ->pause(1500)
                    ->assertSee('ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.')
                    ->screenshot('F07_TC_Trend_004_AccessBlocked');
        });
    }
}
