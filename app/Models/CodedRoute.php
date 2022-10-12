<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodedRoute extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'route_code',
    'orig',
    'dest',
    'dep_fix',
    'route',
    'cycle_id',
    'next'
  ];
  //Relationships
}
