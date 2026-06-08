<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_user_with_valid_phone()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'petugas',
            'phone' => '081234567890',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->users_id), [
            'users_name' => 'Petugas Terupdate',
            'email' => 'updated@example.com',
            'phone' => '08987654321',
            'role' => 'petugas',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'users_id' => $user->users_id,
            'phone' => '08987654321',
        ]);
    }

    public function test_admin_cannot_update_user_with_invalid_phone()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'petugas',
            'phone' => '081234567890',
        ]);

        // Phone with letters
        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->users_id), [
            'users_name' => 'Petugas Terupdate',
            'email' => 'updated@example.com',
            'phone' => '08987654321a',
            'role' => 'petugas',
        ]);

        $response->assertSessionHasErrors(['phone']);

        // Phone with symbols
        $response = $this->actingAs($admin)->put(route('admin.users.update', $user->users_id), [
            'users_name' => 'Petugas Terupdate',
            'email' => 'updated@example.com',
            'phone' => '0898-7654-321',
            'role' => 'petugas',
        ]);

        $response->assertSessionHasErrors(['phone']);
    }
}
