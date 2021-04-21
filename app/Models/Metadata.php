<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;
    
    protected $table = 'metadata';

    protected $fillable = [
        'friendly_url',
        'title',
        'authors',
        'subject',
        'description',
        'keyword',
        'language',
        'indexing',
        'robots',
        'googlebots',
        'distribution',
        'googlecode',
        'analyticcode',
        'pixelcode',
        'author'
    ];
}
