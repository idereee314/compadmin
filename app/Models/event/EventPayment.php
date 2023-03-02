<?php

namespace event;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;
use Config;

class EventPayment extends Model
{
    protected $table = 'uq_event_payment';
    protected $primaryKey = 'id';

    // protected $fillable = ['registration_id', 'member_id', 'register_number', 'status', 'amount', 'from_type', 'created_by', 'updated_by', 'created_at', 'updated_at'];
    protected $fillable = ['registration_id', 'status', 'amount', 'from_type', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    public function eventRegistration()
    {
        return $this->belongsTo('event\EventRegistration', 'registration_id');
    }

	public static function boot()
    {
        parent::boot();   
        
        static::updating(function($payment)
        {
            $payment->updated_by = Auth::id();
			$payment->updated_at = Carbon\Carbon::now()->toDateTimeString();
        });

        static::creating(function($payment)
        {
            $payment->from_type = 'admin';
            $payment->created_by = Auth::id();
			$payment->created_at = Carbon\Carbon::now()->toDateTimeString();
        });
    }
}
