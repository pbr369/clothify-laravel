<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'products',
        'payment_intent_id',
        'subtotal',
        'total',
        'shipping',
        'payment_status',
    ];

    protected $casts = [
        'product_id' => 'array',
    ];

}
