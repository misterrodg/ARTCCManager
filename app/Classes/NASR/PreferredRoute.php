<?php

namespace App\Classes\NASR;

class PreferredRoute
{
  public $orig;
  public $dest;
  public $type;
  public $seq;
  public $area;
  public $altitude;
  public $aircraft;
  public $hours1;
  public $hours2;
  public $hours3;
  public $direction;
  public $narType;
  public $route;

  public function __construct()
  {
    $this->orig = null;
    $this->dest = null;
    $this->type = null;
    $this->seq = null;
    $this->area = null;
    $this->altitude = null;
    $this->aircraft = null;
    $this->hours1 = null;
    $this->hours2 = null;
    $this->hours3 = null;
    $this->direction = null;
    $this->narType = null;
    $this->route = array();
  }

  public function fromString1(string $line)
  {
    //$recordType = trim(substr($line,0,4)); // PFR1 (route data) or PFR2 () // IGNORED;
    $origAirportFacId = trim(substr($line, 4, 5)); // Origin Facility Location ID
    $destAirportFacId = trim(substr($line, 9, 5)); // Dest Facility Location ID
    $routeTypeCode = trim(substr($line, 14, 3)); // PrefRoute Type Code: L, H, LSD, HSD, SLD, SHD, TEC, NAR
    $routeSeq = intval(trim(substr($line, 17, 2))); // Route sequence number (1-99)
    //$routeTypeDesc = trim(substr($line,19,30)); // PrefRoute Type Description // IGNORED
    //L - LOW ALTITUDE, H - HIGH ALTITUDE, LSD - LOW ALT SINGLE DIR, HSD - HIGH ALT SINGLE DIR
    //SLD - SPECIAL LOW ALT DIRECTIONAL, SHD - SPECIAL HIGH ALT DIRECTIONAL, TEC - TOWER ENROUTE CONTROL, NAR - NORTH AMERICAN ROUTE
    $areaDesc = trim(substr($line, 49, 75)); // PrefRoute Area Description
    $altDesc = trim(substr($line, 124, 40)); // PrefRoute Alt Description
    $acftDesc = trim(substr($line, 164, 50)); // PrefRoute Aircraft Description
    $hoursDesc1 = trim(substr($line, 214, 15)); // PrefRoute Hours (GMT) Description (1)
    $hoursDesc2 = trim(substr($line, 229, 15)); // PrefRoute Hours (GMT) Description (2)
    $hoursDesc3 = trim(substr($line, 244, 15)); // PrefRoute Hours (GMT) Description (3)
    $directionDesc = trim(substr($line, 259, 20)); // Route Direction Limitations Description
    $narType = trim(substr($line, 279, 20)); // NAR Type (Common or non-common)
    if ($narType == 'NON-COMMON') {
      $narType = 'NC';
    } else {
      $narType = '';
    }
    //$designator = trim(substr($line,299,5)); // Designator // IGNORED
    //$destCity = trim(substr($line,304,40)); // Destination City // IGNORED

    $this->orig = $origAirportFacId;
    $this->dest = $destAirportFacId;
    $this->type = $routeTypeCode;
    $this->seq = $routeSeq;
    $this->area = $areaDesc;
    $this->altitude = $altDesc;
    $this->aircraft = $acftDesc;
    $this->hours1 = $hoursDesc1;
    $this->hours2 = $hoursDesc2;
    $this->hours3 = $hoursDesc3;
    $this->direction = $directionDesc;
    $this->narType = $narType;
  }

  public function fromString2(string $line)
  {
    //$recordType = trim(substr($line,0,4)); // PFR1 (route data) or PFR2 (segment data) // IGNORED
    //$destCity = trim(substr($line,304,40)); // Destination City // IGNORED
    //$origAirportFacId = trim(substr($line,4,5)); // Origin Facility Location ID  // IGNORED
    //$destAirportFacId = trim(substr($line,9,5)); // Dest Facility Location ID  // IGNORED
    //$routeTypeCode = trim(substr($line,14,3)); // PrefRoute Type Code: L, H, LSD, HSD, SLD, SHD, TEC, NAR // IGNORED
    //$routeSeq = intval(trim(substr($line,17,2))); // Route sequence number (1-99) // IGNORED
    //$segmentSeq = intval(trim(substr($line,19,3))); // Sequence number within route // IGNORED
    $segmentId = trim(substr($line, 22, 48)); // Segment Identifier (NAVAID IDENT, AWY NUMBER, FIX NAME, DP NAME, STAR NAME)
    //$segmentType = trim(substr($line,70,7)); // Segment Type (AIRWAY,FIX,DP,STAR,NAVAID,UNKNOWN) // IGNORED
    //$segmentStateCode = trim(substr($line,77,2)); // Fix State Code (Post Office Alpha Code) // IGNORED
    //$icaoRegion = trim(substr($line,79,2)); // ICAO Region Code // IGNORED
    //$facilityType = trim(substr($line,81,2)); // Navaid Facility Type: C,D,F,L,M,MD,O,OD,R,RD,T,U,V // IGNORED
    //$facilityTypeDesc = trim(substr($line,83,20)); // Navaid Facility Type Description // IGNORED
    //C - VORTAC, D - VOR-DME, F - FAN MARKER, L - LFR, M - MARINE NDB, MD - MARINE NDB/DME,
    //O - VOT, OD - DME, R - NDB, RD - NDB/DME, T - TACAN, U - UHF NDB, V - VOR
    //$radialDist = trim(substr($line,103,7)); // RRR or RRR/DDD // IGNORED
    // RECORD SPACING FROM 110 for 234
    array_push($this->route, $segmentId);
  }

  public function fromModel(object $dbObject)
  {
    $this->orig = $dbObject->orig;
    $this->dest = $dbObject->dest;
    $this->type = $dbObject->type;
    $this->seq = $dbObject->seq_no;
    $this->area = $dbObject->area;
    $this->altitude = $dbObject->alt;
    $this->aircraft = $dbObject->acft;
    $this->hours1 = $dbObject->hours1;
    $this->hours2 = $dbObject->hours2;
    $this->hours3 = $dbObject->hours3;
    $this->direction = $dbObject->dir;
    $this->narType = $dbObject->nar_type;
    $this->route = $dbObject->route;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $route = implode(' ', $this->route);
    $result = array(
      'orig'      => $this->orig,
      'dest'      => $this->dest,
      'type'      => $this->type,
      'seq_no'    => $this->seq,
      'area'      => $this->area,
      'alt'       => $this->altitude,
      'acft'      => $this->aircraft,
      'hours1'    => $this->hours1,
      'hours2'    => $this->hours2,
      'hours3'    => $this->hours3,
      'dir'       => $this->direction,
      'nar_type'  => $this->narType,
      'route'     => $route,
      'cycle_id'  => $airacId,
      'next'      => $next
    );
    return $result;
  }
}
