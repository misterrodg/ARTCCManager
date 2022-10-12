<?php

namespace App\Classes;

class CIFPVHFDME
{
  public $vhfId;
  public $vhfName;
  public $vhfFrequency;
  public $navaidClass;
  public $pointType;
  public $lat;
  public $lon;
  public $dmeId;
  public $dmeLat;
  public $dmeLon;
  public $dmeElevation;
  public $stationDeclination;
  public $figureOfMerit;
  public $icaoRegion;

  public function __construct(string $line)
  {
    // $airportId = substr($line,6,4); // UNUSED IN THIS SECTION
    $this->icaoRegion = substr($line, 10, 2);
    //Padding/Spacing 12
    $this->vhfId = trim(substr($line, 13, 4));
    //Padding/Spacing 17-18
    //$icaoCode2 = substr($line,19,2); // UNUSED
    //$continuationRecordNo = substr($line,21,1); // UNUSED IN THIS SECTION
    $this->vhfFrequency = intval(substr($line, 22, 5)) / 100;
    $this->navaidClass = trim(substr($line, 27, 5)); // V:VOR,D:DME,T:TACAN,M:Mil TACAN,I:ILS,N/P:MLS ...
    // T:Terminal,L:Low Alt,H:High Alt,U:Undefined,C:ILS/TACAN,D:biased ILS,A:Auto TWEB,B:Sched TWEB,W:no voice
    $this->lat = 0;
    $this->lon = 0;
    $this->dmeId = trim(substr($line, 51, 4));
    $this->dmeLat = 0;
    $this->dmeLon = 0;
    $this->stationDeclination = 0;
    $this->dmeElevation = intval(substr($line, 79, 5));
    $this->figureOfMerit = substr($line, 84, 1);  // 0:Terminal,1:Low,2:High,3:ExtHigh ...
    // 7:Not in NOTAM system,9:OTS
    //$ilsDmeBias = substr($line,85,2); // UNUSED BY FAA
    //$frequencyProtection = substr($line,87,3); // UNUSED BY FAA
    //$datumCode = substr($line,90,3); // UNUSED
    $this->vhfName = trim(substr($line, 93, 30));
    //$fileRecordNo = substr($line,123,5);
    //$cycleData = substr($line,128,4);

    $coordinates = new CoordinateHandler;
    $vhfLat = substr($line, 32, 9);
    $vhfLon = substr($line, 41, 10);
    $latLon = $coordinates->dmsToDd($vhfLat, $vhfLon, true);
    $this->lat = $latLon->lat;
    $this->lon = $latLon->lon;
    $dmeLat = substr($line, 55, 9);
    $dmeLon = substr($line, 64, 10);
    $dmeLatLon = $coordinates->dmsToDd($dmeLat, $dmeLon, true);
    $this->dmeLat = $dmeLatLon->lat;
    $this->dmeLon = $dmeLatLon->lon;
    $decl = substr($line, 74, 5);
    $this->stationDeclination = (substr($decl, 0, 1) == 'E') ? - (intval(substr($decl, 1, 4)) / 10) : (intval(substr($decl, 1, 4)) / 10);
    if ($this->navaidClass == 'ITW') {
      $this->pointType = 'LOCGS';
    } else {
      $this->pointType = 'VHFDME';
    }
  }

  public function toDBArray(string $airacId)
  {
    $result = array(
      'point_id'                    => $this->vhfId,
      'point_lat'                   => $this->lat,
      'point_lon'                   => $this->lon,
      'point_type'                  => $this->pointType,
      'point_name'                  => $this->vhfName,
      'point_class'                 => $this->navaidClass,
      'frequency'                   => $this->vhfFrequency,
      'dme_id'                      => $this->dmeId,
      'dme_lat'                     => $this->dmeLat,
      'dme_lon'                     => $this->dmeLon,
      'dme_elev'                    => $this->dmeElevation,
      'dme_frequency'               => $this->vhfFrequency,
      'magvar'                      => $this->stationDeclination,
      'figure_of_merit'             => $this->figureOfMerit,
      'region'                      => $this->icaoRegion,
      'AIRAC'                       => $airacId
    );
    return $result;
  }
}
