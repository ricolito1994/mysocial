<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Follower;
class FollowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'user_id' => 1,
                'follower_id' => 2,
            ],
            [
                'user_id' => 1,
                'follower_id' => 3,
            ],
            [
                'user_id' => 1,
                'follower_id' => 4,
            ],


            [
                'user_id' => 2,
                'follower_id' => 1,
            ],
            [
                'user_id' => 2,
                'follower_id' => 3,
            ],
            [
                'user_id' => 2,
                'follower_id' => 4,
            ],

            [
                'user_id' => 3,
                'follower_id' => 1,
            ],
            [
                'user_id' => 3,
                'follower_id' => 2,
            ],

            [
                'user_id' => 4,
                'follower_id' => 5,
            ],

            [
                'user_id' => 5,
                'follower_id' => 1,
            ],
        ];

        // Insert the posts into the database
        foreach ($posts as $post) {
            Follower::create($post);
        }
    }
}
