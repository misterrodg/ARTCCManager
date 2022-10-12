<?php

namespace App\Classes\NASR;

class CodedRoute
{
  public $routeCode;
  public $orig;
  public $dest;
  public $depFix;
  public $routeString;

  public function __construct()
  {
    $this->routeCode = null;
    $this->orig = null;
    $this->dest = null;
    $this->depFix = null;
    $this->routeString = null;
  }

  public function fromString(string $line)
  {
    $routeArray = explode(',', $line);
    $this->routeCode = $routeArray[0];
    $this->orig = $routeArray[1];
    $this->dest = $routeArray[2];
    $this->depFix = $routeArray[3];
    $this->routeString = $routeArray[4];
  }

  public function fromModel(object $dbObject)
  {
    $this->routeCode = $dbObject->route_code;
    $this->orig = $dbObject->orig;
    $this->dest = $dbObject->dest;
    $this->depFix = $dbObject->dep_fix;
    $this->routeString = $dbObject->route;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'route_code' => $this->routeCode,
      'orig'       => $this->orig,
      'dest'       => $this->dest,
      'dep_fix'    => $this->depFix,
      'route'      => $this->routeString,
      'cycle_id'   => $airacId,
      'next'       => $next
    );
    return $result;
  }
}
