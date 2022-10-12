<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirwayATS extends Model
{
  //Table Name
  protected $table = 'airway_ats';
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'airway_id',
    'point_id',
    'seq_no',
    'route_end',
    'min_alt',
    'min_alt_rev',
    'max_alt',
    'artcc_id',
    'cycle_id',
    'next'
  ];
  //Relationships
}
