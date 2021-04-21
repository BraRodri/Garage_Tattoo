<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersCouriers extends Model
{
    use HasFactory;
    protected $table = 'orders_couriers';


    protected $fillable = [
        'orders_id',
        'couriers_id',
        'number',
        'link',
        'message'
    ];

    public function order(){
        return $this->belongsTo(Orders::class, 'orders_id');
    }

    public function courier(){
        return $this->belongsTo(Couriers::class, 'couriers_id');
    }
}
