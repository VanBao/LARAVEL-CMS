<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    protected $table = 'vt_data';

    public $timestamps = false;

    public function Menu()
    {
    	return $this->belongsTo("App\Menu", "menu", "id");
    }
    
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
