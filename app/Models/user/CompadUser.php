<?php

namespace user;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class CompadUser extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'uq_compad_user';

    public static function rules($id) 
    {
		return array(
            'username' => 'unique:iq_user,username,'.@$id.',id',
            'email' => 'required|unique:iq_user,email,'.@$id.',id',
            'password' => 'required|min:8',
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
