<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The attributes are mass assignable
     * 
     * @var array 
    */
    protected $fillable = [
        'incident', 'status', 'user_id', 'longitude', 'latitude', 'total_views',
    ];

    /**
     * Relationship  
    */
    public function report_media(){
        return $this->hasMany(ReportMedia::class);
    }
}
