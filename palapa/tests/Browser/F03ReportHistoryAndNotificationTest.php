<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Report;
use App\Models\Notifikasi;
use App\Models\StatusHistory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class F03ReportHistoryAndNotificationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.History.001 - Melihat riwayat penanganan laporan (Timeline/Status)
     */
    public function test_TC_History_001_view_timeline(): void
    {
        $user = User::factory()->create([
            'email' => 'warga1@palapa.com',
            'role' => 'masyarakat',
        ]);

        $report = Report::factory()->create([
            'user_id' => $user->users_id,
            'title' => 'Kebakaran Lahan Gambut',
            'status' => 'pending',
        ]);

        $this->browse(function (Browser $browser) use ($user, $report) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->waitForText('Laporan Saya')
                    ->assertSee($report->title)
                    ->script("document.querySelector('a[href*=\"/reports/" . $report->report_id . "/history\"]').click();");

            $browser->waitFor('div[x-show="modalOpen"] .modal-content')
                    ->waitForText('Laporan berhasil dibuat oleh pelapor.')
                    ->assertSee('Riwayat Status')
                    ->assertSee('pending');
        });
    }

    /**
     * TC.History.002 - Menerima notifikasi perubahan status laporan secara real-time
     */
    public function test_TC_History_002_receive_realtime_notification(): void
    {
        $user = User::factory()->create([
            'email' => 'warga2@palapa.com',
            'role' => 'masyarakat',
        ]);

        $notif = Notifikasi::create([
            'user_id' => $user->users_id,
            'pesan' => 'Laporan kebakaran Anda telah diverifikasi Valid',
            'is_read' => 0,
        ]);

        $this->browse(function (Browser $browser) use ($user, $notif) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->waitFor('.profile-bell')
                    ->assertPresent('.notif-badge')
                    ->click('.profile-bell')
                    ->waitForLocation('/notifikasi')
                    ->assertRouteIs('notifikasi.index')
                    ->assertSee($notif->pesan);
        });
    }

    /**
     * TC.History.003 - Menandai notifikasi sebagai telah dibaca
     */
    public function test_TC_History_003_mark_notification_as_read(): void
    {
        $user = User::factory()->create([
            'email' => 'warga3@palapa.com',
            'role' => 'masyarakat',
        ]);

        $notif = Notifikasi::create([
            'user_id' => $user->users_id,
            'pesan' => 'Laporan Anda sedang diproses oleh petugas',
            'is_read' => 0,
        ]);

        $this->browse(function (Browser $browser) use ($user, $notif) {
            $browser->loginAs($user)
                    ->visit('/notifikasi')
                    ->waitForText('Notifikasi Saya')
                    ->assertSee($notif->pesan)
                    ->press('Tandai Dibaca')
                    ->waitForText('Notifikasi ditandai sudah dibaca.')
                    ->assertSee('Sudah dibaca')
                    ->assertDontSee('Tandai Dibaca');
        });
    }

    /**
     * TC.History.004 - Mencoba melihat detail/timeline laporan milik warga lain
     */
    public function test_TC_History_004_prevent_accessing_other_user_report_history(): void
    {
        $userA = User::factory()->create([
            'email' => 'wargaA@palapa.com',
            'role' => 'masyarakat',
        ]);

        $userB = User::factory()->create([
            'email' => 'wargaB@palapa.com',
            'role' => 'masyarakat',
        ]);

        $reportB = Report::factory()->create([
            'user_id' => $userB->users_id,
            'title' => 'Laporan Rahasia B',
            'status' => 'pending',
        ]);

        $this->browse(function (Browser $browser) use ($userA, $reportB) {
            $browser->loginAs($userA)
                    ->visit('/reports/' . $reportB->report_id . '/history');
            $browser->waitForText('AKSES DITOLAK. INI BUKAN LAPORAN ANDA.')
                    ->assertSee('403')
                    ->assertSee('AKSES DITOLAK. INI BUKAN LAPORAN ANDA.');
        });
    }

    /**
     * TC.History.005 - Menandai notifikasi milik pengguna lain sebagai dibaca secara ilegal
     */
    public function test_TC_History_005_prevent_marking_other_user_notification_as_read(): void
    {
        $userA = User::factory()->create([
            'email' => 'wargaA5@palapa.com',
            'role' => 'masyarakat',
        ]);

        $userB = User::factory()->create([
            'email' => 'wargaB5@palapa.com',
            'role' => 'masyarakat',
        ]);

        $notifB = Notifikasi::create([
            'user_id' => $userB->users_id,
            'pesan' => 'Pesan privat warga B',
            'is_read' => 0,
        ]);

        $this->browse(function (Browser $browser) use ($userA, $notifB) {
            $browser->loginAs($userA)
                    ->visit('/profile')
                    ->waitForText('Laporan Saya')
                    ->script("
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/notifikasi/" . $notifB->notifikasi_id . "/read';
                        let csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = document.querySelector('input[name=\"_token\"]').value;
                        form.appendChild(csrf);
                        document.body.appendChild(form);
                        form.submit();
                    ");
            
            $browser->pause(2000); // Wait for page reload/response
            $browser->assertSee('404'); // Controller query throws firstOrFail() causing a 404
        });
    }

    /**
     * TC.History.006 - Menampilkan halaman riwayat ketika database notifikasi kosong
     */
    public function test_TC_History_006_empty_notification_state(): void
    {
        $user = User::factory()->create([
            'email' => 'warganew@palapa.com',
            'role' => 'masyarakat',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/notifikasi')
                    ->waitForText('Notifikasi Saya')
                    ->assertSee('Anda belum memiliki notifikasi.');
        });
    }

    /**
     * TC.History.007 - Validasi kesesuaian kronologis timeline status laporan
     */
    public function test_TC_History_007_chronological_timeline(): void
    {
        $user = User::factory()->create([
            'email' => 'warga7@palapa.com',
            'role' => 'masyarakat',
        ]);

        $report = Report::factory()->create([
            'user_id' => $user->users_id,
            'title' => 'Kebakaran Hutan Rakyat',
            'status' => 'pending',
        ]);

        // Add historical steps
        StatusHistory::create([
            'report_id' => $report->report_id,
            'status_awal' => 'pending',
            'status_baru' => 'valid',
            'catatan' => 'Laporan diverifikasi valid oleh admin.',
            'diubah_oleh' => 'Admin Sistem (Admin)',
            'tanggal_ubah' => now()->addMinutes(1),
        ]);

        StatusHistory::create([
            'report_id' => $report->report_id,
            'status_awal' => 'valid',
            'status_baru' => 'diproses',
            'catatan' => 'Petugas telah dikirim ke lapangan.',
            'diubah_oleh' => 'Petugas Pemadam (Petugas)',
            'tanggal_ubah' => now()->addMinutes(2),
        ]);

        StatusHistory::create([
            'report_id' => $report->report_id,
            'status_awal' => 'diproses',
            'status_baru' => 'selesai',
            'catatan' => 'Kebakaran berhasil dipadamkan total.',
            'diubah_oleh' => 'Petugas Pemadam (Petugas)',
            'tanggal_ubah' => now()->addMinutes(3),
        ]);

        $report->update(['status' => 'selesai']);

        $this->browse(function (Browser $browser) use ($user, $report) {
            $browser->loginAs($user)
                    ->visit('/reports/' . $report->report_id . '/history')
                    ->waitForText('Riwayat Status')
                    ->assertSee('pending')
                    ->assertSee('valid')
                    ->assertSee('diproses')
                    ->assertSee('selesai')
                    ->assertSee('Laporan berhasil dibuat oleh pelapor.')
                    ->assertSee('Laporan diverifikasi valid oleh admin.')
                    ->assertSee('Petugas telah dikirim ke lapangan.')
                    ->assertSee('Kebakaran berhasil dipadamkan total.');
        });
    }

    /**
     * TC.History.008 - Validasi pengurangan otomatis counter notifikasi belum dibaca
     */
    public function test_TC_History_008_unread_notification_counter(): void
    {
        $user = User::factory()->create([
            'email' => 'warga8@palapa.com',
            'role' => 'masyarakat',
        ]);

        // Create 2 notifications
        $notif1 = Notifikasi::create([
            'user_id' => $user->users_id,
            'pesan' => 'Notifikasi 1',
            'is_read' => 0,
        ]);
        $notif2 = Notifikasi::create([
            'user_id' => $user->users_id,
            'pesan' => 'Notifikasi 2',
            'is_read' => 0,
        ]);

        $this->browse(function (Browser $browser) use ($user, $notif1, $notif2) {
            $browser->loginAs($user)
                    ->visit('/profile')
                    ->waitFor('.profile-bell')
                    ->assertPresent('.notif-badge') // Badge "+" exists since we have unread notifs
                    ->click('.profile-bell')
                    ->waitForLocation('/notifikasi')
                    ->press('Tandai Dibaca') // Reads the first notification
                    ->waitForText('Notifikasi ditandai sudah dibaca.')
                    ->visit('/profile')
                    ->waitFor('.profile-bell')
                    ->assertPresent('.notif-badge'); // Badge "+" still exists because of $notif2
            
            // Now mark the second notification as read
            $browser->visit('/notifikasi')
                    ->press('Tandai Dibaca') // Reads the second notification
                    ->waitForText('Notifikasi ditandai sudah dibaca.')
                    ->visit('/profile')
                    ->waitFor('.profile-bell')
                    ->assertMissing('.notif-badge'); // Badge "+" is now gone!
        });
    }

    /**
     * TC.History.009 - Validasi kemunculan foto bukti penanganan di akhir timeline
     */
    public function test_TC_History_009_handling_evidence_in_timeline(): void
    {
        $user = User::factory()->create([
            'email' => 'warga9@palapa.com',
            'role' => 'masyarakat',
        ]);

        // Create finished report with bukti_foto
        $report = Report::factory()->create([
            'user_id' => $user->users_id,
            'title' => 'Laporan Selesai dengan Bukti',
            'status' => 'selesai',
            'bukti_foto' => 'bukti_penanganan/test_bukti.jpg',
            'handling_note' => 'Padam tuntas pukul 21:00.',
        ]);

        StatusHistory::create([
            'report_id' => $report->report_id,
            'status_awal' => 'diproses',
            'status_baru' => 'selesai',
            'catatan' => 'Kebakaran dipadamkan total.',
            'diubah_oleh' => 'Petugas Pemadam (Petugas)',
            'tanggal_ubah' => now(),
        ]);

        $this->browse(function (Browser $browser) use ($user, $report) {
            $browser->loginAs($user)
                    ->visit('/reports/' . $report->report_id . '/history')
                    ->waitForText('Riwayat Status')
                    ->assertSee('selesai')
                    ->assertPresent('.bukti-foto-img');
        });
    }
}
