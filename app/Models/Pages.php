<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;
    protected $table = 'pages';

    protected $fillable = [
        'title', 'introduction', 'description', 'image', 'meta_title', 'meta_description', 'meta_keyword', 'meta_author', 'meta_robots', 'active', 'author'
    ];
}
