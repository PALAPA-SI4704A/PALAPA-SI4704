<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ImportPetugasValidasiJumlahTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testAdminImportValidasiJumlah(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Utama',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'phone' => '081234567899',
        ]);

        $importedUsers = [];
        for ($i = 1; $i <= 15; $i++) {
            $importedUsers[] = [
                'users_name' => "Petugas Ke-$i",
                'email' => "petugas$i.jumlah@palapa.com",
                'phone' => "089910000" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'password' => 'password',
                'pos_name' => 'Pos Daops Pontianak',
            ];
        }

        $csvPath = tempnam(sys_get_temp_dir(), 'import_petugas_jumlah_') . '.csv';
        $csvContent = "Nama,Email,No Telepon,Password,Pos Penempatan\n";
        foreach ($importedUsers as $u) {
            $csvContent .= "{$u['users_name']},{$u['email']},{$u['phone']},{$u['password']},{$u['pos_name']}\n";
        }
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
                    ->waitForText('15 data petugas berhasil diimpor.', 15);
        });

        $this->assertEquals(15, User::where('role', 'petugas')->count());

        foreach ($importedUsers as $u) {
            $dbUser = User::where('email', $u['email'])->first();
            $this->assertNotNull($dbUser);
            $this->assertEquals($u['users_name'], $dbUser->users_name);
            $this->assertEquals($u['phone'], $dbUser->phone);
            $this->assertEquals($u['pos_name'], $dbUser->pos_name);
        }

        @unlink($csvPath);
    }
}
