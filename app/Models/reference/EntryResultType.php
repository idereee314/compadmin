<?php

namespace reference;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use reference\EventEntries;

use Auth;
use Carbon;

class EntryResultType extends Model
{
    protected $table = 'uq_result_type';
    protected $primaryKey = 'id';
    
}
