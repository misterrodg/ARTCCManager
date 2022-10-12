<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Runway extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'fac_id',
    'rwy_id',
    'length',
    'width',
    'sfc',
    'sfc_cond',
    'base_id',
    'base_lat',
    'base_lon',
    'base_true',
    'base_elev',
    'base_tch',
    'base_gpa',
    'base_dthdist',
    'base_tdze',
    'base_vgsi',
    'base_proc',
    'recip_id',
    'recip_lat',
    'recip_lon',
    'recip_true',
    'recip_elev',
    'recip_tch',
    'recip_gpa',
    'recip_dthdist',
    'recip_tdze',
    'recip_vgsi',
    'recip_proc',
    'cycle_id',
    'next'
  ];
  //Relationships
  public function airport()
  {
    return $this->belongsTo(Airport::class);
  }
}
