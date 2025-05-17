<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'reviewer_matricnum',
        'seller_matricnum',
        'rating',
        'comment',
    ];

    // Relationship to product
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    // Reviewer (buyer)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_matricnum', 'matricnum');
    }

    // Seller
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_matricnum', 'matricnum');
    }
}

