<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminSelfDeletionTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testAdminSelfDeletionBlocked(): void
    {
        $admin = User::factory()->create([
            'users_name' => 'Admin Utama',
            'email' => 'admin.test@palapa.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'phone' => '081234567899',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->logout()
                    ->loginAs($admin)
                    ->visit('/admin/users')
                    ->waitForText('Manajemen Pengguna')
                    ->pause(1000)
                    ->script(
                        "let token = document.querySelector('input[name=\"_token\"]').value;" .
                        "let form = document.createElement('form');" .
                        "form.action = '/admin/users/{$admin->users_id}';" .
                        "form.method = 'POST';" .
                        "let methodInput = document.createElement('input');" .
                        "methodInput.type = 'hidden';" .
                        "methodInput.name = '_method';" .
                        "methodInput.value = 'DELETE';" .
                        "form.appendChild(methodInput);" .
                        "let tokenInput = document.createElement('input');" .
                        "tokenInput.type = 'hidden';" .
                        "tokenInput.name = '_token';" .
                        "tokenInput.value = token;" .
                        "form.appendChild(tokenInput);" .
                        "document.body.appendChild(form);" .
                        "form.submit();"
                    );

            $browser->waitForText('Anda tidak dapat menghapus akun Anda sendiri.', 10)
                    ->assertRouteIs('admin.users.index')
                    ->assertSee('Anda tidak dapat menghapus akun Anda sendiri.')
                    ->assertSee($admin->users_name);
        });
    }
}
