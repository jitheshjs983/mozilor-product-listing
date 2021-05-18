<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'products_id';
	public $timestamps = true;

    protected $fillable = [
        'product_name',
        'users_id',
        'price',
        'sku_number',
        'description'
    ];
}
