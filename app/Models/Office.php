<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'title', 'description', 'address', 'city', 'phone', 'fax', 'email', 'horary', 'map', 'image', 'position', 'active', 'author', 'code_webpay'
    ];
}
