<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = ['title', 'description', 'price', 'category', 'image', 'matricnum', 'is_approved'];


    public function user()
{
    return $this->belongsTo(User::class, 'matricnum', 'matricnum');

}

}