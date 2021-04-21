<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modals extends Model
{
    use HasFactory;
    
    protected $table = 'modals';

    protected $fillable = [
        'title', 'description', 'image', 'link', 'target', 'start_date', 'end_date', 'position', 'active', 'author'
    ];
}
