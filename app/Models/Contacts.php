<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;

    
    protected $table = 'contacts';


    protected $fillable = [
        'type', 'name', 'email', 'phone', 'mobile', 'city', 'message', 'offices_id', 'active', 'author'
    ];

    public function office(){
        return $this->belongsTo(Office::class, 'offices_id');
    }
}
