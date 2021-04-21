<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faqs extends Model
{
    use HasFactory;
    protected $table = 'faqs';

    protected $fillable = [
        'title', 'description', 'position', 'active', 'author'
    ];
}
