<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCTrend007Test extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_trend_007_validasi_kecocokan_data_pie_chart_dengan_database(): void
    {
        // 1. Arrange: Persiapkan data Admin dan Pelapor
        $admin = User::factory()->create([
            'role' => 'admin',
            'users_name' => 'Admin Utama',
        ]);
        
        $pelapor = User::factory()->create([
            'role' => 'masyarakat',
            'users_name' => 'Warga Biasa',
        ]);

        // Buat data laporan dengan status berbeda untuk dihitung
        // 2x Pending
        Report::factory()->count(2)->create([
            'user_id' => $pelapor->users_id,
            'status' => 'pending',
            'address' => 'Pontianak, Kalimantan Barat',
            'created_at' => Carbon::now(),
        ]);
        // 3x Valid
        Report::factory()->count(3)->create([
            'user_id' => $pelapor->users_id,
            'status' => 'valid',
            'address' => 'Pontianak, Kalimantan Barat',
            'created_at' => Carbon::now(),
        ]);
        // 1x Selesai
        Report::factory()->create([
            'user_id' => $pelapor->users_id,
            'status' => 'selesai',
            'address' => 'Pontianak, Kalimantan Barat',
            'created_at' => Carbon::now(),
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            // 2. Act: Login sebagai admin dan kunjungi halaman Tren & Distribusi
            $browser->loginAs($admin)
                    ->visit('/admin/tren-distribusi')
                    ->waitForText('Tren & Distribusi Laporan')
                    ->assertPresent('#statusChart');

            // 3. Retrieve: Ambil data dari Chart.js Pie Chart via JavaScript
            $rawPieChartData = $browser->script('return Chart.getChart("statusChart").data.datasets[0].data;');
            $pieChartData = $rawPieChartData[0] ?? [];
            
            // Urutan status di chart: ['pending', 'valid', 'diproses', 'selesai', 'ditolak']
            $pendingInChart = $pieChartData[0] ?? 0;
            $validInChart = $pieChartData[1] ?? 0;
            $diprosesInChart = $pieChartData[2] ?? 0;
            $selesaiInChart = $pieChartData[3] ?? 0;
            $ditolakInChart = $pieChartData[4] ?? 0;

            // 4. Assert: Bandingkan dengan data riil di database
            $this->assertEquals(2, (int)$pendingInChart);
            $this->assertEquals(3, (int)$validInChart);
            $this->assertEquals(0, (int)$diprosesInChart);
            $this->assertEquals(1, (int)$selesaiInChart);
            $this->assertEquals(0, (int)$ditolakInChart);

            $browser->screenshot('F07_TC_Trend_007_Validation_Database');
        });
    }
}
