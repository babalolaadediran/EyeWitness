<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MunicipalHead extends Model
{
     /**
     * The attributes are mass assignable 
     * 
     * @var array
    */
    protected $fillable = [
        'fullname', 'gender', 'dob', 'picture', 'email', 'phone', 'address', 'password', 'municipal_id'
    ];

    /**
     * The attribute that should be hidden for arrays 
    */
    protected $hidden = [
        'password'  
    ];

    /**
     * Relationships 
    */
    public function municipal(){
        return $this->belongsTo(Municipal::class);
    }
}
