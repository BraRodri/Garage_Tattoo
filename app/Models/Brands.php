<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    use HasFactory;
    protected $table = 'brands';

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'target',
        'position',
        'active',
        'author'
    ];

    public function products(){
        return $this->hasMany(Products::class);
    }
}
