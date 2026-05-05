<?php

namespace Tests\Browser;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class CreateNegativeTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testNegativeCreate(): void
    {
        $user = User::factory()->create();
        $this->browse(function ($browser) use ($user): void {
            $browser->loginAs($user)
                ->visit('/reports/create')
                ->type('title', 'Kebakaran Hutan Palangkaraya') 
                ->type('latitude', '-2.392127') 
                ->type('longitude', '112.313648')
                ->press('Lanjutkan Preview')
                ->assertPathIs('/reports/create')
                ->assertAttribute('textarea[name="description"]', 'required', 'true');
        });
    }
}
