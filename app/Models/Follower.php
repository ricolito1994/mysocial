<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follower extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'follower_id'];

    public function user () 
    {
        return $this->belongsTo('App\Models\User','id','user_id');
    }

    public function followerDetails () 
    {
        return $this->belongsTo('App\Models\User','id','follower_id');
    }
}
