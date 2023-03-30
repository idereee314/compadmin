<?php

namespace organization;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

use Auth;
use Carbon;

class OrganizationEvent extends Model
{

    protected $table = 'rti_organization_event';
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}