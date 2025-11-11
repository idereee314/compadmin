<?php

namespace reference;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class Category extends Model
{
    protected $table = 'rti_category';
    protected $primaryKey = 'id';

	public static function rules($id){
		return array(
            'name' => 'required',
            'bg_color' => 'required',
            'code' => 'required|unique:rti_category,code,'.$id,
            "show_order"  =>'required',
            "cover_image64"  => 'dimensions:min_width=320,min_height=210',
		);
	}

    public function parentCategory()
    {
        return $this->belongsTo('reference\Category', 'parent_id');
    }

    public function child()
	{
		return $this->hasMany('reference\Category', 'parent_id')
        ->withCount('organizations')
        ->withCount('services')
        ->orderBy('id', 'asc');
	}

    public function children()
    {
        return $this->child()->with('children')
        ->withCount('organizations')
        ->withCount('services')
        ->orderBy('id', 'asc')->selectRaw("*, '' as cover_image, '' as action");
    }

    public function organizations()
    {
        return $this->belongsToMany('listing\organization\Organization', 'rti_organization_category', 'category_id', 'organization_id');
    }

    public function services()
    {
        return $this->belongsToMany('listing\reference\Service', 'rt_listing.rtm_category_services', 'category_id', 'service_id')->orderBy('service_id', 'asc');
    }

	public static function boot()
    {
        parent::boot();    

        static::updating(function($category)
        {
            $category->updated_by = Auth::id();
			$category->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($category)
        {
            $category->created_by = Auth::id();
			$category->created_at = Carbon\Carbon::now()->toDateTimeString();
			$category->is_active = TRUE;
        });

        static::deleting(function($category)
        {
            $category->services()->detach();
		});

        static::saved(function($category)
        {
            //$category->child->category_type = $category->category_type;
            $category->child()->update(['category_type' => $category->category_type]);
        });
    }
}
