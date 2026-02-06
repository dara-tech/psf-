<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewTable extends Model
{
    //
     //
     protected $primarykey ='id';  
     protected $table = 'viewtables';
     protected $fillable = ['id', 'name']; // which fields can be filled with User::create()
     protected $dates = ['created_at']; // which fields will be Carbon-izeds

}
