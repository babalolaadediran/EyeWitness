<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportMedia extends Model
{
    /**
     * The attributes are mass assignable
     *  
     * @var array
    */
    protected $fillable = [
        'report_id', 'media_url', 'media_type'
    ];

    /**
     * Relationship 
    */
    public function report(){
        return $this->belongsTo(Report::class);
    }
}