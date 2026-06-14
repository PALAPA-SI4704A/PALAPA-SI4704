<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ValidasiAksesFotoTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.Complete.008 - Validasi link akses foto bukti penanganan petugas
     */
    public function test_TC_Complete_008_validasi_link_akses_foto_bukti_penanganan(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Utama',
            'email'      => 'admin8@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'admin',
        ]);

        $petugas = User::factory()->create([
            'users_name' => 'Petugas Delapan',
            'email'      => 'petugas8@palapa.com',
            'password'   => bcrypt('password123'),
            'role'       => 'petugas',
        ]);

        $report = Report::factory()->create([
            'status' => 'selesai',
            'title'  => 'Kebakaran Lahan Gambut',
            'assigned_petugas_id' => $petugas->users_id,
        ]);

        Storage::disk('public')->put('photos/bukti_penanganan/test.png', 'fake image content');

        Penugasan::create([
            'report_id'   => $report->report_id,
            'petugas_id'  => $petugas->users_id,
            'assigned_at' => now()->subHours(2),
            'completed_at'=> now(),
            'bukti_photo' => 'bukti_penanganan/test.png',
        ]);

        try {
            $this->browse(function (Browser $browser) use ($admin, $report) {
                $browser->logout()
                        ->loginAs($admin)
                        ->visit(route('admin.reports.show', $report->report_id))
                        ->waitForText('Lihat Bukti Foto')
                        ->assertSee('Lihat Bukti Foto');

                $linkHref = $browser->attribute('a[href*="test.png"]', 'href');
                
                $browser->visit($linkHref)
                        ->assertSourceHas('fake image content')
                        ->screenshot('tc-complete-008-success');
            });
        } finally {
            Storage::disk('public')->delete('photos/bukti_penanganan/test.png');
        }
    }
}
