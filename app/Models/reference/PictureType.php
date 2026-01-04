<?php

namespace reference;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class PictureType extends Model
{
    protected $table = 'rtc_picture_type';
    protected $primaryKey = 'id';
    public $timestamps = false;

	public static function rules($id){
		return array(
            'code' => 'required|unique:rtc_picture_type,code'.$id,
            "height"  => "required",
            "width"  => "required",
            "object_type"  => "required",
            "dir_url"  => "required",
		);
	}

	public static function boot()
    {
        parent::boot();    

        static::updating(function($pictureType)
        {
            $pictureType->updated_by = Auth::id();
			$pictureType->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($pictureType)
        {
            $pictureType->created_by = Auth::id();
			$pictureType->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
