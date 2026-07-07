<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TCTrend005Test extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_tc_trend_005_filter_periode_tidak_valid(): void
    {
        // Arrange
        $admin = User::factory()->create([
            'role' => 'admin',
            'users_name' => 'Admin Utama',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    // Masukkan URL dengan parameter periode tidak valid
                    ->visit('/admin/tren-distribusi?period=invalid_value')
                    ->waitForText('Tren Laporan Per Periode & Status')
                    // Sistem harus otomatis kembali ke default (7 days) tanpa crash
                    ->assertSelected('select[name="period"]', '7days')
                    ->screenshot('F07_TC_Trend_005_Invalid_Period');
        });
    }
}
