<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminReportDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_report()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $report = Report::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.reports.destroy', $report->report_id));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success', 'Laporan berhasil dihapus.');

        $this->assertDatabaseMissing('reports', [
            'report_id' => $report->report_id,
        ]);
    }

    public function test_non_admin_cannot_delete_report()
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $report = Report::factory()->create();

        $response = $this->actingAs($petugas)->delete(route('admin.reports.destroy', $report->report_id));

        $response->assertStatus(403);

        $this->assertDatabaseHas('reports', [
            'report_id' => $report->report_id,
        ]);
    }
}
