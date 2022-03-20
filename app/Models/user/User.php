<?php

namespace user;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Authenticatable;

    protected $table = 'rti_user';
}
