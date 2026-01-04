<?php

namespace organization;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class OrganizationWorktime extends Model
{
    protected $table = 'rti_organization_worktime';
    protected $primaryKey = 'id';

    protected $fillable = ['organization_id', 'work_day', 'start_time', 'end_time'];

    public static $rules = array(
        'organization_id' => 'required',
        'work_day' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',
    );

    public static $rulesUpdate = array(
        'start_time' => 'required',
        'end_time' => 'required',
    );

    public static function boot()
    {
        parent::boot();
    
        // cause a delete of a product to cascade to children so they are also deleted
        static::creating(function($worktime)
        {
            $worktime->is_active = TRUE;
            $worktime->created_at = Carbon\Carbon::now()->toDateTimeString();
            $worktime->created_by = Auth::user()->id;
        });

        static::created(function($worktime)
        {
        });

        static::updating(function($worktime)
        {
            $worktime->updated_by = Auth::user()->user_id;
            $worktime->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::deleting(function($worktime)
        {
        });
    }
}
