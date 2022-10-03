<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'fac_id',
    'fac_type',
    'fac_name',
    'vis_range',
    'mag_var',
    'lat',
    'lon',
    'top_lat',
    'bottom_lat',
    'west_lon',
    'east_lon',
    'init_alt',
    'ca_lat_min',
    'ca_vert_min',
    'ca_floor',
    'prefer_multi'
  ];
  //Relationships
}
