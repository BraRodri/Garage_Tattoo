<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesProducts extends Model
{
    use HasFactory;
    protected $table = 'categories_products';

    protected $fillable = [
        'categories_id', 'products_id'
    ];

    public function category(){
        return $this->belongsTo(Categories::class, 'categories_id');
    }
}
