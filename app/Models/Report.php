<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'news';

    protected $fillable = [
        'title', 'introduction', 'description', 'main_image', 'secondary_image', 'publication_date', 'publication_hour', 'number_visits', 'meta_title', 'meta_description', 'meta_keyword', 'meta_author', 'meta_robots', 'position', 'featured', 'active', 'author'
    ];
}
