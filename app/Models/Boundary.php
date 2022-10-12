<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boundary extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'bound_id',
    'alt_struct',
    'bound_seq',
    'bound_lat',
    'bound_lon',
    'bound_des',
    'is_desc',
    'artcc_name',
    'cycle_id',
    'next'
  ];
  //Relationships
}
