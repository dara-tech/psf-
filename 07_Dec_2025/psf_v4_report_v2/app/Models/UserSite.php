<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSite extends Model
{
    //
    protected $primarykey =['user_id','site_id'];  
    protected $table = 'user_belong2_sites';
    protected $fillable = ['user_id', 'site_id']; // which fields can be filled with User::create()
    protected $dates = ['created_at']; // which fields will be Carbon-ized
    

    
    

}
