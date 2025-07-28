<?php

namespace organization;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class OrganizationContact extends Model
{
    protected $table = 'rti_organization_contact';
    protected $primaryKey = 'id';

    public static $rules = array(
        'organization_id' => 'required',
        'contact_value' => 'required',
        'contact_type' => 'required',
    );

    public static $rulesUpdate = array(
        'contact_value' => 'required',
    );

    public function contactType()
    {
        return $this->belongsTo('reference\ContactType', 'contact_type_id');
    }

	public static function boot()
    {
        parent::boot();    

        static::updating(function($organizationContact)
        {
            $organizationContact->updated_by = @Auth::user()->user_id;
			$organizationContact->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($organizationContact)
        {
            $organizationContact->created_by = @Auth::user()->user_id;
			$organizationContact->created_at = Carbon\Carbon::now()->toDateTimeString();
            $organizationContact->is_active = TRUE;
        });

    }
}
