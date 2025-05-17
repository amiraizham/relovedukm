<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'buyer_matricnum',
        'seller_matricnum', // Add seller matricnum
        'status',
    ];

    // Relationship to product
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    // Relationship to buyer (user via matricnum)
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_matricnum', 'matricnum');
    }

    // Relationship to seller (user via matricnum)
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_matricnum', 'matricnum');
    }

    public function review()
{
    return $this->hasOne(Review::class, 'product_id', 'product_id');
}
}

