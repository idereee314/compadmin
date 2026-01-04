<?php

namespace user;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class UserAdmin extends Model
{
    use Authenticatable;

    protected $table = 'sd_user';
}
