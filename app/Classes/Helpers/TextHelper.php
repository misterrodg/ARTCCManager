<?php

namespace App\Classes\Helpers;

use App\Classes\Coordinate;

class TextHelper
{
  public function handleGNDFL(string $textVal)
  {
    $textVal = trim($textVal);
    if (!is_numeric($textVal)) {
      if ($textVal == 'GND') {
        return 0;
      }
      if (substr($textVal, 0, 2) == 'FL') {
        return intval(substr($textVal, 2)) * 100;
      }
    }
    return intval($textVal);
  }

  public function handleDMS(string $lat, string $lon)
  {
    $latNs = substr($lat, 0, 1);
    $latD = intval(substr($lat, 1, 2));
    $latM = intval(substr($lat, 3, 2));
    $latS = (intval(substr($lat, 5, 4))) / 100;
    $lonEw = substr($lon, 0, 1);
    $lonD = intval(substr($lon, 1, 3));
    $lonM = intval(substr($lon, 4, 2));
    $lonS = (intval(substr($lon, 6, 4))) / 100;
    $result = new Coordinate;
    $result->fromDms($latNs, $latD, $latM, $latS, $lonEw, $lonD, $lonM, $lonS);
    return $result;
  }
}
