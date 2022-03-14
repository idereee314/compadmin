<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CompadEventConfig extends Model
{
    protected $table = 'uq_event_config';

    public static function rules($id) 
    {
		return array(
            'event_id' => 'required',
            'reg_start_date' => 'required',
            'reg_end_date' => 'required'
		);
	}
}
