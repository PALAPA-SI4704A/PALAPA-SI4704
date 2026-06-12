<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DeleteConfirmationModalTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testDeleteConfirmationModal(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $petugas = User::factory()->create([
            'users_name' => 'Petugas Target Hapus',
            'role' => 'petugas',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $petugas) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit('/admin/users?role=petugas')
                    ->waitForText('Manajemen Pengguna')
                    ->assertSee($petugas->users_name)
                    ->press('[Hapus]')
                    ->waitForText('Konfirmasi Tindakan')
                    ->assertSee('Apakah Anda yakin ingin menghapus data pengguna ini?')
                    ->click('#confirm-modal-cancel')
                    ->pause(1000)
                    ->assertScript("document.getElementById('confirm-modal-cancel').offsetParent === null")
                    ->assertSee($petugas->users_name);
        });

        $this->assertDatabaseHas('users', [
            'users_id' => $petugas->users_id,
            'users_name' => 'Petugas Target Hapus',
        ]);
    }
}
