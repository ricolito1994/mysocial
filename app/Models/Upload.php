<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $table = 'uploads';

    protected $fillable = ['path', 'model', 'model_id'];

    public function chat() 
    {
        return $this->belongsTo ('App\Models\Chat', 'id', 'model_id');
    }

    public function post() 
    {
        return $this->belongsTo ('App\Models\Post', 'id', 'model_id');
    }
}
