<?php

namespace member;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use user\CompadUser as User;
use event\Event;
use event\EventRegistration;

use Auth;
use Carbon;
use Illuminate\Support\Str;

class Member extends Model
{
    protected $table = 'uq_member';
    protected $appends = array('fullname','age');
    
    public static function rules($id) 
    {
		return array(
            // 'user_id' => 'required',
            'register_number' => 'required|unique:uq_member,register_number,'.$id.',id',
            'firstname' => 'required',
            'lastname' => 'required',
            'contact_phone' => 'required',
            'birth' => 'required',
            // 'profile_photo' => 'required',
            // 'id_photo' => 'required',
		);
	}
    public function getAgeAttribute()
    {
        $currentYear = date('Y'); // Одоогийн жил
        $birthYear = date('Y', strtotime($this->birth)); // strtotime болон date() функцийг ашиглан төрсөн оныг задалсан
        $age = $currentYear - $birthYear; // Тухайн жилээс төрсөн оныг хасна
        return $age;
    }

    public function getFullnameAttribute()
    {
		return Str::substr($this->lastname,0,1).'.'.$this->firstname;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function eventRegistration()
    {
        return $this->hasMany(EventRegistration::class, 'member_id');
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function($member)
        {
            $member->updated_by = Auth::id();
			$member->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($member)
        {
            $member->created_by = Auth::id();
			$member->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($member)
        {
            //
        });

        static::deleting(function($member)
        {
            if (\Storage::disk('s3')->exists('/member/'.@$member->id)) {  
                \Storage::disk('s3')->deleteDirectory('/member/'.@$member->id);
            }
		});
    }
}
