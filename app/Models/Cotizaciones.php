<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizaciones extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';

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
        'order_status',
        'order_comment',
        'subtotal',
        'discount',
        'extra',
        'extra',
        'tax',
        'total'
    ];

    public function office(){
        return $this->belongsTo(Office::class, 'offices_id');
    }

    public function client(){
        return $this->belongsTo(Clients::class, 'clients_id');
    }

    public function details(){
        return $this->hasMany(CotizacionesDetalle::class);
    }
}
