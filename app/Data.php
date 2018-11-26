<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $table = 'vt_data';

    public $timestamps = false;

    public function Category()
    {
    	return $this->belongsTo("App\Menu", "menu", "id");
    }
}
