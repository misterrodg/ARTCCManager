<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferredRoute extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'orig',
    'dest',
    'type',
    'seq_no',
    'area',
    'alt',
    'acft',
    'hours1',
    'hours2',
    'hours3',
    'dir',
    'nar_type',
    'route',
    'cycle_id',
    'next'
  ];
  //Relationships
}
