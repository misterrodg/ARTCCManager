<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable;
  //Hidden Fields
  protected $hidden = [
    'password',
    'remember_token',
  ];
  //Fillable Fields
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'password',
  ];
  //Casts for Fields
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];
  //Relationships
}
