<?php

namespace reference;

use Illuminate\Database\Eloquent\Model;

class OrganizationType extends Model
{
    protected $table = 'rtc_organization_type';
    protected $primaryKey = 'id';
    public $timestamps = false;

	public static function rules($id){
		return array(
            'code' => 'required|unique:rtc_organization_type,code,'.$id,
            "name"  => "required",
		);
	}

    public function organization()
    {
        return $this->hasMany('organization\Organization', 'type_id');
    }
}
