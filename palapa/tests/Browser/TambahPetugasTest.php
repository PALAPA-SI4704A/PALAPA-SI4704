<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TambahPetugasTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testAdminTambahPetugasManual(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Utama',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'phone' => '081234567899',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit('/admin/users')
                    ->waitForText('Manajemen Pengguna')
                    ->pause(1000)
                    ->click('button[onclick*="addPetugasModal"]')
                    ->waitFor('#addPetugasModal', 5)
                    ->type('#addPetugasModal input[name="users_name"]', 'Petugas Pemadam Baru')
                    ->type('#addPetugasModal input[name="email"]', 'petugas.baru@palapa.com')
                    ->type('#addPetugasModal input[name="phone"]', '081234567890')
                    ->select('#addPetugasModal select[name="pos_name"]', 'Pos Daops Pontianak')
                    ->type('#addPetugasModal input[name="password"]', 'password123')
                    ->click('#addPetugasModal button[type="submit"]')
                    ->waitForText('Data petugas Petugas Pemadam Baru berhasil ditambahkan.', 10)
                    ->assertRouteIs('admin.users.index')
                    ->assertSee('Data petugas Petugas Pemadam Baru berhasil ditambahkan.')
                    ->assertSee('Petugas Pemadam Baru')
                    ->assertSee('petugas.baru@palapa.com')
                    ->assertSee('081234567890')
                    ->assertSee('Pos Daops Pontianak');
        });
    }
}
