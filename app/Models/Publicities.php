<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicities extends Model
{
    use HasFactory;
    protected $table = 'publicities';

    protected $fillable = [
        'location', 'title', 'description', 'image', 'link', 'target', 'position', 'active', 'author'
    ];
}
