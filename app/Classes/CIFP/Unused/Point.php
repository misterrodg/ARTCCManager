<?php

namespace App\Classes;

class CIFPPoint
{
  public $waypointId;
  public $waypointClass;
  public $waypointType;
  public $lat;
  public $lon;
  public $magVar;
  public $figureOfMerit;
  public $icaoRegion;

  public function __construct(string $line)
  {
    //$regionCode = substr($line,6,4);
    $this->icaoRegion = substr($line, 10, 2);
    //$subsection  = substr($line,12,1);
    $this->waypointId = trim(substr($line, 13, 5));
    //$icaoCode2 = substr($line,19,2); // UNUSED
    //$continuationRecordNo = substr($line,21,1); // UNUSED IN THIS SECTION
    $this->waypointClass = substr($line, 26, 3); //Column 1
    //C:ANAV/RNAV,I:Unnamed/Charted,N:NDB-as-waypt ...
    //R:Named,U:Uncharted,V:VFR waypt,W:RNAV
    //Column 2
    //A:FAF,B:IAF/FAF,C:FACFix,D:IF,E:FAA Off-route NRS ...
    //F:Off-route int,I:IAF,K:FAC@IAF,L:FAC@IF,M:Missed ...
    //N:IAF/Missed,O:Oceanic Entry,P:FAA Pitch/Catch ...
    //S:FAA SUA Waypt,U:FIR/UIR,V:Lat/Lon Full,W:Lat/Lon Half
    //Column 3 NA FAA
    $waypointUsage = trim(substr($line, 30, 1)); //B:Hi/Lo,H:Hi,L:Lo,[Blank]:Terminal
    $this->magVar = 0;
    //$datumCode = substr($line,84,3); // UNUSED
    //$name_format_indicator = substr($line,95,3); // UNUSED
    //$waypoint_name_description = substr($line,98,25); // UNUSED
    //$fileRecordNo = substr($line,123,5);
    //$cycle_data  = substr($line,128,4);
    $this->waypointType = 'Intersection';

    //Convert from waypoint usage to FoM // 0:Terminal,1:Low,2:High,4:Hi/Lo (unofficial)
    switch ($waypointUsage) {
      case  '':
        $this->figureOfMerit = 0;
        break; //Terminal
      case 'L':
        $this->figureOfMerit = 1;
        break; // Low
      case 'H':
        $this->figureOfMerit = 2;
        break; // Hi
      case 'B':
        $this->figureOfMerit = 4;
        break; //Unofficial Low/Hi
    }
    $coordinates = new CoordinateHandler;
    $waypointLat = substr($line, 32, 9);
    $waypointLon = substr($line, 41, 10);
    $latLon = $coordinates->dmsToDd($waypointLat, $waypointLon, true);
    $this->lat = $latLon->lat;
    $this->lon = $latLon->lon;
    $magVar = substr($line, 74, 5);
    $this->magVar = (substr($magVar, 0, 1) == 'E') ? - (intval(substr($magVar, 1, 4)) / 10) : (intval(substr($magVar, 1, 4)) / 10);
  }

  public function toDBArray(string $airacId)
  {
    $result = array(
      'point_id'                    => $this->waypointId,
      'point_lat'                   => $this->lat,
      'point_lon'                   => $this->lon,
      'point_type'                  => $this->waypointType,
      'point_class'                 => $this->waypointClass,
      'magvar'                      => $this->magVar,
      'figure_of_merit'             => $this->figureOfMerit,
      'region'                      => $this->icaoRegion,
      'AIRAC'                       => $airacId
    );
    return $result;
  }
}
