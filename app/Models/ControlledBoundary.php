<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ControlledBoundary extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'cont_id',
    'mult_code',
    'seq_no',
    'cont_type',
    'via',
    'cont_lat',
    'cont_lon',
    'arc_lat',
    'arc_lon',
    'arc_dist',
    'arc_bear',
    'min_alt',
    'min_alt_unit',
    'max_alt',
    'max_alt_unit',
    'cont_name',
    'region',
    'cycle_id',
    'next'
  ];
  //Relationships
}
