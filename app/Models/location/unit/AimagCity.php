<?php

namespace location\unit;

use Illuminate\Database\Eloquent\Model;

class AimagCity extends Model
{
    protected $table = 'rta_aimag_city';
    protected $primaryKey = 'id';
    
    public function bagKhoroo()
    {
        return $this->hasMany('location\unit\SoumDistrict', 'aimag_city_id');
    }
}
