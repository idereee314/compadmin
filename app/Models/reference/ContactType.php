<?php

namespace reference;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class ContactType extends Model
{
    protected $table = 'rtc_contact_type';
    protected $primaryKey = 'id';
    public $timestamps = false;

	public static function rules($id){
		return array(
            'code' => 'required|unique:rtc_contact_type,code,'.$id,
            'description' => 'required',
            "image"  => "required",
		);
	}

	public static function boot()
    {
        parent::boot();    

        static::updating(function($contactType)
        {
            $contactType->updated_by = Auth::id();
			$contactType->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($contactType)
        {
            $contactType->created_by = Auth::id();
			$contactType->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
