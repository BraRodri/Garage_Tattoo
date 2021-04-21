<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersDetails extends Model
{
    use HasFactory;
    protected $table = 'orders_details';


    protected $fillable = [
        'orders_id',
        'code',
        'description',
        'combination',
        'quantity',
        'unit_price',
        'total_price',
        'weight',
        'lenght',
        'width',
        'height',
        'shipping_free'
    ];

    public function order(){
        return $this->belongsTo(Orders::class, 'orders_id');
    }
}
