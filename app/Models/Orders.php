<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    
    protected $table = 'orders';

    protected $fillable = [
        'offices_id',
        'clients_id',
        'business_name',
        'rut',
        'commercial_business',
        'email',
        'phone',
        'address',
        'address_number',
        'office_number',
        'region_name',
        'province_name',
        'location_name',
        'type_document',
        'document_business_name',
        'document_rut',
        'document_commercial_business',
        'document_phone',
        'document_address',
        'document_address_number',
        'document_office_number',
        'document_region_name',
        'document_province_name',
        'document_location_name',
        'shipping_type',
        'payment_type',
        'shipping_status',
        'payment_status',
        'order_comment',
        'shipping_comment',
        'discount_code',
        'subtotal',
        'discount',
        'shipping',
        'extra',
        'tax',
        'total',
        'width',
        'height',
        'length',
        'weight',
        'shipit_courier_name',
        'shipit_commune_id',
        'shipit_id',
    ];

    public function office(){
        return $this->belongsTo(Office::class, 'offices_id');
    }

    public function client(){
        return $this->belongsTo(Clients::class, 'clients_id');
    }

    public function details(){
        return $this->hasMany(OrdersDetails::class);
    }
}
