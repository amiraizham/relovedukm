<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['user1_matricnum', 'user2_matricnum'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_matricnum', 'matricnum');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_matricnum', 'matricnum');
    }
}

