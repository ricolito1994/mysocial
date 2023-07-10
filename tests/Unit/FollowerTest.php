<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class FollowerTest extends TestCase
{
    use RefreshDatabase;

    public function test_followers(): void
    {
        // Create a user record in the database
        $user = User::factory()->create();

        // Create three follower records for the user
        $followers = User::factory()->count(3)->create();

        // Attach the followers to the user
        $user->followers()->attach($followers);

        $response = $this->get('/followers/'.$user->id);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }

    public function test_follow(): void
    { 
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->get('/followUser/'.$user1->id);

        $response->assertStatus(200);

        $this->assertDatabaseHas('followers', [
            'user_id' => $user1->id,
            'follower_id' => $user2->id,
        ]);
    }

    public function test_unfollow(): void
    {
       // Create two user records in the database
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Establish a follow relationship
        $user1->following()->attach($user2);

        $response = $this->delete('/unfollowUser/'.$user1->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('followers', [
            'user_id' => $user1->id,
            'follower_id' => $user2->id,
        ]);
    }

    public function test_suggested_follow(): void
    {
        // Create a user record in the database
        $user = User::factory()->create();

        // Create three potential followers
        $potentialFollowers = User::factory()->count(3)->create();

        $response = $this->get('/suggestedFollowers');

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');
    }
}