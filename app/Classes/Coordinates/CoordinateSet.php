<?php

namespace App\Classes\Coordinates;

class CoordinateSet
{
  public $coordinateSet;

  /**
   * @return void
   */
  public function __construct()
  {
    $this->coordinateSet = array();
  }

  /**
   * Adds a coordinate to the set.
   * @param Coordinate $coordinate
   * @return void
   */
  public function addCoordinate(Coordinate $coordinate)
  {
    if (!is_null($coordinate->lat) && !is_null($coordinate->lon)) {
      $this->coordinateSet[] = $coordinate;
    }
  }

  /**
   * Adds a coordinate to the set, ignoring any duplicates.
   * @param Coordinate $coordinate
   * @return void
   */
  public function addCoordinateUnique(Coordinate $coordinate)
  {
    if (!in_array($coordinate, $this->coordinateSet) && !is_null($coordinate->lat) && !is_null($coordinate->lon)) {
      $this->coordinateSet[] = $coordinate;
    }
  }

  /**
   * Casts the coordinate set as a FROM - TO array.
   * @param bool $skipSegments Default FALSE. Set TRUE if you wish to print every $mod line.
   * @param bool $offset Default FALSE. Set TRUE if the first line should be skipped ( i.e. TRUE: |- - - |, FALSE: | - - -| ).
   * @param int $mod Default 2. Can be modified to have $skipSegments skip every $mod-th line.
   * @return array CoordinatePairs
   */
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

  /**
   * Casts the coordinate set as a polygon.
   * @return array CoordinatePairs
   */
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
