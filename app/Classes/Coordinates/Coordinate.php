<?php

namespace App\Classes\Coordinates;

class Coordinate
{
  const EARTH_RADIUS_NM = 3443.92;

  public $lat;
  public $lon;

  /**
   * @param float $lat Default 0.0.
   * @param float $lon Default 0.0.
   * @return void
   */
  public function __construct(?float $lat = 0.0, ?float $lon = 0.0)
  {
    $this->lat = $lat;
    $this->lon = $lon;
  }

  /**
   * Modifies $this->lat and $this->lon based on a place, bearing, and distance from another point.
   * @param float $lat Latitude of the origin point.
   * @param float $lon Longitude of the origin point.
   * @param float $bearing Bearing from the origin point.
   * @param float $distance Distance from the origin point.
   * @return void
   */
  public function fromPBD(float $lat, float $lon, float $bearing, float $distance)
  {
    $endLat = asin(sin(deg2rad($lat)) * cos($distance / self::EARTH_RADIUS_NM) + cos(deg2rad($lat)) * sin($distance / self::EARTH_RADIUS_NM) * cos(deg2rad($bearing)));
    $endLon = deg2rad($lon) + atan2(sin(deg2rad($bearing)) * sin($distance / self::EARTH_RADIUS_NM) * cos(deg2rad($lat)), cos($distance / self::EARTH_RADIUS_NM) - sin(deg2rad($lat)) * sin($endLat));

    $this->lat = rad2deg($endLat);
    $this->lon = rad2deg($endLon);
  }

  /**
   * Modifies $this->lat and $this->lon based on a bearing, and distance from itself. Useful for plotting ad-hoc arcs.
   * @param float $bearing Bearing from this point to new point.
   * @param float $distance Distance from this point to new point.
   * @return Coordinate
   */
  public function nextViaBD(float $bearing, float $distance)
  {
    $endLat = asin(sin(deg2rad($this->lat)) * cos($distance / self::EARTH_RADIUS_NM) + cos(deg2rad($this->lat)) * sin($distance / self::EARTH_RADIUS_NM) * cos(deg2rad($bearing)));
    $endLon = deg2rad($this->lon) + atan2(sin(deg2rad($bearing)) * sin($distance / self::EARTH_RADIUS_NM) * cos(deg2rad($this->lat)), cos($distance / self::EARTH_RADIUS_NM) - sin(deg2rad($this->lat)) * sin($endLat));

    $this->lat = rad2deg($endLat);
    $this->lon = rad2deg($endLon);

    return $this;
  }

  /**
   * Modifies $this->lat and $this->lon using Degrees, Minutes, and Seconds.
   * @param string $nS Default 'N'. North or South identifier.
   * @param string $latD Degrees latitude.
   * @param string $latM Minutes latitude.
   * @param string $latS Seconds latitude.
   * @param string $eW Default 'W'. East or West identifier.
   * @param string $lonD Degrees longitude.
   * @param string $lonM Minutes longitude.
   * @param string $lonS Seconds longitude.
   * @return void
   */
  public function fromDms(string $nS = 'N', string $latD, string $latM, string $latS, string $eW = 'W', string $lonD, string $lonM, string $lonS)
  {
    $lat  = $latD + $this->changeLevel($latM, -1) + $this->changeLevel($latS, -2);
    $lon  = $lonD + $this->changeLevel($lonM, -1) + $this->changeLevel($lonS, -2);
    $this->lat = ($nS == 'S') ? -$lat : $lat;
    $this->lon = ($eW == 'W') ? -$lon : $lon;
  }

  /**
   * Calculates the distance between this Coordinate and the provided lat/lon.
   * @param float $endLat Latitude of the end point.
   * @param float $endLon Longitude of the end point.
   * @return float
   */
  public function haversineGreatCircleDistance(float $endLat, float $endLon)
  {
    $theta = $this->lon - $endLon;
    $arc = rad2deg(acos((sin(deg2rad($this->lat)) * sin(deg2rad($endLat))) + (cos(deg2rad($this->lat)) * cos(deg2rad($endLat)) * cos(deg2rad($theta)))));
    $distance = $arc * 60; //Convert degrees to min (nautical miles are min of arc)
    return $distance;
  }

  /**
   * Calculates the bearing between this Coordinate and the provided lat/lon.
   * @param float $endLat Latitude of the end point.
   * @param float $endLon Longitude of the end point.
   * @return float
   */
  public function haversineGreatCircleBearing(float $endLat, float $endLon)
  {
    $x = cos(deg2rad($this->lat)) * sin(deg2rad($endLat)) - sin(deg2rad($this->lat)) * cos(deg2rad($endLat)) * cos(deg2rad($endLon - $this->lon));
    $y = sin(deg2rad($endLon - $this->lon)) * cos(deg2rad($endLat));
    $bearing = rad2deg(atan2($y, $x));
    $bearing = fmod($bearing + 360, 360);
    return $bearing;
  }

  /**
   * Creates a formatted string in VRC format for this point.
   * @return string
   */
  public function toVRC()
  {
    $nS = ($this->lat >= 0) ? 'N' : 'S';
    $lat  = abs($this->lat);
    $latD = floor($lat);
    $latM = floor(($lat - $latD) * 60);
    $latS = ((($lat - $latD) * 60) - $latM) * 60;
    $eW = ($this->lon >= 0) ? 'E' : 'W';
    $lon = abs($this->lon);
    $lonD = floor($lon);
    $lonM = floor(($lon - $lonD) * 60);
    $lonS = ((($lon - $lonD) * 60) - $lonM) * 60;
    $result =
      $nS . str_pad($latD, 3, '0', STR_PAD_LEFT) . '.' . str_pad($latM, 2, '0', STR_PAD_LEFT) . '.' . str_pad(number_format($latS, 3, '.', ''), 6, '0', STR_PAD_LEFT) . ' ' .
      $eW . str_pad($lonD, 3, '0', STR_PAD_LEFT) . '.' . str_pad($lonM, 2, '0', STR_PAD_LEFT) . '.' . str_pad(number_format($lonS, 3, '.', ''), 6, '0', STR_PAD_LEFT);
    return $result;
  }

  /**
   * Calculates degrees minutes or seconds from the input value.
   * @param float $inputValue The degree, minute, or second value.
   * @param int $levels Default 1. The number of levels to step up or down, where positive is down (Deg -> Min or Min -> Sec) and negative is up (Sec -> Min or Min -> Deg).
   * @return float
   */
  private function changeLevel(float $inputValue, int $levels = 1)
  {
    $result = $inputValue;
    if ($levels == 0) {
      return $result;
    } elseif ($levels < 0) {
      $result = $inputValue / (60 / $levels);
    } else {
      $result = $inputValue * (60 * $levels);
    }
    return $result;
  }
}
