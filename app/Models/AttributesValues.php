<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributesValues extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'atrribute_id', 'active', 'author'
    ];
    public function attribute(){
        return $this->belongsTo(Attribute::class, 'atrribute_id');
    }
}
