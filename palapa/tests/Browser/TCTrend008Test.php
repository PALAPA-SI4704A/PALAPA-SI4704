<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCTrend008Test extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_trend_008_validasi_parsing_wilayah_dari_alamat(): void
    {
        // 1. Arrange: Persiapan data Admin, Pelapor, dan Laporan dengan alamat terstruktur
        $admin = User::factory()->create([
            'role' => 'admin',
            'users_name' => 'Admin Utama',
        ]);
        
        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
            'users_name' => 'Warga Biasa',
        ]);

        // Alamat lengkap: 'Tarakan, Kalimantan Utara'
        Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'address' => 'Tarakan, Kalimantan Utara',
            'created_at' => Carbon::now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            // 2. Act: Login sebagai admin dan kunjungi halaman Tren & Distribusi
            $browser->loginAs($admin)
                    ->visit('/admin/tren-distribusi')
                    ->waitForText('Tren & Distribusi Laporan')
                    ->assertPresent('#wilayahChart');

            // 3. Retrieve & Assert: Ambil labels dari Chart.js Wilayah Chart via JavaScript
            $wilayahLabels = $browser->script('return Chart.getChart("wilayahChart").data.labels;');
            $labelsArray = $wilayahLabels[0] ?? [];

            // Memastikan Tarakan sukses terekstrak dari alamat dan tampil di grafik
            $this->assertTrue(in_array('Tarakan', $labelsArray), 'Kategori Tarakan tidak ditemukan di label grafik wilayah.');

            $browser->screenshot('F07_TC_Trend_008_Region_Parsing');
        });
    }
}
