<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsRelations extends Model
{
    use HasFactory;
    
    protected $table = 'products_relations';


    protected $fillable = [
        'products_id', 'relation_id'
    ];

    public function product(){
        return $this->belongsTo(Products::class, 'products_id');
    }
}
