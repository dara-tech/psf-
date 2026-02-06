<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    //
    public  $incrementing = false;
    protected $primaryKey  ='code';  
    protected $table = 'tokens';
    protected $fillable = ['code']; // which fields can be filled with User::create()
  
}
