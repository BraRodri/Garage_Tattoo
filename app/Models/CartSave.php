<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartSave extends Model
{
    use HasFactory;
    protected $table = 'cart_list_detail';

    protected $fillable = [
        'cart_list_id',
        'products_id',
        'quantity'
    ];

    public function product(){
        return $this->belongsTo(Products::class, 'products_id');
    }

    public function cartlist(){
        return $this->belongsTo(CartList::class, 'cart_list_id');
    }
}
