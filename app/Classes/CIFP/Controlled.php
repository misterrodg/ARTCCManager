<?php

namespace App\Classes\CIFP;

use App\Classes\Helpers\TextHelper;

class Controlled
{
  public $airspaceCenter;
  public $multipleCode;
  public $sequenceNumber;
  public $airspaceClassification;
  public $boundaryVia;
  public $contLat;
  public $contLon;
  public $arcLat;
  public $arcLon;
  public $arcDist;
  public $arcBearing;
  public $lowerLimit;
  public $lowerLimitUnit;
  public $upperLimit;
  public $upperLimitUnit;
  public $controlledAirspaceName;
  public $icaoRegion;

  public function __construct()
  {
    $this->airspaceCenter = '';
    $this->multipleCode = '';
    $this->sequenceNumber = 0;
    $this->airspaceClassification = '';
    $this->boundaryVia = '';
    $this->contLat = 0.0;
    $this->contLon = 0.0;
    $this->arcLat = null;
    $this->arcLon = null;
    $this->arcDist = null;
    $this->arcBearing = null;
    $this->lowerLimit = 0;
    $this->lowerLimitUnit = '';
    $this->upperLimit = 0;
    $this->upperLimitUnit = '';
    $this->controlledAirspaceName = '';
    $this->icaoRegion = '';
  }

  public function fromString(string $line)
  {
    $texthelp = new TextHelper;
    /*
      This function is a mess, but the commented lines
      have been left in to show the FAA file definition
      in case they should be useful to anyone.
    */
    $this->icaoRegion = substr($line, 6, 2);
    //$airspace_type = substr($line,8,1);
    //A:Class C,C:CTA,M:TMA/TCA,R:TRSA ...
    //T:Class B,Z:Class D
    //Useful, but FAA uses $airspace_classification as direct B/C/D Airspace designation
    $this->airspaceCenter = trim(substr($line, 9, 5));
    //$this->sectionCode2 = substr($line, 14, 1);
    //$this->subsectionCode2 = substr($line, 15, 1);
    $this->airspaceClassification = substr($line, 16, 1);
    //Padding/Spacing 17-18
    $this->multipleCode = substr($line, 19, 1);
    $this->sequenceNumber = intval(substr($line, 20, 4));
    $continuationRecordNo = intval(substr($line, 24, 1));
    //$level = substr($line,25,1);
    //$time_code = substr($line,26,1);  //C:Continuous,H:Cont-NoHoliday,N:Not continuous,[Blank]:By NOTAM
    //$notam = substr($line,27,1);
    //Padding/Spacing 28-29
    $this->boundaryVia = substr($line, 30, 2);
    //A:Arc by edge,C:Circle,G:Great Circle,H:Rhumb Line,L:Counter Clockwise ARC,R:Clockwise ARC,
    //E:End of description, return to origin point
    $this->contLat = 0;
    $this->contLon = 0;
    $this->arcOriginLat = 0;
    $this->arcOriginLon = 0;
    $this->arcDist = 0;
    $this->arcBearing = 0;
    //$rnp = substr($line,78,3);
    $lowerLimit = substr($line, 81, 5);
    $this->lowerLimit = $texthelp->handleGNDFL($lowerLimit);
    $this->lowerLimitUnit = substr($line, 86, 1); //M:MSL,A:AGL
    $upperLimit = substr($line, 87, 5);
    $this->upperLimit = $texthelp->handleGNDFL($upperLimit);
    $this->upperLimitUnit = substr($line, 92, 1); //M:MSL,A:AGL
    $this->controlledAirspaceName = (trim(substr($line, 93, 30)) == '') ? null : trim(substr($line, 93, 30));
    //$file_record_no = substr($line,123,5);
    //$cycle_data = substr($line,128,4);

    $this->sequenceNumber += $continuationRecordNo;
    //Main Cont Point
    $contLat = substr($line, 32, 9);
    $contLon = substr($line, 41, 10);
    $coord = $texthelp->handleDMS($contLat, $contLon);
    $this->contLat = $coord->lat;
    $this->contLon = $coord->lon;
    //Arc Definition
    $arcOriginLat = substr($line, 51, 9);
    $arcOriginLon = substr($line, 60, 10);
    if (trim($arcOriginLat) != '') {
      $arcCoord = $texthelp->handleDMS($arcOriginLat, $arcOriginLon);
      $this->arcLat = $arcCoord->lat;
      $this->arcLon = $arcCoord->lon;
      $this->arcDist = intval(substr($line, 70, 4)) / 10;
      $this->arcBearing = intval(substr($line, 74, 4)) / 10;
    }
  }

  public function fromModel(object $dbObject)
  {
    $this->airspaceCenter = $dbObject->cont_id;
    $this->multipleCode = $dbObject->mult_code;
    $this->sequenceNumber = $dbObject->seq_no;
    $this->airspaceClassification = $dbObject->cont_type;
    $this->boundaryVia = $dbObject->via;
    $this->contLat = $dbObject->cont_lat;
    $this->contLon = $dbObject->cont_lon;
    $this->arcLat = $dbObject->arc_lat;
    $this->arcLon = $dbObject->arc_lon;
    $this->arcDist = $dbObject->arc_dist;
    $this->arcBearing = $dbObject->arc_bear;
    $this->lowerLimit = $dbObject->min_alt;
    $this->lowerLimitUnit = $dbObject->min_alt_unit;
    $this->upperLimit = $dbObject->max_alt;
    $this->upperLimitUnit = $dbObject->max_alt_unit;
    $this->controlledAirspaceName = $dbObject->cont_name;
    $this->icaoRegion = $dbObject->region;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'cont_id'      => $this->airspaceCenter,
      'mult_code'    => $this->multipleCode,
      'seq_no'       => $this->sequenceNumber,
      'cont_type'    => $this->airspaceClassification,
      'via'          => $this->boundaryVia,
      'cont_lat'     => $this->contLat,
      'cont_lon'     => $this->contLon,
      'arc_lat'      => $this->arcLat,
      'arc_lon'      => $this->arcLon,
      'arc_dist'     => $this->arcDist,
      'arc_bear'     => $this->arcBearing,
      'min_alt'      => $this->lowerLimit,
      'min_alt_unit' => $this->lowerLimitUnit,
      'max_alt'      => $this->upperLimit,
      'max_alt_unit' => $this->upperLimitUnit,
      'cont_name'    => $this->controlledAirspaceName,
      'region'       => $this->icaoRegion,
      'cycle_id'     => $airacId,
      'next'         => $next
    );
    return $result;
  }
}
