<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
  //Hidden Fields
  protected $hidden = [];
  //Fillable Fields
  protected $fillable = [
    'dot_command',
    'replace_with',
    'type',
    'is_sup_only',
    'is_loa_item',
    'loa_with',
    'expiration'
  ];
  //Relationships
}
