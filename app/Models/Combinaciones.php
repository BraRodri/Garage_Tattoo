<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Combinaciones extends Model
{
    use HasFactory;
    protected $fillable = [
        'sku', 'stock', 'price', 'products_id', 'attibutes_products'
    ];
    public function producto()
    {
        return $this->belongsTo(Products::class, 'products_id');
    }
    public function atributeProduct()
    {
        return $this->hasMany(AttributesProducts::class, 'products_id');
    }
}
