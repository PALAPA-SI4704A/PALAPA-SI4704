<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditProfilPenggunaTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testAdminEditProfilPengguna(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Utama',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'phone' => '081234567899',
        ]);

        $petugas = User::factory()->create([
            'users_name' => 'Petugas Lama',
            'email' => 'petugas.lama@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'phone' => '081234567800',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit('/admin/users')
                    ->waitForText('Manajemen Pengguna')
                    ->pause(1000)
                    ->click('table tbody tr:first-child a.btn-link')
                    ->waitForText('Edit Data Pengguna')
                    ->type('users_name', 'Petugas Terupdate')
                    ->type('email', 'petugas.baru@palapa.com')
                    ->click('.btn-submit')
                    ->waitForText('Data pengguna Petugas Terupdate berhasil diperbarui.', 10)
                    ->assertRouteIs('admin.users.index')
                    ->assertSee('Data pengguna Petugas Terupdate berhasil diperbarui.')
                    ->assertSee('Petugas Terupdate')
                    ->assertSee('petugas.baru@palapa.com')
                    ->assertDontSee('Petugas Lama')
                    ->assertDontSee('petugas.lama@palapa.com');
        });
    }
}
