<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;
    /**
     * @test
     *
     * User Can Added in Database
     *
     */

    public function user_can_be_added()
    {
        factory(User::class)->create(['name' => 'hanna']);
        $user = User::where('name', '=', 'hanna')->first();
        $this->assertEquals('hanna', $user['name']);
    }

    /**
     * @test
     *
     * user Must exist one in database
     */
    public function user_must_exist_one_in_database()
    {

        factory(User::class)->create();
        $user =  User::all()->count();
        $this->assertDatabaseCount('users', 1);
    }
}
