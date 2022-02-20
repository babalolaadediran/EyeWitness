<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    /**
     * This attributes are mass assignable
     * 
     * @var array
    */
    protected $fillable = [
        'fullname', 'gender', 'dob', 'picture', 'email', 'phone', 'address', 'password'
    ];


    /**
     * Th attribute will be hidden from array 
    */
    protected $hidden = [
        'password'
    ];
}
