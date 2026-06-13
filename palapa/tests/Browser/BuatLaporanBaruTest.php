<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BuatLaporanBaruTest extends DuskTestCase
{
    use DatabaseMigrations; 

    public function testBuatLaporanBaru(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/reports/create')
                ->assertSee('Buat Laporan')
                ->type('title', 'Kebakaran di Hutan Lindung')
                ->type('description', 'Terjadi kebakaran di titik koordinat terpencil dan perlu penanganan segera.')
                ->type('latitude', '-2.548900')
                ->type('longitude', '118.014900')
                ->click('label.fire-level-card.level-critical')
                ->press('Lanjutkan Preview')
                ->waitForLocation('/reports/preview')
                ->assertSee('Preview Laporan')
                ->press('Confirm dan Simpan')
                ->waitForLocation('/profile')
                ->assertSee('Laporan berhasil dikirim');
        });

        $this->assertDatabaseHas('reports', [
            'user_id' => $user->users_id,
            'title' => 'Kebakaran di Hutan Lindung',
            'status' => 'pending',
        ]);
    }
}