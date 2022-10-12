<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ILSGS extends Model
{
  //Table Name
  protected $table = 'ils_gs';
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'ils_id',
    'fac_id',
    'status',
    'gs_lat',
    'gs_lon',
    'dir_rwy',
    'dist_thr',
    'dist_cln',
    'elev',
    'angle',
    'freq',
    'has_dme',
    'cycle_id',
    'next'
  ];
  //Relationships
}
