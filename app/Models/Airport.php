<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'fac_id',
    'icao_id',
    'faa_id',
    'name',
    'apt_lat',
    'apt_lon',
    'mag_var',
    'elev',
    'faa_region',
    'artcc_id',
    'type',
    'ownership',
    'use_id',
    'towered',
    'fuel',
    'emergency',
    'status',
    'cycle_id',
    'next'
  ];
  //Relationships
  public function runways()
  {
    return $this->hasMany(Runway::class);
  }
}
