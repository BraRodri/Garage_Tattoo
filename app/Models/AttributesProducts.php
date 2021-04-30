<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributesProducts extends Model
{
    use HasFactory;
    protected $fillable = [
        'products_id', 'attribute_values_id'
    ];
    public function attributeValues()
    {
        return $this->belongsTo(AttributesValues::class, 'attribute_values_id');
    }
}
