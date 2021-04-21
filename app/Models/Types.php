<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    use HasFactory;
    protected $table = 'types';

    protected $fillable = [
        'title', 'position', 'active', 'author'
    ];

    public function products(){
        return $this->hasMany(Products::class);
    }
}
