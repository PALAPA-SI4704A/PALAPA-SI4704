<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ImportPetugasFormatSalahTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testAdminImportFormatSalah(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Utama',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'phone' => '081234567899',
        ]);

        $csvPath = tempnam(sys_get_temp_dir(), 'import_petugas_salah_') . '.csv';
        $csvContent = "Nama,Email\n"
            . "Petugas Inkomplit,petugas.inkomplit@palapa.com\n";
        
        file_put_contents($csvPath, $csvContent);

        $this->browse(function (Browser $browser) use ($admin, $csvPath) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit('/admin/users')
                    ->waitForText('Manajemen Pengguna')
                    ->pause(1000)
                    ->click('button[onclick*="importModal"]')
                    ->waitFor('#importModal', 5)
                    ->attach('file', $csvPath)
                    ->click('#importModal button[type="submit"]')
                    ->waitForText('Hasil Validasi: Terdapat 1 data yang dilewati/gagal diimpor', 10)
                    ->assertSee('Hasil Validasi: Terdapat 1 data yang dilewati/gagal diimpor')
                    ->assertSee('Baris 2: Format kolom tidak lengkap.')
                    ->assertSee('0 data petugas berhasil diimpor.')
                    ->assertDontSee('Petugas Inkomplit');
        });

        @unlink($csvPath);
    }
}
