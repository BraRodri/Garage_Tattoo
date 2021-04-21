<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsArchives extends Model
{
    use HasFactory;
    protected $table = 'products_archives';

    protected $fillable = [
        'products_id',
        'archive',
        'expiration_date',
        'recipient_email',
        'active',
        'author'
    ];

    public function product(){
        return $this->belongsTo(Products::class, 'products_id');
    }
}
