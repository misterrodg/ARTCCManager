<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestrictiveBoundary extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'rest_id',
    'mult_code',
    'seq_no',
    'rest_type',
    'via',
    'rest_lat',
    'rest_lon',
    'arc_lat',
    'arc_lon',
    'arc_dist',
    'arc_bear',
    'min_alt',
    'min_alt_unit',
    'max_alt',
    'max_alt_unit',
    'rest_name',
    'agency',
    'region',
    'cycle_id',
    'next'
  ];
  //Relationships
}
