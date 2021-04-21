<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountsProducts extends Model
{
    use HasFactory;
    
    protected $table = 'discounts_products';

    protected $fillable = [
        'discounts_id', 'products_id'
    ];

    public function discount(){
        return $this->belongsTo(Discounts::class, 'discounts_id');
    }

    public function product(){
        return $this->belongsTo(Products::class, 'products_id');
    }
}
