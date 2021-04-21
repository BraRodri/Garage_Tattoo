<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsCategories extends Model
{
    use HasFactory;
    protected $table = 'products_categories';

    protected $fillable = [
        'products_id', 'categories_id'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'products_id')->orderBy('position');
    }

    public function category()
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }
}
