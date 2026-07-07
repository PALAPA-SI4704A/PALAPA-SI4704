<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCTrend001Test extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_trend_001_tampilan_default_halaman_tren_distribusi_laporan(): void
    {
        // 1. Arrange: Persiapan data Admin, Pelapor, dan Laporan
        $admin = User::factory()->create([
            'role' => 'admin',
            'users_name' => 'Admin Utama',
        ]);
        
        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
            'users_name' => 'Warga Biasa',
        ]);

        Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'pending',
            'address' => 'Pontianak, Kalimantan Barat',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            // 2. Act & Assert: Langkah 1 - Login sebagai admin utama
            $browser->loginAs($admin)
                    ->visit('/admin/dashboard')
                    ->waitForText('Laporan Masuk')
                    ->assertSee('Laporan Masuk Hari Ini') // Verifikasi Dashboard admin termuat

                    // Langkah 2 - Klik menu "Tren & Distribusi" pada sidebar admin
                    ->clickLink('Tren & Distribusi')
                    ->waitForText('Tren & Distribusi Laporan') // Verifikasi Halaman tren dimuat sukses

                    // Langkah 3 - Periksa visual grafik batang (Bar Chart) tren per periode & status
                    ->assertPresent('#trenChart') // Verifikasi Grafik batang ter-render dengan sukses

                    // Langkah 4 - Verifikasi rentang waktu default (7 Hari Terakhir)
                    ->assertSelected('select[name="period"]', '7days') // Verifikasi Sumbu X menampilkan 7 hari terakhir
                    
                    // Ambil screenshot sebagai bukti/evidence
                    ->screenshot('F07_TC_Trend_001_Default');
        });
    }
}
