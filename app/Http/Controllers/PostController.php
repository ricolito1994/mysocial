<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\UploadService;

class PostController extends Controller
{
    public $uploadService;

    public function __construct (UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }
  
    public function create(Request $request)
    {
        try {
            $contentType = $request->header('Content-Type');
            $hasfiles = (strpos($contentType, 'multipart/form-data') !== false);
            $postClientObject = !$hasfiles ? $request->post : JSON_DECODE($request->postObject, true); 
        
            $post = Post::create($postClientObject);

            $this->uploadService->upload($request , [
                'model' => 'post',
                'model_id' => $post->id,
            ]);

            if ($hasfiles) {
                $attachments = ($chat->files()->where('model','=','post')->get());
                $post ['files'] = $attachments;
            }
            
            return response()->json(['post'=> $post], 200);

        } catch (Exception $e) {
           return response()->json(['err' => $e], 500);
        }
    }

  
    public function deletePost ($postId) 
    {
        try {
            $post = Post::find($postId);

            Chat::find($postId)->delete();

        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }

    public function updatePost (Request $request) 
    {
        try {

            Post::where('id',$request->postId)->update([
                'content' => $request->content,
            ]);

            $post = Post::find($request->postId);

            return response()->json(['post'=> $post], 200);

        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }

    public function posts (Request $request) 
    {
        try {
            $posts = Post::with(['files' => function ($query) {
                $query->where('model','post');
            }])
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'ASC');

            return response()->json(['posts' => $posts->get()], 200); 
        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }

    public function post ($postId) 
    {
        try {
            $posts = Post::with(['files' => function ($query) {
                $query->where('model','post');
            }])
            ->where('id','=',$postId)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'ASC');

            return response()->json(['posts' => $posts->get()], 200); 
        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }

    public function followerPost ()
    {
        try {
            $userId = Auth::id();
            $user = User::find($userId); // Assuming you have the user ID of the specific user

            $posts = Post::whereIn('poster_id', $user->following()->pluck('users.id'))
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();
            return response()->json(['posts' => $posts->get()], 200); 
        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }
    
}
