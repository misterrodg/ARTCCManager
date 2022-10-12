<?php

namespace App\Classes;

class CIFPNDB
{
  public $ndbId;
  public $ndbName;
  public $ndbFrequency;
  public $navaidClass;
  public $lat;
  public $lon;
  public $magVar;
  public $figureOfMerit;
  public $icaoRegion;

  public function __construct(string $line)
  {
    // $airportId = substr($line,6,4); // UNUSED IN THIS SECTION
    $this->icaoRegion = substr($line, 10, 2);
    //Padding/Spacing 12
    $this->ndbId = trim(substr($line, 13, 4));
    //Padding/Spacing 17-18
    //$icaoCode2 = substr($line,19,2); // UNUSED
    //$continuationRecordNo = substr($line,21,1); // UNUSED IN THIS SECTION
    $this->ndbFrequency = intval(substr($line, 22, 5)) / 10;
    $this->navaidClass = trim(substr($line, 27, 5));  //H:NDB,I:IM,M:MM,O:OM,C:Backmarker ...
    //H:200W+,[blank]:50-199W,M:25-50W,L:<25W ...
    //A:Auto TWEB,B:Sched TWEB,W:no voice
    $this->lat = 0;
    $this->lon = 0;
    //Padding/Spacing 51-73
    $this->magVar = 0;
    $this->figureOfMerit = 0;  // 0:Terminal,1:Low,2:High,3:ExtHigh
    //Padding/Spacing 79-84
    //Reserved 85-89
    //$datumCode = substr($line,90,3); // UNUSED
    $this->ndbName = trim(substr($line, 93, 30));
    //$fileRecordNo = substr($line,123,5);
    //$cycleData = substr($line,128,4);

    $coordinates = new CoordinateHandler;
    $ndbLat = substr($line, 32, 9);
    $ndbLon = substr($line, 41, 10);
    $latLon = $coordinates->dmsToDd($ndbLat, $ndbLon, true);
    $this->lat = $latLon->lat;
    $this->lon = $latLon->lon;
    $magVar = substr($line, 74, 5);
    $this->magVar = (substr($magVar, 0, 1) == 'E') ? - (intval(substr($magVar, 1, 4)) / 10) : (intval(substr($magVar, 1, 4)) / 10);
    switch (substr($this->navaidClass, 2, 1)) {
      case 'L':
        $this->figureOfMerit = 0;
        break; //Terminal
      case 'M':
        $this->figureOfMerit = 1;
        break; // Low
      case  '':
        $this->figureOfMerit = 2;
        break; // Hi
      case 'H':
        $this->figureOfMerit = 3;
        break; // Extra Hi
    }
  }

  public function toDBArray(string $airacId)
  {
    $result = array(
      'point_id'                    => $this->ndbId,
      'point_lat'                   => $this->lat,
      'point_lon'                   => $this->lon,
      'point_type'                  => 'NDB',
      'point_name'                  => $this->ndbName,
      'point_class'                 => $this->navaidClass,
      'frequency'                   => $this->ndbFrequency,
      'magvar'                      => $this->magVar,
      'figure_of_merit'             => $this->figureOfMerit,
      'region'                      => $this->icaoRegion,
      'AIRAC'                       => $airacId
    );
    return $result;
  }
}
