<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlidersPartners extends Model
{
    use HasFactory;
    protected $table = 'sliders_partners';

    protected $fillable = [
        'location', 'title', 'description', 'image', 'link', 'target', 'position', 'active', 'author'
    ];
}
