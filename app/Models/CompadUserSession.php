<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CompadUserSession extends Model
{
    protected $table = 'uq_compad_user_sessions';

    public static function rules($id) 
    {
		return array(
            'user_id' => 'required',
            'ip_address' => 'required',
            'user_agent' => 'required',
            'payload' => 'required'
		);
	}
}
