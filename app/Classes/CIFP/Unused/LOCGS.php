<?php

namespace App\Classes;

class CIFPLOCGS
{
  public $locId;
  public $locFrequency;
  public $lat;
  public $lon;
  public $gsLat;
  public $gsLon;
  public $stationDeclination;
  public $figureOfMerit;
  public $icaoRegion;

  public function __construct(string $line)
  {
    $this->icaoRegion = substr($line, 10, 2);
    $this->locId = trim(substr($line, 13, 4));
    $this->ilsCategory = intval(substr($line, 17, 1));
    //Padding/Spacing 18-20
    //$continuationRecordNo = substr($line,21,1); // UNUSED IN THIS SECTION
    $this->locFrequency = intval(substr($line, 22, 5)) / 100;
    //$runwayId = trim(substr($line,27,5));
    $this->lat = 0;
    $this->lon = 0;
    //$locBearing = intval(substr($line,51,4));
    $this->gsLat = 0;
    $this->gsLon = 0;
    //$locPosition = substr($line,74,4);
    //$locPositionRef = substr($line,78,1);
    //$gsPosition = substr($line,79,4);
    //$locWidth = intval(substr($line,83,4))/100;
    //$gsAngle = intval(substr($line,87,3))/100;
    $this->stationDeclination = 0;
    //$gsHeightAtLandingThreshold = substr($line,95,2);
    //$gsElevation = intval(substr($line,97,5));
    //$supportingFacilityId = substr($line,102,4);
    //$supportingFacilityIcaoId = substr($line,106,2);
    //$supportingFacilitySectionCode = substr($line,108,1);
    //$supportingFacilitySubsectionCode = substr($line,109,1);
    //Reserved 110-122
    //$fileRecordNo = substr($line,123,5);
    //$cycleData = substr($line,128,4);

    $coordinates = new CoordinateHandler;
    $locLat = substr($line, 32, 9);
    $locLon = substr($line, 41, 10);
    $latLon = $coordinates->dmsToDd($locLat, $locLon, true);
    $this->lat = $latLon->lat;
    $this->lon = $latLon->lon;
    $gsLat = substr($line, 55, 9);
    $gsLon = substr($line, 64, 10);
    $gsLatLon = $coordinates->dmsToDd($gsLat, $gsLon, true);
    $this->gsLat = $gsLatLon->lat;
    $this->gsLon = $gsLatLon->lon;
    $decl = substr($line, 90, 5);
    $this->stationDeclination = (substr($decl, 0, 1) == 'E') ? - (intval(substr($decl, 1, 4)) / 10) : (intval(substr($decl, 1, 4)) / 10);
  }

  public function toDBArray(string $airacId)
  {
    $result = array(
      'point_id'                    => $this->locId,
      'point_lat'                   => $this->lat,
      'point_lon'                   => $this->lon,
      'point_type'                  => 'LOCGS',
      'frequency'                   => $this->locFrequency,
      'gs_lat'                      => $this->gsLat,
      'gs_lon'                      => $this->gsLon,
      'magvar'                      => $this->stationDeclination,
      'figure_of_merit'             => $this->ilsCategory,
      'region'                      => $this->icaoRegion,
      'AIRAC'                       => $airacId
    );
    return $result;
  }
}
