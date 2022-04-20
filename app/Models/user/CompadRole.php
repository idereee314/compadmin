<?php

namespace user;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use user\CompadRoleMenu;
use user\CompadUser;

use Auth;
use Carbon;

class CompadRole extends Model
{
    protected $table = 'uq_compad_role';
    
    public static function rules($id) 
    {
		return array(
            'name' => 'required',
            'code' => 'required|unique:uq_compad_role,code,'.@$id.',id',
		);
	}

    public function users()
    {
        return $this->belongsToMany(CompadUser::class, 'uq_compad_user_role', 'role_id', 'user_id');
    }

    public function menus()
    {
        return $this->hasMany(CompadRoleMenu::class, 'role_id');
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function($role)
        {
            $role->updated_by = Auth::id();
			$role->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($role)
        {
            $role->created_by = Auth::id();
			$role->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($role)
        {
            //
        });

        static::deleting(function($role)
        {
            foreach ($role->menus as $menu){
                $menu->delete();
            }
		});
    }
}
