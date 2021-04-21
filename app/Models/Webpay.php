<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webpay extends Model
{
    use HasFactory;
    protected $table = 'webpay';


    protected $fillable = [
        'tbk_transaccion',
        'tbk_orden_compra',
        'tbk_id_session',
        'tbk_fecha_contable',
        'tbk_fecha_transaccion',
        'tbk_hora_transaccion',
        'tbk_numero_final_tarjeta',
        'tbk_fecha_expiracion_tarjeta',
        'tbk_codigo_autorizacion',
        'tbk_codigo_tipo_pago',
        'tbk_codigo_respuesta',
        'tbk_descripcion_respuesta',
        'tbk_monto',
        'tbk_valor_cuota',
        'tbk_numero_cuotas',
        'tbk_codigo_comercio',
        'tbk_orden_compra_comercio'
    ];

    public function products(){
        return $this->hasMany(Products::class);
    }
}
