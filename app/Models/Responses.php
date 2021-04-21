<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responses extends Model
{
    use HasFactory;
    
    protected $table = 'responses';


    protected $fillable = [
        'type', 'title', 'description1', 'description2', 'description3', 'description4', 'active', 'author'
    ];
}
