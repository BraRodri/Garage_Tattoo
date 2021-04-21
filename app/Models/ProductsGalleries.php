<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsGalleries extends Model
{
    use HasFactory;

    protected $table = 'products_galleries';

    protected $fillable = [
        'products_id', 'image', 'position', 'active', 'author'
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'products_id');
    }
}
