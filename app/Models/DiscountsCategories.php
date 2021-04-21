<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountsCategories extends Model
{
    use HasFactory;
    
    protected $table = 'discounts_categories';

    protected $fillable = [
        'discounts_id', 'categories_id'
    ];

    public function discount(){
        return $this->belongsTo(Discounts::class, 'discounts_id');
    }

    public function category(){
        return $this->belongsTo(Categories::class, 'categories_id');
    }
}
