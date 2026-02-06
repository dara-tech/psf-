<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    //
    public  $incrementing = false;
    protected $primaryKey ='_URI';  
    protected $table = 'userdata';
    protected $fillable  = ['ACKNOWLEDGE',
    'Q1A',
    'Q2A',
    'Q3A',
    'Q4A',
    'Q5A',
    'Q6A',
    'Q7A',
    'Q8A',
    'Q9A',
    'Q10A',
    'Q1B',
    'Q2B',
    'Q3B',
    'Q5B',
    'Q4B',
    'Q1C',
    'Q2C',
    'Q3C_1',
    'Q3C_2',
    'Q3C_3',
    'Q3C_4',
    'Q3C_5',
    'Q3C_6',
    'Q3C_7',
    'Q3C_8',
    'Q4C',
    'Q5C1',
    'Q5C2',
    'Q5C3',
    'Q6C_1',
    'Q6C_2',
    'Q6C_3',
    'Q6C_4',
    'Q6C_5',
    'Q6C_6',
    'Q6C_7',
    'Q6C_8',
    'Q7C',
    'Q8C',
    'Q9C_1',
    'Q9C_2',
    'Q9C_3',
    'Q9C_4',
    'Q9C_5',
    'Q10C',
    'Q11C',
    'Q12C',
    'Q13C',
    'Q14C',
    'START',
    'SIMSERIAL',
    'USERNAME',
    'META_INSTANCE_ID',
    'DEVICEID',
    '_URI',
    '_IS_COMPLETE',
    '_SUBMISSION_DATE',
    ]; 
    
    // which fields can be filled with User::create()
    protected $dates = ['_submission_date']; // which fields will be Carbon-ized
    public $timestamps = false;

    
    

}
