<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chats';

    protected $fillable = ['sender_id', 'receiver_id', 'message'];

    public function room() 
    {
        return $this->hasOne('App\Models\ChatRoom', 'id', 'chat_room_id');
    }

    public function user() 
    {
        return $this->hasOne('App\Models\User', 'id', 'sender_id');
    }
}
