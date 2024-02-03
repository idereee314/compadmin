<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use reference\EntryConfigWeight;
use reference\EventEntries;
use Auth;
use Carbon;

class EntryConfigAge extends Model
{
    protected $table = 'uq_entry_config_age';
    protected $primaryKey = 'id';

    protected $appends = array('name');

    public static function rules($id)
    {
        return [
            'entry_id' => 'required',
            'start_age' => [
                'required',
                'numeric',
                Rule::unique('uq_entry_config_age')->where(function ($query) use ($id) {
                    return $query->where('entry_id', request()->input('entry_id'))
                        ->where('id', '!=', $id);
                }),
            ],
            'end_age' => 'nullable|numeric',
        ];
    }

    public function getNameAttribute()
    {
        if($this->start_age == null)
        {
            $age = '-'.$this->end_age;
        }
        elseif($this->end_age == null)
        {
            $age = $this->start_age.'+';
        }
        else
        {
            $age = $this->start_age.'-'.$this->end_age;
        }
        return $age;
    }

    public function weights()
    {
        return $this->hasMany(EntryConfigWeight::class, 'entry_age_id');
    }

    public function entry()
    {
        return $this->belongsTo(EventEntries::class, 'entry_id');
    }
    
    public static function boot()
    {
        parent::boot();

        static::updating(function($entryConAge)
        {
            $entryConAge->updated_by = Auth::id();
			$entryConAge->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($entryConAge)
        {
            $entryConAge->created_by = Auth::id();
			$entryConAge->created_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::created(function($entryConAge)
        {
            //
        });

        static::deleting(function($entryConAge)
        {
		});
    }
}
