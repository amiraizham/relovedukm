<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'matricnum',
        'product_id',
    ];

    public function product()
{
    return $this->belongsTo(Products::class, 'product_id');
}
}

