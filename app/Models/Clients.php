<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Clients extends Authenticatable
{
    use Notifiable;

    protected $guard = 'client';
    protected $table = 'clients';

    protected $fillable = [
         'rut', 'business_name', 'address', 'regions_id', 'provinces_id', 'locations_id', 'phone', 'email', 'password',
    ];

    protected $hidden = [
        'password','remember_token',
    ];

    public function region(){
        return $this->belongsTo(Regions::class, 'regions_id');
    }

    public function province(){
        return $this->belongsTo(Provinces::class, 'provinces_id');
    }

    public function location(){
        return $this->belongsTo(Locations::class, 'locations_id');
    }

    public function address(){
        return $this->belongsTo(ClientsAddress::class)->orderBy('id')->first();
    }

    public function addresses(){
        return $this->hasMany(ClientsAddress::class);
    }
}
