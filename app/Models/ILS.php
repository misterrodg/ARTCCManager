<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ILS extends Model
{
  //Table Name
  protected $table = 'ils';
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'cycle_id',
    'next'
  ];
  //Relationships
}
