<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderData extends Model
{
    //
    public  $incrementing = false;
    protected $primaryKey ='_URI';  
    protected $table = 'providerdata';
    protected $fillable  = ['acknowledge',
    'dept',
    'other',
    'e1',
    'e2',
    'e3',
    'e4',
    'e5',
    'e6',
    'START',
    'SIMSERIAL',
    'USERNAME',
    'META_INSTANCE_ID',
    'DEVICEID',
    '_URI',
    '_IS_COMPLETE',
    '_SUBMISSION_DATE',
    '_MODEL_VERSION'
    
    
    ]; 
    
    // which fields can be filled with User::create()
    protected $dates = ['_submission_date']; // which fields will be Carbon-ized
    public $timestamps = false;

    
    

}
