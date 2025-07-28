<?php

namespace reference;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class Features extends Model
{
    protected $table = 'rti_features';
    protected $primaryKey = 'id';

    public static $rules = array(
        'name' => 'required',
        "image"  => "required",
    );

    public static $updateRules = array(
        'name' => 'required',
    );

	public static function boot()
    {
        parent::boot();    

        static::updating(function($features)
        {
            $features->updated_by = Auth::id();
			$features->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($features)
        {
            $features->created_by = Auth::id();
			$features->created_at = Carbon\Carbon::now()->toDateTimeString();
			$features->is_active = TRUE;
        });
    }
}
