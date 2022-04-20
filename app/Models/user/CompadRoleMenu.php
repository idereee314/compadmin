<?php

namespace user;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon;

class CompadRoleMenu extends Model
{
    protected $table = 'uq_compad_role_menu';
    protected $fillable = ['role_id', 'menu', 'operation'];

    public static function rules($id) 
    {
        return array(
                'role_id' => 'required',
                'menu' => 'required',
                'operation' => 'required'
        );
      }
}
