<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    /**
     * The attributes are mass assignable
     * 
     * @var array 
    */
    protected $fillable = [
        'name', 'longitude', 'latitude', 'logo'
    ];

    /**
     * Relationship 
    */
    public function districts(){
        return $this->hasMany(District::class);
    }
}
