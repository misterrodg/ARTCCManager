<?php

namespace App\Classes\NASR;

use App\Classes\Helpers\TextHelper;

class AWOS
{
  public $stationId;
  public $stationType;
  public $isFunctional;
  public $isAssocNavaid;
  public $lat;
  public $lon;
  public $elev;
  public $frequency;
  public $frequency2;
  public $facilityAssoc;

  public function __construct()
  {
    $this->stationId = null;
    $this->stationType = null;
    $this->isFunctional = null;
    $this->isAssocNavaid = null;
    $this->lat = null;
    $this->lon = null;
    $this->elev = null;
    $this->frequency = null;
    $this->frequency2 = null;
    $this->facilityAssoc = null;
  }

  public function fromString1(string $line)
  {
    $texthelper = new TextHelper;
    /*
      This function is a mess, but the commented lines
      have been left in to show the FAA file definition
      in case they should be useful to anyone.
    */
    //$recordType = trim(substr($line,0,5)); // AWOS1 // IGNORED
    $stationId = trim(substr($line, 5, 4)); // Station Identifier
    $stationType = trim(substr($line, 9, 10)); // Station Type:
    //ASOS, AWOS-1, AWOS-2, AWOS-3, AWOS-4, AWOS-A, ASOS-A, ASOS-B, ASOS-C, ASOS-D, AWSS
    //AWOS-3T, AWOS-3P, AWOS-3PT, AWOS-AV, WEF, SAWS
    $isFunctional = trim(substr($line, 19, 1)); // Commissioning status Y/N
    //$serviceDate = trim(substr($line,20,10)); // Commissioning/decommissioning date (MM/DD/YYY) // IGNORED
    $isAssocNavaid = trim(substr($line, 30, 1)); // Station is associated with Navaid Y/N
    $lat = trim(substr($line, 31, 14)); // Fix Lat (NN-NN-NN.NNNA)
    $lon = trim(substr($line, 45, 15)); // Fix Lon (NNN-NN-NN.NNNA)
    $elev = trim(substr($line, 60, 7)); // Elevation (NNNNN.N)
    //$elevMethod = trim(substr($line,67,1)); // Elevation survey method: E - estimate, S - surveyed // IGNORED
    $frequency = trim(substr($line, 68, 7)); // Station frequency
    $frequency2 = trim(substr($line, 75, 7)); // Second station frequency
    //$phoneNumber = trim(substr($line,82,14)); // Station telephone number // IGNORED
    //$phoneNumber2 = trim(substr($line,96,14)); // Second station telephone number // IGNORED
    $facilityAssoc = trim(substr($line, 110, 11)); // Associated facility site number (ex. 04508.*A)
    //$city = trim(substr($line,121,40)); // Station city // IGNORED
    //$stateId = trim(substr($line,161,2)); // Station State code // IGNORED
    //$effectiveDate = trim(substr($line,163,10)); // Info effective date (MM/DD/YYYY) // IGNORED
    // RECORD SPACING FROM 173 for 82

    // ASSIGNMENTS TO AWOS OBJECT
    $this->stationId = $stationId;
    $this->stationType = $stationType;
    $this->isFunctional = ($isFunctional == 'Y') ? 1 : 0;
    $this->isAssocNavaid = ($isAssocNavaid == 'Y') ? 1 : 0;
    if ($lat == '') {
      $lat = null;
      $lon = null;
    } else {
      $coord = $texthelper->handleDMSFormatted($lat, 'DD-MM-SS.SSSSA', $lon, "DDD-MM-SS.SSSSA");
      $lat = $coord->lat;
      $lon = $coord->lon;
    }
    $this->lat = $lat;
    $this->lon = $lon;
    $this->elev = ($elev == '') ? null : round(floatval($elev));
    $this->frequency = ($frequency != '') ? (number_format($frequency, 3)) : null;
    $this->frequency2 = ($frequency2 != '') ? (number_format($frequency2, 3)) : null;
    $this->facilityAssoc = $facilityAssoc;
  }

  public function fromString2(string $line)
  {
    //AWOS REMARK TEXT // UNUSED
    //$recordType = trim(substr($line,0,5)); // AWOS1 or ASOS1 // IGNORED
    //$stationId = trim(substr($line,5,4)); // Station Identifier
    //$stationType = trim(substr($line,9,10)); // Station Type:
    //ASOS, AWOS-1, AWOS-2, AWOS-3, AWOS-4, AWOS-A, ASOS-A, ASOS-B, ASOS-C, ASOS-D, AWSS
    //AWOS-3T, AWOS-3P, AWOS-3PT, AWOS-AV, WEF, SAWS
    //$remarks = trim(substr($line,19,236)); // Station remarks

    // ASSIGNMENTS TO AWOS OBJECT
  }

  public function fromModel(object $dbObject)
  {
    $this->stationId = $dbObject->awos_id;
    $this->stationType = $dbObject->awos_type;
    $this->isFunctional = $dbObject->is_func;
    $this->isAssocNavaid = $dbObject->is_assoc;
    $this->lat = $dbObject->awos_lat;
    $this->lon = $dbObject->awos_lon;
    $this->elev = $dbObject->elev;
    $this->frequency = $dbObject->freq;
    $this->frequency2 = $dbObject->freq2;
    $this->facilityAssoc = $dbObject->assoc_fac;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'awos_id'   => $this->stationId,
      'awos_type' => $this->stationType,
      'is_func'   => $this->isFunctional,
      'is_assoc'  => $this->isAssocNavaid,
      'awos_lat'  => $this->lat,
      'awos_lon'  => $this->lon,
      'elev'      => $this->elev,
      'freq'      => $this->frequency,
      'freq2'     => $this->frequency2,
      'assoc_fac' => $this->facilityAssoc,
      'cycle_id'  => $airacId,
      'next'      => $next
    );
    return $result;
  }
}
