<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;


class Locations extends Model
{
    use HasFactory;
    protected $table = 'locations';

    protected $fillable = [
        'parent_code', 'code', 'description', 'active_shipping', 'shipping_cost', 'active', 'author'
    ];

    public function province(){
        return $this->belongsTo(Provinces::class, 'parent_code', 'code');
    }

    public static function getLocationsByProvince($province_code)
    {
        $locations = Capsule::select("SELECT id, code, description FROM locations WHERE parent_code = :code ORDER BY description ASC", [':code' => $province_code]);
        return $locations;
    }
}
