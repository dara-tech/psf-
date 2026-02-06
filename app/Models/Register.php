<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    //
    protected $primarykey ='id';  
    protected $table = 'register';
    protected $fillable = ['id', 'name']; // which fields can be filled with User::create()
    protected $dates = ['created_at']; // which fields will be Carbon-ized
    

    
    

}
