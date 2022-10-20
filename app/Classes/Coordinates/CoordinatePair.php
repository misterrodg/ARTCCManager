<?php

namespace App\Classes\Coordinates;

class CoordinatePair
{
  public $fromPoint;
  public $toPoint;

  public function __construct(Coordinate $fromPoint, Coordinate $toPoint)
  {
    $this->fromPoint = $fromPoint;
    $this->toPoint = $toPoint;
  }
}
