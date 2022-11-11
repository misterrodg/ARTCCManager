<?php

namespace App\Classes\Helpers;

use App\Classes\Coordinates\Coordinate;

class TextHelper
{
  /**
   * Translates FAA textual altitude values into integer values.
   * @param string $textVal Textual altitude (e.g. 'GND' or 'FL220')
   * @return int|null Integer value of altitude. Null if unable to parse.
   */
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

  /**
   * Handles DMS-formatted strings, allowing the format to be specified.
   * @param string $lat Latitude string in DMS.
   * @param string $latFormat Format of the DMS string, where 'A' is North/South, 'D' is degrees, 'M' is minutes, and 'S' is seconds (38-56-50.8000N would be 'DD-MM-SS.SSSSA').
   * @param string $lon Longitude string in DMS.
   * @param string $lonFormat Format of the DMS string, where 'A' is East/West, 'D' is degrees, 'M' is minutes, and 'S' is seconds (077-27-35.8000W would be 'DDD-MM-SS.SSSSA').
   * @return Coordinate
   */
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

  /**
   * Finds first occurrence of a string value in a string, along with the length of its occurrence.
   * @param string $needle The string value being searched for.
   * @param string $haystack The string to search within.
   * @return object Object with keys $first, which is the first occurrence of the $needle, and $length, which is the number of occurrences thereafter.
   */
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

  /**
   * Checks for FAA seconds strings that do not contain a decimal point.
   * @param string $number Seconds string to be checked.
   * @return float Properly formatted seconds string.
   */
  private function checkSeconds(string $number)
  {
    if (str_contains($number, '.')) {
      return floatval($number);
    }
    $result = substr($number, 0, 2) . "." . substr($number, 2);
    return floatval($result);
  }
}
