<?php

namespace App\Classes\Coordinates;

class CoordinateSet
{
  public $coordinateSet;

  public function __construct()
  {
    $this->coordinateSet = array();
  }

  public function addCoordinate(Coordinate $coordinate)
  {
    if (!is_null($coordinate->lat) && !is_null($coordinate->lon)) {
      $this->coordinateSet[] = $coordinate;
    }
  }

  public function addCoordinateUnique(Coordinate $coordinate)
  {
    if (!in_array($coordinate, $this->coordinateSet) && !is_null($coordinate->lat) && !is_null($coordinate->lon)) {
      $this->coordinateSet[] = $coordinate;
    }
  }

  public function castFromTo(bool $skipSegments = FALSE, bool $offset = FALSE, int $mod = 2)
  {
    $result = array();
    $fromArray = $this->coordinateSet;
    $toArray = $this->coordinateSet;
    array_pop($fromArray);
    array_shift($toArray);
    $limit = count($fromArray);
    $segmentOffset = ($offset) ? 0 : 1;
    for ($i = 0; $i < $limit; $i++) {
      if ($fromArray[$i]->lat != 0.0 && $toArray[$i]->lat != 0.0) {
        if (!$skipSegments || ($skipSegments && (fmod($segmentOffset, $mod) != 0))) {
          array_push($result, new CoordinatePair($fromArray[$i], $toArray[$i]));
        }
      }
      $segmentOffset++;
    }
    return $result;
  }

  public function castPoly()
  {
    $result = array();
    $fromArray = $this->coordinateSet;
    $toArray = $this->coordinateSet;
    $first = array_shift($toArray);
    array_push($toArray, $first);
    $limit = count($fromArray);
    for ($i = 0; $i < $limit; $i++) {
      if ($fromArray[$i]->lat != 0.0 && $toArray[$i]->lat != 0.0) {
        array_push($result, new CoordinatePair($fromArray[$i], $toArray[$i]));
      }
    }
    return $result;
  }
}
