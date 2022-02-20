<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    /**
     * The attributes are mass assignable
     * 
     * @var arrray
    */
    protected $fillable = [
        'name', 'longitude', 'latitude', 'logo', 'province_id'
    ];

    /**
     * Relationship 
    */
    public function province(){
        return $this->belongsTo(Province::class);
    } 
    
    public function municipals(){
        return $this->hasMany(Municipal::class);
    }

    public function district_heads(){
        return $this->hasMany(DistrictHead::class);
    }
}
