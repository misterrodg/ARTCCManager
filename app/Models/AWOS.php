<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AWOS extends Model
{
  //Table Name
  protected $table = 'awos';
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'awos_id',
    'awos_type',
    'is_func',
    'is_assoc',
    'awos_lat',
    'awos_lon',
    'elev',
    'freq',
    'freq2',
    'assoc_fac',
    'cycle_id',
    'next'
  ];
  //Relationships
}
