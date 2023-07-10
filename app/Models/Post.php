<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['poster_id', 'content'];

    public function files () 
    {
        return $this->hasMany('App\Models\Upload', 'model_id' , 'id');
    }

    public function user () 
    {
        return $this->belongsTo ('App\Models\User', 'id', 'poster_id');
    }
}
