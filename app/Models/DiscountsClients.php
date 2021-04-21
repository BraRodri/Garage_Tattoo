<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountsClients extends Model
{
    use HasFactory;
    
    protected $table = 'discounts_clients';


    protected $fillable = [
        'discounts_id', 'clients_id'
    ];

    public function discount(){
        return $this->belongsTo(Discounts::class, 'discounts_id');
    }

    public function client(){
        return $this->belongsTo(Clients::class, 'clients_id');
    }
}
