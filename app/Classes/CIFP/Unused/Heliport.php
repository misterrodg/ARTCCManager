<?php

namespace App\Classes;

class CIFPHeliport
{
  public $airportId;
  public $airportFaa;
  public $longestRunway;
  public $ifrCapability;
  public $longestRunwaySurfaceCode;
  public $lat;
  public $lon;
  public $magVar;
  public $elevation;
  public $publicMilitaryIndicator;
  public $airportName;
  public $icaoRegion;

  public function __construct(string $line)
  {
    $this->airportId = substr($line, 6, 4);
    $this->icaoRegion = substr($line, 10, 2);
    //$subsection_code = substr($line,12,1); // UNUSED
    $this->airportFaa = substr($line, 13, 3); //FAA ID when using CIFP
    //$continuation_record_no = substr($line,21,1); // UNUSED
    //$speed_limit_altitude = substr($line,22,5); // UNUSED
    $this->longestRunway = intval(substr($line, 27, 3));
    $this->ifrCapability = substr($line, 30, 1);
    $this->longestRunwaySurfaceCode = substr($line, 31, 1); // H:hard,S:soft,W:water,U:undefined
    $this->lat = 0;
    $this->lon = 0;
    $this->magVar = 0;
    $this->elevation  = intval(substr($line, 56, 5));
    //$speed_limit = substr($line,61,3); // UNUSED
    //$recommended_navaid = substr($line,64,4); // UNUSED
    //$icao_code2 = substr($line,68,2); // UNUSED
    //$transition_altitude = substr($line,70,5); // UNUSED
    //$transition_level = substr($line,75,5); // UNUSED
    $this->publicMilitaryIndicator = substr($line, 80, 1); // C:civilian,M:military,P:private
    //$time_zone = substr($line,81,3); // UNUSED
    //$daylight_indicator = substr($line,84,1); // UNUSED
    //$magnetic_true_indicator = substr($line,85,1); // UNUSED
    //$datum_code = substr($line,86,3); // UNUSED
    $this->airportName = trim(substr($line, 93, 30));
    //$file_record_no = substr($line,123,5); // UNUSED
    //$cycle_data = substr($line,128,4); // UNUSED

    $coordinates = new CoordinateHandler;
    $airportRefPointLat = substr($line, 32, 9);
    $airportRefPointLon = substr($line, 41, 10);
    $latLon = $coordinates->dmsToDd($airportRefPointLat, $airportRefPointLon, true);
    $this->lat = $latLon->lat;
    $this->lon = $latLon->lon;
    $magVar = substr($line, 51, 5);
    $this->magVar = (substr($magVar, 0, 1) == 'E') ? - (intval(substr($magVar, 1, 4)) / 10) : (intval(substr($magVar, 1, 4)) / 10);
  }

  public function toDBArray(string $airacId)
  {
    $result = array(
      'airport_id'                  => $this->airportId,
      'airport_faa'                 => $this->airportFaa,
      'airport_name'                => $this->airportName,
      'airport_lat'                 => $this->lat,
      'airport_lon'                 => $this->lon,
      'magvar'                      => $this->magVar,
      'elevation'                   => $this->elevation,
      'facility_type'               => 'Heliport',
      'use_id'                      => $this->publicMilitaryIndicator,
      'longest_runway'              => $this->longestRunway,
      'longest_surface'             => $this->longestRunwaySurfaceCode,
      'region'                      => $this->icaoRegion,
      'AIRAC'                       => $airacId
    );
    return $result;
  }
}
