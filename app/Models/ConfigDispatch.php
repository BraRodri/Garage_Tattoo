<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigDispatch extends Model
{
    use HasFactory;
    protected $table = 'config_dispatch';

    protected $fillable = [
        'description', 'price', 'active', 'author'
    ];
}
