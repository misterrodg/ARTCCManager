<?php

namespace App\Classes\Coordinates;

class Coordinate
{
  const EARTH_RADIUS_NM = 3443.92;

  public $lat;
  public $lon;

  public function __construct(?float $lat = 0.0, ?float $lon = 0.0)
  {
    $this->lat = $lat;
    $this->lon = $lon;
  }

  public function fromPBD(float $lat, float $lon, float $bearing, float $distance)
  {
    $endLat = asin(sin(deg2rad($lat)) * cos($distance / self::EARTH_RADIUS_NM) + cos(deg2rad($lat)) * sin($distance / self::EARTH_RADIUS_NM) * cos(deg2rad($bearing)));
    $endLon = deg2rad($lon) + atan2(sin(deg2rad($bearing)) * sin($distance / self::EARTH_RADIUS_NM) * cos(deg2rad($lat)), cos($distance / self::EARTH_RADIUS_NM) - sin(deg2rad($lat)) * sin($endLat));

    $this->lat = rad2deg($endLat);
    $this->lon = rad2deg($endLon);
  }

  public function nextViaBD(float $bearing, float $distance)
  {
    $endLat = asin(sin(deg2rad($this->lat)) * cos($distance / self::EARTH_RADIUS_NM) + cos(deg2rad($this->lat)) * sin($distance / self::EARTH_RADIUS_NM) * cos(deg2rad($bearing)));
    $endLon = deg2rad($this->lon) + atan2(sin(deg2rad($bearing)) * sin($distance / self::EARTH_RADIUS_NM) * cos(deg2rad($this->lat)), cos($distance / self::EARTH_RADIUS_NM) - sin(deg2rad($this->lat)) * sin($endLat));

    $this->lat = rad2deg($endLat);
    $this->lon = rad2deg($endLon);

    return $this;
  }

  public function fromDms(string $nS = 'N', string $latD, string $latM, string $latS, string $eW = 'W', string $lonD, string $lonM, string $lonS)
  {
    $lat  = $latD + $this->minToDeg($latM) + $this->secToDeg($latS);
    $lon  = $lonD + $this->minToDeg($lonM) + $this->secToDeg($lonS);
    $this->lat = ($nS == 'S') ? -$lat : $lat;
    $this->lon = ($eW == 'W') ? -$lon : $lon;
  }

  public function haversineGreatCircleDistance(float $endLat, float $endLon)
  {
    $theta = $this->lon - $endLon;
    $arc = rad2deg(acos((sin(deg2rad($this->lat)) * sin(deg2rad($endLat))) + (cos(deg2rad($this->lat)) * cos(deg2rad($endLat)) * cos(deg2rad($theta)))));
    $distance = $arc * 60; //Convert degrees to min (nautical miles are min of arc)
    return $distance;
  }

  public function haversineGreatCircleBearing(float $endLat, float $endLon)
  {
    $x = cos(deg2rad($this->lat)) * sin(deg2rad($endLat)) - sin(deg2rad($this->lat)) * cos(deg2rad($endLat)) * cos(deg2rad($endLon - $this->lon));
    $y = sin(deg2rad($endLon - $this->lon)) * cos(deg2rad($endLat));
    $bearing = rad2deg(atan2($y, $x));
    $bearing = fmod($bearing + 360, 360);
    return $bearing;
  }

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

  private function degToMin(float $degrees)
  {
    $minutes = $degrees * 60;
    return $minutes;
  }

  private function degToSec(float $degrees)
  {
    $seconds = $degrees * 60 * 60;
    return $seconds;
  }

  private function minToDeg(float $minutes)
  {
    $degrees = $minutes / 60;
    return $degrees;
  }

  private function secToDeg(float $seconds)
  {
    $degrees = $seconds / 60 / 60;
    return $degrees;
  }
}
