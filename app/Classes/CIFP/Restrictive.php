<?php

namespace App\Classes\CIFP;

use App\Classes\Helpers\TextHelper;

class Restrictive
{
  public $restrictiveType;
  public $restrictiveAirspaceDesignation;
  public $multipleCode;
  public $sequenceNumber;
  public $boundaryVia;
  public $restLat;
  public $restLon;
  public $arcLat;
  public $arcLon;
  public $arcDist;
  public $arcBearing;
  public $lowerLimit;
  public $lowerLimitUnit;
  public $upperLimit;
  public $upperLimitUnit;
  public $restrictiveAirspaceName;
  public $controllingAgency;
  public $icaoRegion;

  public function __construct()
  {
    $this->restrictiveType = '';
    $this->restrictiveAirspaceDesignation = '';
    $this->multipleCode = '';
    $this->sequenceNumber = 0;
    $this->boundaryVia = '';
    $this->restLat = 0.0;
    $this->restLon = 0.0;
    $this->arcLat = null;
    $this->arcLon = null;
    $this->arcDist = null;
    $this->arcBearing = null;
    $this->lowerLimit = 0;
    $this->lowerLimitUnit = '';
    $this->upperLimit = 0;
    $this->upperLimitUnit = '';
    $this->restrictiveAirspaceName = '';
    $this->controllingAgency = '';
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
    $this->restrictiveType = substr($line, 8, 1);
    //A:Alert,C:Caution,D:Danger,M:MOA ...
    //P:Prohibited,R:Restricted,T:Training,W:Warning
    $this->restrictiveAirspaceDesignation = trim(substr($line, 9, 10));
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
    $this->restLat = 0;
    $this->restLon = 0;
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
    $this->restrictiveAirspaceName = null;
    $this->controllingAgency = null;
    if ($continuationRecordNo == 1) {
      $this->restrictiveAirspaceName = trim(substr($line, 93, 30));
    }
    if ($continuationRecordNo == 2) {
      $this->controllingAgency = trim(substr($line, 99, 24));
    }
    //$file_record_no = substr($line,123,5);
    //$cycle_data = substr($line,128,4);

    $this->sequenceNumber += $continuationRecordNo;
    //Main Rest Point
    $restLat = substr($line, 32, 9);
    $restLon = substr($line, 41, 10);
    $coord = $texthelp->handleDMS($restLat, $restLon);
    $this->restLat = $coord->lat;
    $this->restLon = $coord->lon;
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
    $this->restrictiveAirspaceDesignation = $dbObject->rest_id;
    $this->multipleCode = $dbObject->mult_code;
    $this->sequenceNumber = $dbObject->seq_no;
    $this->restrictiveType = $dbObject->rest_type;
    $this->boundaryVia = $dbObject->via;
    $this->restLat = $dbObject->rest_lat;
    $this->restLon = $dbObject->rest_lon;
    $this->arcLat = $dbObject->arc_lat;
    $this->arcLon = $dbObject->arc_lon;
    $this->arcDist = $dbObject->arc_dist;
    $this->arcBearing = $dbObject->arc_bear;
    $this->lowerLimit = $dbObject->min_alt;
    $this->lowerLimitUnit = $dbObject->min_alt_unit;
    $this->upperLimit = $dbObject->max_alt;
    $this->upperLimitUnit = $dbObject->max_alt_unit;
    $this->restrictiveAirspaceName = $dbObject->rest_name;
    $this->controllingAgency = $dbObject->agency;
    $this->icaoRegion = $dbObject->region;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'rest_id'      => $this->restrictiveAirspaceDesignation,
      'mult_code'    => $this->multipleCode,
      'seq_no'       => $this->sequenceNumber,
      'rest_type'    => $this->restrictiveType,
      'via'          => $this->boundaryVia,
      'rest_lat'     => $this->restLat,
      'rest_lon'     => $this->restLon,
      'arc_lat'      => $this->arcLat,
      'arc_lon'      => $this->arcLon,
      'arc_dist'     => $this->arcDist,
      'arc_bear'     => $this->arcBearing,
      'min_alt'      => $this->lowerLimit,
      'min_alt_unit' => $this->lowerLimitUnit,
      'max_alt'      => $this->upperLimit,
      'max_alt_unit' => $this->upperLimitUnit,
      'rest_name'    => $this->restrictiveAirspaceName,
      'agency'       => $this->controllingAgency,
      'region'       => $this->icaoRegion,
      'cycle_id'     => $airacId,
      'next'         => $next
    );
    return $result;
  }
}
