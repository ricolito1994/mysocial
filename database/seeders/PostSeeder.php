<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'poster_id' => 1,
                'content' => '1 This is the content of the first post.',
            ],
            [
                'poster_id' => 2,
                'content' => '2 This is the content of the second post.',
            ],
            [
                'poster_id' => 3,
                'content' => '3 This is the content of the second post.',
            ],
            [
                'poster_id' => 4,
                'content' => '4 This is the content of the second post.',
            ],
            [
                'poster_id' => 5,
                'content' => '5 This is the content of the second post.',
            ],
            // Add more posts as needed
        ];

        // Insert the posts into the database
        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
