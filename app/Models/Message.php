<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'sender_matricnum', 'message'];


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_matricnum', 'matricnum');
    }
    

}
