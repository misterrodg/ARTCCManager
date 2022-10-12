<?php
namespace App\Classes\NASR;

class STARDP
{
  public $stardpType;
  public $fixFacCode;
  public $pointLat;
  public $pointLon;
  public $pointId;
  public $procCompCode;
  public $procTransBodyName;
  public $airwaysNavaids;

  public function __construct(string $line){
    $recordType = trim(substr($line,0,5)); // DNNNN or SNNNN // IGNORED;
    //RECORD SPACING FROM 5 FOR 5
    $fixFacCode = trim(substr($line,10,2)); // Fix/Facility Type Code that describes Lat, Lon, and ID fields:
      //AA - ADAPTED AIRPORT, B - BREAK IN ROUTE,C - COORDINATED FIX, CN - COMPUTER NAVIGATION FIX
      //D - DME, E - ENTRY (ARTCC), I - BEARING INTERSECTION, IB - AIR ROUTES BEARING INTERSECTION
      //IS - SEGMENT BEARING INTERSECTION, NA - NAVAID AIRPORT (AIRPORT SERVED BY DP BUT NOT SERVED BY ANY BODY)
      //ND - NAVAID VORDME, NO - NAVAID DME, NV - NAVAID VOR, NW - NAVAID VORTAC, NX - NAVAID RBN
      //NT - NAVAID TACAN, NZ - NAVAID ILS, N7 - NAVAID LFR, N8 - NAVAID VHFRBN, N9 - NAVAID VHFRBN
      //P - WAYPOINT, R - REPORTING POINT, SS - START DPS DEPARTING, ST - START DPS TRANSITION
      //T - TURNING POINT, TA - TURNING POINT, TP - TURNING POINT, TT - TRAINING POINT, U - ARTCC BOUNDARY POINT
      //XA - AIRWAY CROSSING, XT - TRANSITION CROSSING, Z - ARTCC BOUNDARY CROSSING
    //RECORD SPACING FROM 12 FOR 1
    $pointLat = trim(substr($line,13,8)); // Fix/Navaid/Airport Lat  (XDDMMSST NS Dec Min Sec Tenths, decimal point not present but implied)
    $pointLon = trim(substr($line,21,9)); // Fix/Navaid/Airport Lat (XDDDMMSST EW Dec Min Sec Tenths, decimal point not present but implied)
    $pointId = trim(substr($line,30,6)); // Fix/Navaid/Airport ID
    //$icaoRegion = trim(substr($line,36,2)); // ICAO Region code (Fixes only) // IGNORED
    $procCompCode = trim(substr($line,38,13)); // Procedure Computer Code (only present in first record of basic DP and transitions)
      //Procedure without an assigned Computer Code are indicated with "NOT ASSIGNED"
    $procTransBodyName = trim(substr($line,51,110)); // Name assigned to the Procedure, transition, or body (only present in first record)
    $airwaysNavaids = trim(substr($line,161,62)); // Airways/navaids using numbered fixes

    // ASSIGNMENTS TO STARDP OBJECT
    $this->stardpType = (substr($recordType,0,1) == 'D') ? 'DEP' : 'ARR';
    $this->fixFacCode = $fixFacCode;
    $this->pointLat = $pointLat;
    $this->pointLon = $pointLon;
    $this->pointId = $pointId;
    $this->procCompCode = $procCompCode;
    $this->procTransBodyName = $procTransBodyName;
    $this->airwaysNavaids = $airwaysNavaids;
  }

  public function toDBArray(string $airacId){
    $result = array(
      'db_field' => $this->variable,
    );

    return $result;
  }
}
