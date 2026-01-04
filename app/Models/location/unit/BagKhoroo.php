<?php

namespace location\unit;

use Illuminate\Database\Eloquent\Model;

class BagKhoroo extends Model
{
    protected $table = 'rta_bag_khoroo';
    protected $primaryKey = 'id';

    public function soumDistrict()
    {
        return $this->belongsTo('location\unit\SoumDistrict', 'soum_district_id');
    }
}
