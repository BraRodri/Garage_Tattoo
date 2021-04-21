<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $table = 'blog';

    protected $fillable = [
        'slug', 'title', 'description', 'date_public', 'image_main', 'active', 'author'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
