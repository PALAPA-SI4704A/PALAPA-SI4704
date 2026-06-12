<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TambahPetugasEmailDuplikatTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testAdminTambahPetugasEmailDuplikat(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Utama',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'phone' => '081234567899',
        ]);

        User::factory()->create([
            'users_name' => 'Petugas Eksis',
            'email' => 'petugas.duplikat@palapa.com',
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
                    ->click('button[onclick*="addPetugasModal"]')
                    ->waitFor('#addPetugasModal', 5)
                    ->type('#addPetugasModal input[name="users_name"]', 'Petugas Duplikat Baru')
                    ->type('#addPetugasModal input[name="email"]', 'petugas.duplikat@palapa.com')
                    ->type('#addPetugasModal input[name="phone"]', '081234567801')
                    ->select('#addPetugasModal select[name="pos_name"]', 'Pos Daops Pontianak')
                    ->type('#addPetugasModal input[name="password"]', 'password123')
                    ->click('#addPetugasModal button[type="submit"]')
                    ->waitForText('Email sudah terdaftar.', 10)
                    ->assertRouteIs('admin.users.index')
                    ->assertSee('Email sudah terdaftar.')
                    ->assertDontSee('Petugas Duplikat Baru');
        });
    }
}
