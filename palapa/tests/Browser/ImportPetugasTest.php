<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ImportPetugasTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testAdminImportPetugasMassal(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Utama',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'phone' => '081234567899',
        ]);

        $csvPath = tempnam(sys_get_temp_dir(), 'import_petugas_') . '.csv';
        $csvContent = "Nama,Email,No Telepon,Password,Pos Penempatan\n"
            . "Petugas Satu,petugas1.import@palapa.com,08990000001,password,Pos Daops Pontianak\n"
            . "Petugas Dua,petugas2.import@palapa.com,08990000002,password,Pos Daops Ketapang\n"
            . "Petugas Tiga,petugas3.import@palapa.com,08990000003,password,Pos Induk Sintang\n"
            . "Petugas Empat,petugas4.import@palapa.com,08990000004,password,Pos Melawi\n"
            . "Petugas Lima,petugas5.import@palapa.com,08990000005,password,Pos Daops Palangka Raya\n"
            . "Petugas Enam,petugas6.import@palapa.com,08990000006,password,Pos Daops Pangkalan Bun\n"
            . "Petugas Tujuh,petugas7.import@palapa.com,08990000007,password,Pos Induk Sampit\n"
            . "Petugas Delapan,petugas8.import@palapa.com,08990000008,password,Pos Daops Banjarbaru\n"
            . "Petugas Sembilan,petugas9.import@palapa.com,08990000009,password,Pos Induk Banjarmasin\n"
            . "Petugas Sepuluh,petugas10.import@palapa.com,08990000010,password,Pos Amuntai\n";
        
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
                    ->waitForText('10 data petugas berhasil diimpor.', 10)
                    ->assertRouteIs('admin.users.index')
                    ->assertSee('10 data petugas berhasil diimpor.')
                    ->assertSee('Petugas Satu')
                    ->assertSee('Petugas Lima')
                    ->assertSee('Petugas Sepuluh')
                    ->assertSee('petugas10.import@palapa.com')
                    ->assertSee('08990000010')
                    ->assertSee('Pos Amunta');
        });

        @unlink($csvPath);
    }
}
