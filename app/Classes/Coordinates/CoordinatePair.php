<?php

namespace App\Classes\Coordinates;

class CoordinatePair
{
  public $fromPoint;
  public $toPoint;

  /**
   * Creates a CoordinatePair object.
   * @param Coordinate $fromPoint
   * @param Coordinate $toPoint
   * @return void
   */
  public function __construct(Coordinate $fromPoint, Coordinate $toPoint)
  {
    $this->fromPoint = $fromPoint;
    $this->toPoint = $toPoint;
  }
}
