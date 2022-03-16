<?php

namespace user;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon;
use Auth;

class CompadUserSession extends Model
{
    use HasFactory;

    public $timestamps = false;

	protected $table = 'uq_compad_user_sessions';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'last_activity', 'payload'];

    public static function rules($id) 
    {
		return array(
            'user_id' => 'required',
            'ip_address' => 'required',
            'user_agent' => 'required',
            'payload' => 'required'
		);
	}

    public function user()
    {
        return $this->belongsTo('user\User', 'user_id');
    }

	public static function boot()
    {
        parent::boot();
    }
}
