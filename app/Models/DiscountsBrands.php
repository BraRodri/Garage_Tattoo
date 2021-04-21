<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountsBrands extends Model
{
    use HasFactory;
    protected $table = 'discounts_brands';

    protected $fillable = [
        'discounts_id', 'brands_id'
    ];

    public function discount(){
        return $this->belongsTo(Discounts::class, 'discounts_id');
    }

    public function brand(){
        return $this->belongsTo(Brands::class, 'brands_id');
    }
}
