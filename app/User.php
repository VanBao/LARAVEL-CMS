<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
   protected $table = "vt_user";

   public $timestamps = false;

}
