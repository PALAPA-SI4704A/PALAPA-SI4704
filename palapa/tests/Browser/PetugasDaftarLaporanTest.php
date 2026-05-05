<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Report;
use App\Models\Penugasan;

class PetugasDaftarLaporanTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_petugas_dapat_melihat_daftar_laporan_masuk()
    {
        
        
        $warga = User::create([
            'users_name' => 'Warga Pelapor',
            'email' => 'warga@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'warga',
            'phone' => '08111111111'
        ]);

   
        $petugas = User::create([
            'users_name' => 'Petugas Manggala Agni',
            'email' => 'petugas@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'phone' => '08222222222'
        ]);

     
        $report = Report::create([
            'user_id' => $warga->users_id, 
            'admin_id' => 1, 
            'title' => 'Kebakaran Lahan Gambut',
            'description' => 'Api terlihat membesar di area gambut',
            'latitude' => -1.234567,
            'longitude' => 116.831200,
            'status' => 'Diproses',
        ]);

      
        Penugasan::create([
            'petugas_id' => $petugas->users_id,
            'report_id' => $report->report_id,
        ]);

   
        $this->browse(function (Browser $browser) use ($petugas, $report) {
            $browser->loginAs($petugas)
                    
                    ->visit('/petugas/dashboard') 
                    
                    ->pause(1000)
                    
                    ->assertSee('-1.234567')
                    ->assertSee('116.831200')
                    ->assertSee('Diproses')
                    
                    ->assertPresent('table'); 
        });
    }
}