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
        try {

            $chat = Chat::create($request->message);

            $messageObject = [
                'chatId' => $chat->id,
                'chat' => $chat,
            ];

            broadcast(new MessageNotification($messageObject, $request->receiverId, $request->senderId))->toOthers();

            return response()->json(['chat'=> $chat], 200);

        } catch (Exception $e) {
           return response()->json(['err' => $e], 500);
        }
    }

    public function deleteMessage ($chatId) 
    {
        try {
            $chat = Chat::find($chatId);

            $messageObject = [
                'method' => 'deleteChat',
                'chatId' => $chatId,
            ];

            broadcast(new MessageNotification($messageObject, $chat->receiver_id, $chat->sender_id))->toOthers();

            Chat::find($chatId)->delete();

        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }

    public function updateMessage (Request $request) 
    {
        try {

            Chat::where('id',$request->chatId)->update([
                'message' => $request->message,
            ]);

            $chat = Chat::find($request->chatId);

            $messageObject = [
                'method' => 'updateChat',
                'chatId' => $request->chatId,
                'message' => $request->message,
            ];

            broadcast(new MessageNotification($messageObject, $chat->receiver_id, $chat->sender_id))->toOthers();

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
        ])
        ->whereNull('deleted_at')
        ->orderBy('created_at', 'ASC');

        return response()->json(['messages' => $chatMessages->get()], 200); 
    }
}
