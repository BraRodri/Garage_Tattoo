<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    use HasFactory;
    protected $table = 'discounts';


    protected $fillable = [
        'title', 'description', 'type', 'code', 'discount', 'discount_percentage', 'start_date', 'end_date', 'restrictions', 'send', 'active', 'author'
    ];
}
