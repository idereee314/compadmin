<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use reference\EventEntries;

use Auth;
use Carbon;

class BeltGroup extends Model
{
    protected $table = 'uq_bjj_belt_group';
    protected $primaryKey = 'id';
    
    public function configBelts()
    {
        return $this->hasMany('reference\EntryConfigBelt', 'possible_belts');
    }
}
