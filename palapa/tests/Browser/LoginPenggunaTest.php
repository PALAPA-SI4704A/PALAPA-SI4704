<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginPenggunaTest extends DuskTestCase
{
    use DatabaseMigrations;


    public function test_login_sebagai_warga_berhasil_dialihkan_ke_halaman_laporan(): void
    {
        User::create([
            'users_name' => 'Pindwa Warga',
            'email' => 'warga@palapa.com',
            'phone' => '081234567891',
            'role' => 'masyarakat',
            'password' => Hash::make('password123'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/login')
                    ->waitFor('input[name="login"]', 5)
                    ->assertPathIs('/login')
                    
                    ->script([
                        "document.querySelector('input[name=\"login\"]').value = 'warga@palapa.com';",
                        "document.querySelector('input[name=\"password\"]').value = 'password123';"
                    ]);

            $browser->pause(500)
                    ->script("document.querySelector('form').submit();");

            $browser->waitForLocation('/beranda', 10)
                    ->assertPathIs('/beranda');
        });
    }


    public function test_login_sebagai_petugas_berhasil_dialihkan_ke_dashboard_statistik(): void
    {
        User::create([
            'users_name' => 'Petugas Lapangan',
            'email' => 'petugas@palapa.com',
            'phone' => '081234567892',
            'role' => 'petugas',
            'password' => Hash::make('password123'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/login')
                    ->waitFor('input[name="login"]', 5)
                    
                    ->script([
                        "document.querySelector('input[name=\"login\"]').value = 'petugas@palapa.com';",
                        "document.querySelector('input[name=\"password\"]').value = 'password123';"
                    ]);

            $browser->pause(500)
                    ->script("document.querySelector('form').submit();");

            $browser->waitForLocation('/petugas/dashboard', 10)
                    ->assertPathIs('/petugas/dashboard');
        });
    }


    public function test_login_sebagai_admin_berhasil_dialihkan_ke_dashboard_statistik(): void
    {
        User::create([
            'users_name' => 'Admin Utama',
            'email' => 'admin@palapa.com',
            'phone' => '081234567893',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/login')
                    ->waitFor('input[name="login"]', 5)
                    
                    ->script([
                        "document.querySelector('input[name=\"login\"]').value = 'admin@palapa.com';",
                        "document.querySelector('input[name=\"password\"]').value = 'password123';"
                    ]);

            $browser->pause(500)
                    ->script("document.querySelector('form').submit();");

            $browser->waitForLocation('/admin/dashboard', 10)
                    ->assertPathIs('/admin/dashboard');
        });
    }
}