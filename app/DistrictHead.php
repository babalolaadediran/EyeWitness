<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DistrictHead extends Model
{
    /**
     * The attributes are mass assignable 
     * 
     * @var array
    */
    protected $fillable = [
        'fullname', 'gender', 'dob', 'picture', 'email', 'phone', 'address', 'password', 'district_id'
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
    public function district(){
        return $this->belongsTo(District::class);
    }
}
