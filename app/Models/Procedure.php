<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'proc_type',
    'proc_section',
    'airport_id',
    'proc_id',
    'trans_id',
    'seq_no',
    'fix_id',
    'wp_desc',
    'turn_dir',
    'path_term',
    'arc_dist',
    'alt_desc',
    'alt1',
    'alt2',
    'speed',
    'speed_desc',
    'center_fix',
    'region',
    'cycle_id',
    'next'
  ];
  //Relationships
}
