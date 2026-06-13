<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BuatLaporanFieldKosongTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testNegatifLaporanFieldKosong(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
        ]);

        $this->browse(function (Browser $browser) use ($user): void {
            $browser->loginAs($user)
                ->visit('/reports/create')
                ->assertSee('Buat Laporan')
                ->type('title', '')
                ->press('Lanjutkan Preview')
                // Kita verifikasi bahwa URL TIDAK BERUBAH (tetap di halaman create)
                ->assertPathIs('/reports/create');
        });
    }
}
