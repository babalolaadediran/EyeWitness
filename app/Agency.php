<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    /**
     * These attributes are mass assignable 
    */
    protected $fillable = [
        'municipal_id', 'agency_name', 'description', 'email', 'phone', 'location', 'password'
    ];

    /**
     * This attribute is hidden from the array
    */
    protected $hidden = [
        'password'
    ];

    /**
     * Relationship 
    */
    public function municipal(){
        return $this->belongsTo(Municipal::class);
    }
}
