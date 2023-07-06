<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\MessageNotification;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // query user except current user
        $users = User::all()->except(Auth::id());

        return Inertia::render('Chats', [
            'users' => $users
        ]);
    }

    public function sendMessage (Request $request) 
    {
        try{
            Chat::create($request->message);
            broadcast(new MessageNotification($request->message, $request->receiverId))->toOthers();
        } catch (Exception $e) {
           return response()->json(['err' => $e], 500);
        }
    }


    public function getMessages (Request $request) 
    {
        $chatMessages = Chat::where([
            ['receiver_id','=',$request->userId],
            ['sender_id','=',$request->friendId],
        ])->orWhere([
            ['sender_id','=',$request->userId],
            ['receiver_id','=',$request->friendId],
        ])->orderBy('created_at', 'DESC');

        return response()->json(['messages' => $chatMessages->get()], 200); 
    }
}
