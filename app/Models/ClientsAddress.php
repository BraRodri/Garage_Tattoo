<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientsAddress extends Model
{
    use HasFactory;
    protected $table = 'clients_address';

    protected $fillable = [
        'clients_id', 'address_default', 'address', 'address_number', 'office_number', 'regions_id', 'provinces_id', 'locations_id', 'active', 'author'
    ];

    public function client(){
        return $this->belongsTo(Clients::class, 'clients_id');
    }

    public function region(){
        return $this->belongsTo(Regions::class, 'regions_id');
    }

    public function province(){
        return $this->belongsTo(Provinces::class, 'provinces_id');
    }

    public function location(){
        return $this->belongsTo(Locations::class, 'locations_id');
    }
}
