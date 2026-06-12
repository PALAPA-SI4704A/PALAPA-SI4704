<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ImportPetugasEkstensiSalahTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testAdminImportEkstensiSalah(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Utama',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'phone' => '081234567899',
        ]);

        $invalidFilePath = 'E:/KULIAH/KULIAH SEMESTER 6/PPL/PALAPA/PALAPA-SI4704/wrong.png';

        $this->browse(function (Browser $browser) use ($admin, $invalidFilePath) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit('/admin/users')
                    ->waitForText('Manajemen Pengguna')
                    ->pause(1000)
                    ->click('button[onclick*="importModal"]')
                    ->waitFor('#importModal', 5)
                    ->attach('file', $invalidFilePath)
                    ->click('#importModal button[type="submit"]')
                    ->waitForText('csv', 10)
                    ->assertRouteIs('admin.users.index')
                    ->assertSee('csv')
                    ->assertSee('txt');
        });
    }
}
