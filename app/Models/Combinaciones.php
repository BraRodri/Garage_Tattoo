<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combinaciones extends Model
{
    use HasFactory;
    protected $fillable = [
        'sku', 'stock', 'price', 'products_id', 'attibutes_products'
   ];
   public function producto(){
    return $this->belongsTo(Products::class, 'products_id');
    }
    public function atributeValue(){
    return $this->belongsTo(AttributesProducts::class, 'attibutes_products');
    }
}
