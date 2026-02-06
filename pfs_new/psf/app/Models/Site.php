<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    //
    protected $primaryKey ='id';  
    protected $table = 'tbl_sites';
    protected $fillable = ['id','username', 'sitecode', 'province','sitename','site']; // which fields can be filled with User::create()
    protected $dates = ['created_at']; // which fields will be Carbon-ized
    

    
    

}
