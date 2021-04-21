<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionesDetalle extends Model
{
    use HasFactory;
    protected $table = 'cotizaciones_detalle';


    protected $fillable = [
        'cotizaciones_id',
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
        'shipping_free',
        'talla',
        'color'
    ];

    public function cotizacion(){
        return $this->belongsTo(Cotizaciones::class, 'cotizaciones_id');
    }
}
