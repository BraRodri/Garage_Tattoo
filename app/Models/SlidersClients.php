<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlidersClients extends Model
{
    use HasFactory;
    
    protected $table = 'sliders_clients';

    protected $fillable = [
        'location', 'title', 'description', 'image', 'link', 'target', 'position', 'active', 'author'
    ];
}
