<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navaid extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'nav_id',
    'nav_type',
    'name',
    'nav_class',
    'nav_lat',
    'nav_lon',
    'tac_lat',
    'tac_lon',
    'artcc_hi',
    'artcc_lo',
    'elev',
    'mag_var',
    'freq',
    'bear',
    'vor_vol',
    'dme_vol',
    'is_lo_in_hi',
    'nav_status',
    'is_pitch',
    'is_catch',
    'is_suaatcaa',
    'faa_region',
    'cycle_id',
    'next'
  ];
  //Relationships
}
