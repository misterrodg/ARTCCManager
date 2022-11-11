<?php

namespace App\Classes\Helpers;

use App\Classes\Coordinates\Coordinate;

class TextHelper
{
  public function handleGNDFL(string $textVal)
  {
    $result = null;
    $textVal = trim($textVal);
    if (!is_numeric($textVal)) {
      if ($textVal == 'GND') {
        $result = 0;
      }
      if (substr($textVal, 0, 2) == 'FL') {
        $result = intval(substr($textVal, 2)) * 100;
      }
    } else {
      $result = intval($textVal);
    }
    return $result;
  }

  public function handleDMSFormatted(string $lat, string $latFormat, string $lon, string $lonFormat)
  {
    //Find the positions of the identifier in the format string
    $latNSPos = $this->getPositionsInString('/A/', $latFormat);
    $latDPos = $this->getPositionsInString('/D/', $latFormat);
    $latMPos = $this->getPositionsInString('/M/', $latFormat);
    $latSPos = $this->getPositionsInString('/S/', $latFormat);
    $lonEWPos = $this->getPositionsInString('/A/', $lonFormat);
    $lonDPos = $this->getPositionsInString('/D/', $lonFormat);
    $lonMPos = $this->getPositionsInString('/M/', $lonFormat);
    $lonSPos = $this->getPositionsInString('/S/', $lonFormat);
    //Find the values at the positions
    $latNS = substr($lat, $latNSPos->first, $latNSPos->length);
    $latD = intval(substr($lat, $latDPos->first, $latDPos->length));
    $latM = intval(substr($lat, $latMPos->first, $latMPos->length));
    $latS = substr($lat, $latSPos->first, $latSPos->length);
    $lonEW = substr($lon, $lonEWPos->first, $lonEWPos->length);
    $lonD = intval(substr($lon, $lonDPos->first, $lonDPos->length));
    $lonM = intval(substr($lon, $lonMPos->first, $lonMPos->length));
    $lonS = substr($lon, $lonSPos->first, $lonSPos->length);
    $latS = $this->checkSeconds($latS);
    $lonS = $this->checkSeconds($lonS);
    $result = new Coordinate;
    $result->fromDms($latNS, $latD, $latM, $latS, $lonEW, $lonD, $lonM, $lonS);
    return $result;
  }

  private function getPositionsInString(string $needle, string $haystack)
  {
    $result = (object)[];
    $result->first = null;
    $result->length = null;
    preg_match_all($needle, $haystack, $matches, PREG_OFFSET_CAPTURE);
    foreach ($matches[0] as $m) {
      if (is_null($result->first)) {
        $result->first = $m[1];
      }
      $result->length = $m[1] - $result->first + 1;
    }
    return $result;
  }

  private function checkSeconds(string $number)
  {
    if (str_contains($number, '.')) {
      return floatval($number);
    }
    $result = substr($number, 0, 2) . "." . substr($number, 2);
    return floatval($result);
  }
}
