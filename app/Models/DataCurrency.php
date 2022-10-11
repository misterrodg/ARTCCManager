<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataCurrency extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'data_id',
    'edition',
    'cycle_id',
    'edition_date',
  ];
  //Relationships
}
