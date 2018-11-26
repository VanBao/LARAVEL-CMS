<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'vt_menu';

    public $timestamps = false;
    
    
    public function data()
    {
        return $this->hasMany('App\Data');
    }
}
