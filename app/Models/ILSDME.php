<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ILSDME extends Model
{
  //Table Name
  protected $table = 'ils_dme';
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'ils_id',
    'fac_id',
    'status',
    'dme_lat',
    'dme_lon',
    'dir_rwy',
    'dist_thr',
    'dist_cln',
    'dist_rwy_opp',
    'elev',
    'channel',
    'cycle_id',
    'next'
  ];
  //Relationships
}
