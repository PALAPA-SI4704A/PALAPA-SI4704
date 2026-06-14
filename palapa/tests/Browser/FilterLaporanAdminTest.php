<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Carbon\Carbon;

class FilterLaporanAdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_admin_dapat_memfilter_laporan_berdasarkan_wilayah_status_dan_tanggal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pelapor = User::factory()->create(['role' => 'masyarakat']);

        // 1. Buat Laporan Pontianak, Status Pending, Tanggal 2026-06-10
        $reportA = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Kebakaran Hutan Pontianak',
            'description' => 'Kebakaran Hutan Pontianak',
            'latitude' => '-0.0227',
            'longitude' => '109.3323',
            'status' => 'pending',
            'address' => 'Jl. Pontianak Jaya, Pontianak',
        ]);
        $reportA->created_at = Carbon::parse('2026-06-10 12:00:00');
        $reportA->save();

        // 2. Buat Laporan Balikpapan, Status Valid, Tanggal 2026-06-11
        $reportB = Report::create([
            'user_id' => $pelapor->users_id,
            'title' => 'Asap Tebal Balikpapan',
            'description' => 'Asap Tebal Balikpapan',
            'latitude' => '-1.2654',
            'longitude' => '116.8312',
            'status' => 'valid',
            'address' => 'Jl. Balikpapan Baru, Balikpapan',
        ]);
        $reportB->created_at = Carbon::parse('2026-06-11 12:00:00');
        $reportB->save();

        $this->browse(function (Browser $browser) use ($admin, $reportA, $reportB) {
            $browser->loginAs($admin)
                    ->visit('/admin/dashboard')
                    ->waitForText('Laporan Masuk')

                    // UJI WILAYAH (Pontianak)
                    ->select('region', 'Pontianak')
                    ->press('Cari')
                    ->pause(1500)
                    ->assertSee('Kebakaran Hutan Pontianak')
                    ->assertDontSee('Asap Tebal Balikpapan')

                    // Reset filter dengan kembali ke dashboard
                    ->visit('/admin/dashboard')
                    ->waitForText('Laporan Masuk')

                    // UJI STATUS (Valid)
                    ->select('status', 'valid')
                    ->press('Cari')
                    ->pause(1500)
                    ->assertSee('Asap Tebal Balikpapan')
                    ->assertDontSee('Kebakaran Hutan Pontianak')

                    // Reset filter
                    ->visit('/admin/dashboard')
                    ->waitForText('Laporan Masuk');

                    // UJI TANGGAL (2026-06-10)
                    $browser->script("document.querySelector('input[type=\"date\"]').value = '2026-06-10';");
                    $browser->press('Cari')
                            ->pause(1500)
                            ->assertSee('Kebakaran Hutan Pontianak')
                            ->assertDontSee('Asap Tebal Balikpapan')

                            // UJI KOMBINASI
                            ->visit('/admin/dashboard')
                            ->waitForText('Laporan Masuk')
                            ->select('region', 'Pontianak')
                            ->select('status', 'pending');
                    $browser->script("document.querySelector('input[type=\"date\"]').value = '2026-06-10';");
                    $browser->press('Cari')
                            ->pause(1500)
                            ->assertSee('Kebakaran Hutan Pontianak')
                            ->assertDontSee('Asap Tebal Balikpapan');
        });
    }
}
