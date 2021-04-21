<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class Provinces extends Model
{
    use HasFactory;
    protected $table = 'provinces';

    protected $fillable = [
        'parent_code', 'code', 'description', 'active', 'author'
    ];

    public function region(){
        return $this->belongsTo(Regions::class, 'parent_code', 'code');
    }

    public function locations(){
        return $this->hasMany(Locations::class, 'code', 'parent_code');
    }

    public static function getProvincesByRegion($region_code)
    {
        $provinces = Capsule::select("SELECT id, code, description FROM provinces WHERE parent_code = :code ORDER BY description ASC", [':code' => $region_code]);
        return $provinces;
    }
}
