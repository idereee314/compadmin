<?php

namespace user;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class CompadUser extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use Authenticatable;

    protected $table = 'uq_compad_user';

    public static function rules($id) 
    {
		return array(
            'username' => 'unique:uq_compad_user,username,'.@$id.',id',
            'email' => 'required|unique:uq_compad_user,email,'.@$id.',id',
            //'password' => 'required|min:8',
            'firstname' => 'required',
            'lastname' => 'required',
            'phone_number' => 'numeric',
		);
	}
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
