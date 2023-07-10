<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class PostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_create_posts(): void
    {
        $postData = [
            'poster_id' => 1,
            'content' => 'This is a test post.',
        ];
    
        $response = $this->post('/createPost', $postData);
    
        $response->assertStatus(201);
    
        $this->assertDatabaseHas('posts', $postData);
    }

    public function test_that_create_posts_with_file(): void
    {
        Storage::fake('public'); // Use the "public" disk for testing image uploads

        $file = UploadedFile::fake()->image('test-image.jpg');
    
        $response = $this->post('/createPost', [
            'image' => $file,
            'postObject' => "{'poster_id':'1','content':'this is a test content'}",
        ]);
    
        $response->assertStatus(200);
    
        Storage::disk('public')->assertExists('uploads/' . $file->hashName());
    }

    public function test_that_update_posts(): void
    {
        $post = Post::factory()->create();

        $response = $this->post('/createPost', [
            'id' => $post->id,
            'content' => 'sample content',
            'poster_id' => 1,
        ]);

        $response->assertStatus(200);

        // Assert that the user record was updated in the database
        $this->assertDatabaseHas('post', [
            'id' => $post->id,
            'content' => 'sample content',
            'poster_id' => 1,
        ]);
    }

    public function test_that_get_post(): void
    {
        Post::factory()->count(5)->create();

        $response = $this->get('/posts');
    
        $response->assertStatus(200);
    
        $response->assertJsonCount(5, 'data');
    }

    public function test_that_delete_posts(): void
    {
        $post = Post::factory()->create();

        $response = $this->delete('/deletePost/'.$post->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
