<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configurations extends Model
{
    use HasFactory;
    protected $table = 'configurations';


    protected $fillable = [
        'address',
        'city',
        'address_2',
        'city_2',
        'phone1',
        'phone2',
        'phone3',
        'phone4',
        'fax',
        'email',
        'email2',
        'email3',
        'contact_email',
        'sale_email',
        'suscription_email',
        'cotizacion_email',
        'map_1',
        'map_2',
        'map_1_link',
        'map_2_link',
        'horary',
        'transfer_text',
        'webpay_text',
        'minimun_sale',
        'minimum_free_shipping',
        'shipping_text',
        'shipping_text_for_paying',
        'office_shipping_text',
        'discount_minimum',
        'discount_percentage',
        'shipping_active',
        'office_shipping_active',
        'transfer_active',
        'webpay_active',
        'social_facebook',
        'social_instagram',
        'social_linkedin',
        'social_twitter',
        'social_youtube',
        'site_offline',
        'shipping_type',
        'shipit_environment',
        'shipit_email',
        'shipit_token',
        'shipit_tax',
        'webpay_name_company',
        'webpay_code',
        'webpay_environment',
        'webpay_private_key',
        'webpay_public_cert',
        'webpay_tbk_cert',
        'webpay_tax',
        'active_tax',
        'active_cart',
        'active_cotizacion',
        'author'
    ];
}
