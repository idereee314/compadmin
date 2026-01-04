<?php

namespace location\object;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class Entrance extends Model
{
    protected $table = 'rti_entrance';
    protected $primaryKey = 'entrance_id';

    public $fillable = ['name', 'entry_type', 'object_location_id', ''];

    public function objectLocation()
    {
        return $this->belongsTo('location\object\ObjectLocation', 'object_location_id');
    }

    public function entranceType()
    {
        return $this->belongsTo('location\unit\SoumDistrict', 'address_soum_district');
    }
    
	public static function boot()
    {
        parent::boot();    

        static::updating(function($entrance)
        {
            $entrance->updated_by = Auth::id();
			$entrance->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($entrance)
        {
            $entrance->created_by = Auth::id();
			$entrance->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
