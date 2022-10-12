<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ILSMKR extends Model
{
  //Table Name
  protected $table = 'ils_mkr';
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'ils_id',
    'fac_id',
    'mkr_id',
    'type',
    'status',
    'mkr_lat',
    'mkr_lon',
    'dir_rwy',
    'dist_thr',
    'dist_cln',
    'elev',
    'freq',
    'nav_id',
    'cycle_id',
    'next'
  ];
  //Relationships
}
