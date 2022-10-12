<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fix extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'fix_id',
    'region',
    'fix_lat',
    'fix_lon',
    'prev_name',
    'use_type',
    'nas_id',
    'artcc_hi',
    'artcc_lo',
    'is_pitch',
    'is_catch',
    'is_suaatcaa',
    'cycle_id',
    'next',
  ];
  //Relationships
}
