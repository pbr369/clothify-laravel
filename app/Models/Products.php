<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'product_name',
        'price',
        'stock_quantity',
        'description',
        'category',
        'rate',
        'reviews_num',
        'sold',
        'image_url_1',
        'image_url_2',
        'image_url_3',
        'image_url_4',
        'image_url_5',
    ];

    protected $table = 'products';

    protected $primaryKey = 'id';

}
