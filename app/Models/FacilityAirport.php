<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityAirport extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'fac_id',
    'airport_id',
    'tower_list',
    'list_range',
    'ssa',
    'tdm',
    'is_primary',
    'is_maps_only',
  ];
  //Relationships
  public function facility()
  {
    return $this->belongsTo(Facility::class);
  }
}
