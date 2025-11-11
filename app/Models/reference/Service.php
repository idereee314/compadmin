<?php

namespace listing\reference;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class Service extends Model
{
    protected $table = 'rti_service';
    protected $primaryKey = 'id';

	public static function rules($id){
		return array(
            'name' => 'required|unique:rti_service,name,'.$id,
		);
	}

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while(!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    public function parent()
	{
		return $this->belongsTo('listing\reference\Service', 'parent_id');
	}

    public function child()
	{
		return $this->hasMany('listing\reference\Service', 'parent_id')->orderBy('id', 'asc');
	}

    public function children()
    {
        return $this->child()->with('children')->orderBy('id', 'asc');
    }

    public function categories()
    {
        return $this->belongsToMany('listing\reference\Category', 'rtm_category_services', 'service_id', 'category_id');
    }

	public static function boot()
    {
        parent::boot();    

        static::updating(function($service)
        {
            $service->updated_by = Auth::id();
			$service->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($service)
        {
            $service->created_by = Auth::id();
			$service->created_at = Carbon\Carbon::now()->toDateTimeString();
			$service->is_active = TRUE;
        });
    }
}
