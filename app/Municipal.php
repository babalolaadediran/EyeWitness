<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipal extends Model
{
    /**
     * The attributes are mass assignable
     * 
     * @var array 
    */
    protected $fillable = [
        'name', 'longitude', 'latitude', 'logo', 'district_id'
    ];

    /**
     * Relationship 
    */
    public function district(){
        return $this->belongsTo(District::class);
    }

    public function municipal_heads(){
        return $this->hasMany(MunicipalHead::class);
    }

    public function citizens(){
        return $this->hasMany(User::class);
    }

    public function agencies(){
        return $this->hasMany(Agency::class);
    }
}
