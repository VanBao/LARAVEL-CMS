<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'vt_file';

    public $timestamps = false;

    public function Menu()
    {
    	$this->hasMany("App\Menu", "file", "file");
    }
    
}
