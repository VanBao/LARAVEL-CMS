<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'vt_menu';

    public $timestamps = false;
    
    
    public function Data()
    {
        return $this->hasMany('App\Data', "menu", "id");
    }

    public function File()
    {
    	return $this->belongsTo("App\File", "file", "file");
    }
}
