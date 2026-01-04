<?php

namespace location\reference;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class ObjectType extends Model
{
    protected $table = 'rtc_object_type';
    protected $primaryKey = 'id';

	public static function boot()
    {
        parent::boot();    

        static::updating(function($objectType)
        {
            $objectType->updated_by = Auth::id();
			$objectType->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($objectType)
        {
            $objectType->created_by = Auth::id();
			$objectType->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
    
}
