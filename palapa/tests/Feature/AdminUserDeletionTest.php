<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminUserDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $petugas->users_id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Data pengguna berhasil dihapus.');

        $this->assertDatabaseMissing('users', [
            'users_id' => $petugas->users_id,
        ]);
    }

    public function test_admin_cannot_delete_themselves()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->users_id));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Anda tidak dapat menghapus akun Anda sendiri.');

        $this->assertDatabaseHas('users', [
            'users_id' => $admin->users_id,
        ]);
    }

    public function test_non_admin_cannot_delete_user()
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $userToDelete = User::factory()->create([
            'role' => 'masyarakat',
        ]);

        $response = $this->actingAs($petugas)->delete(route('admin.users.destroy', $userToDelete->users_id));

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'users_id' => $userToDelete->users_id,
        ]);
    }
}
