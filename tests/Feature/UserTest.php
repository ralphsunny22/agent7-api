<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    // public function test_get_active_user_by_id()
    // {
    //     $user_id = User::where('status', 'pending')->get()->random()->id;
    //     $response = $this->get('/api/single-user/' . $user_id)
    //         ->assertStatus(200)
    //         ->assertJsonStructure(
    //             [
    //                 // 'code'=>'200',
    //                 // 'message'=>'active user',
    //                 'data' => [
    //                     'id',
    //                     'name',
    //                     'email',
    //                     'lastname',
    //                     'middlename',
    //                     'isSuperAdmin',
    //                     'role',
    //                     'profile_picture',
    //                     'notification_preferences',
    //                     'subscription_plan_id',
    //                     'completion_rate',
    //                     'status',
    //                     'workspace' => [
    //                         '*' => [
    //                             "id",
    //                             "name",
    //                             "logo",
    //                             "timezone",
    //                             "status",
    //                             "create_at",
    //                             "update_at"
    //                         ],
    //                     ]
    //                 ],
    //             ]
    //         );
    // }
    // use RefreshDatabase;
    public function test_all_users()
    {
        //action
        $response = $this->getJson(route('allUser'));
        
        //response
        $this->assertEquals(2,count($response->json()));
    }


}
