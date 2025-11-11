<?php

namespace location\unit;

use Illuminate\Database\Eloquent\Model;

class SoumDistrict extends Model
{
    protected $table = 'rta_soum_district';
    protected $primaryKey = 'id';

    public function aimagCity()
    {
        return $this->belongsTo('location\unit\AimagCity', 'aimag_city_id');
    }

    public function bagKhoroo()
    {
        return $this->hasMany('location\unit\BagKhoroo', 'soum_district_id');
    }
}
