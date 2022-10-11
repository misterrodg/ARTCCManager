<?php

namespace App\Classes\CIFP;

class Procedure
{
  public $airportId;
  public $procedureType;
  public $sidStarAppId;
  public $routeType;
  public $transitionId;
  public $sequenceNumber;
  public $fixId;
  public $continuationRecordNo;
  public $waypointDescriptionCode;
  public $turnDirection;
  public $pathAndTerminator;
  public $arcRadius;
  public $altitudeDescription;
  public $altitude;
  public $altitude2;
  public $speedLimit;
  public $centerFix;
  public $speedLimitDescription;
  public $icaoRegion;

  public function __construct()
  {
    $this->airportId = '';
    $this->procedureType = '';
    $this->sidStarAppId = '';
    $this->routeType = '';
    $this->transitionId = '';
    $this->sequenceNumber = 0;
    $this->fixId = '';
    $this->continuationRecordNo = '';
    $this->waypointDescriptionCode = '';
    $this->turnDirection = '';
    $this->pathAndTerminator = '';
    $this->arcRadius = 0.0;
    $this->altitude = 0;
    $this->altitude2 = 0;
    $this->altitudeDescription = '';
    $this->speedLimit = 0;
    $this->centerFix = '';
    $this->speedLimitDescription = '';
    $this->icaoRegion = '';
  }

  public function fromString(string $line)
  {
    /*
      This function is a mess, but the commented lines
      have been left in to show the FAA file definition
      in case they should be useful to anyone.
    */
    $this->airportId = trim(substr($line, 6, 4));
    $this->icaoRegion = substr($line, 10, 2);
    $this->procedureType = substr($line, 12, 1);
    $this->sidStarAppId = trim(substr($line, 13, 6)); //SID/STAR/APP Name#
    $this->routeType = substr($line, 19, 1);
    //D:: 0:EOSID,1:RWYTrans,2:SID,3:SIDTrans,4:RNAVSIDRWYTrans,5:RNAVSID, ...
    //6:RNAVSIDENRTrans,F:FMSSIDRWYTrans,M:FMSSID,S:FMSSIDENRTrans,T:VectorSIDRWYTrans,V:VectorSIDENRTrans
    //E:: 1:STARENRTrans,2:STAR,3:STARRWYTrans,4:RNAVSTARENRTrans,5:RNAVSTAR, ...
    //6:RNAVSTARRWYTrans,7:ProfDescENRTrans,8:ProfDesc,9:ProfDescRWYTrans,F:FMSSTARENRTrans,M:FMSSTAR,S:FMSSTARRWYTrans
    //F:: A:AppTrans,B:LOC/BC,D:VORDME,F:FMS,G:IGS,I:ILS,J:GNSS,L:LOC Only, ...
    //M:MLS,N:NDB,P:GPS,Q:NDBDME,R:RNAV,S:VORTAC,T:TACAN,U:SDF,V:VOR,W:MLS-A,X:LDA,Y:MLS-B/C,Z:Missed
    $this->transitionId = trim(substr($line, 20, 5));
    //Padding/Spacing 25
    $this->sequenceNumber = intval(substr($line, 26, 3));
    $this->fixId = trim(substr($line, 29, 5));
    //$icao_code2 = substr($line,34,2);
    //$section_code2 = substr($line,36,1);
    //$subsection_code2 = substr($line,37,1);
    $this->continuationRecordNo = substr($line, 38, 1);
    $this->waypointDescriptionCode = substr($line, 39, 4);
    //COL 39: A:Airport,E:Essential,F:Off-Airway,G:Runway/Helipad,H:Heliport,N:NDB,P:Phantom,R:Non-essential,T:Transition,V:VHF
    //COL 40: B:Flyover/EndSID/STAR/ApprTrans/Final,E:End of ENR,U:Uncharted,Y:Flyover
    //COL 41: A:UnnamedStepAfterFAF,B:UnnamedStepBeforeFAF,C:ATCCompulsory,G:OceanicGateway,M:FirstMAPLeg,P:PathPoint,S:UnnamedStep
    //COL 42: A:IAF,B:IF,C:IAF/Hold,D:IAF/FAC,E:FinalEnd,F:PublishedFAF/DatabaseFAF,H:Holding,I:FAC,M:PublishedMAPF
    $this->turnDirection = (trim(substr($line, 43, 1)) != '') ? substr($line, 43, 1) : null;
    //$rnp = substr($line,44,3);
    $this->pathAndTerminator = substr($line, 47, 2);
    //IF:Initial Fix/Leg,TF:TrackToFix,CF:CourseToFix,DF:DirectToFix,FA:FixToAltitude,FC:FromFixForDistance, ...
    //FD:FromFixToDMEDist,FM:FromFixToManualTermination,CA:CourseToAltitude,CD:CourseToDMEDist,CI:CourseToIntercept, ...
    //CR:CourseToRadial,RF:RadiusToFix,AF:ArcToFix,VA:HeadingToAltitude,VD:HeadingToDMEDist,VI:HeadingToIntercept, ...
    //VM:HeadingToManualTermination,VR:HeadingToRadial,PI:ProcedureTurn,HA:HoldUntilAlt,HF:HoldISOProcedureTurn,HM:HoldManualTerm
    //$turn_direction_valid = substr($line,49,1);
    //$recommended_navaid = substr($line,50,4);
    //$icao_code3 = substr($line,54,2);
    $this->arcRadius = (trim(substr($line, 56, 6)) != '') ? intval(substr($line, 56, 6)) / 1000 : null;
    //$theta = substr($line,62,4);
    //$rho = substr($line,66,4);
    //$magnetic_course = intval(substr($line,70,4))/10;
    //$route_holding_dist_time = intval(substr($line,74,4))/10;
    //$recommended_nav_section = substr($line,78,1);
    //$recommended_nav_subsection = substr($line,79,1);
    //Reserved 80-81
    $this->altitudeDescription = (trim(substr($line, 82, 1)) != '') ? substr($line, 82, 1) : null;
    //+:AOA,-:AOB,@/[Blank]:At,B:AOA Alt1/AOB Alt2,C:AOA Alt2,G:AT in Alt1/GS in Alt2, ...
    //H:AOA in Alt1/GS in Alt2,I:AT in Alt1/GSInt in Alt2,J:AOA in Alt1/GSInt in Alt2, ...
    //V:AT Step Down in Alt1/AT in Alt2,Y:AOB Step Down in Alt1/AT in Alt2
    //$atc_indicator = substr($line,83,1);
    //A:Assigned/Modded by ATC,S:Assigned if no alt
    $this->altitude = (trim(substr($line, 84, 5)) != '') ? intval(substr($line, 84, 5)) : null;
    $this->altitude2 = (trim(substr($line, 89, 5)) != '') ? intval(substr($line, 89, 5)) : null;
    //$transition_altitude = substr($line,94,5);
    $this->speedLimit = (trim(substr($line, 99, 3)) != '') ? intval(substr($line, 99, 3)) : null;
    //$vertical_angle = intval(substr($line,102,4))/100;
    $this->centerFix = (trim(substr($line, 106, 5)) != '') ? trim(substr($line, 106, 5)) : null; //Center fix for RF Leg or MinSafeAlt
    //$multiple_code = substr($line,111,1); //Defines additional segments for MSA at $center_fix
    //$icao_code4 = substr($line,112,2);
    //$section_code3 = substr($line,114,1);
    //$subsection_code3 = substr($line,115,1);
    //$gps_fms_indication = substr($line,116,1);
    //0:NoGPS/FMSoverlay,1:GPSoverlayAuthWNavaids,2:GPSoverlayAuthNoNavaids, ...
    //3:GPSoverlay(RNAV/GPS),4:FMSoverlay,5:FMS/GPSoverlay,A:RNAV/GPSwWAAS, ...
    //B:RNAV/GPSnoWAAS,C:RNAV/GPSunspecifiedWAAS,P:StandaloneGPS,U:OverlayUnspecified
    $this->speedLimitDescription = (trim(substr($line, 117, 1)) != '') ? substr($line, 117, 1) : null;
    //@/[blank]:AT,+:AOA,-:AOB
    //$apch_route_qual1 = substr($line,118,1);
    //D:DMEReq,J:GPSReq DME/DME NA,L:GBAS,N:DMENotReq,P:GPSReq,R:GPS DME/DME Req,T:DME/DMEReq,U:RNAV,V:VORDME RNAV,W:SBAS
    //$apch_route_qual2 = substr($line,119,1);
    //A:PrimaryMissed,B:SecondaryMissed,E:EOMissed,C:ProcedureCTLMins,S:ProcedureStraightIn
    //Padding/Spacing 120-122
    //$file_record_no = substr($line,123,5);
    //$cycle_data = substr($line,128,4);
  }

  public function fromModel(object $dbObject)
  {
    $this->airportId = $dbObject->airport_id;
    $this->procedureType = $dbObject->proc_type;
    $this->sidStarAppId = $dbObject->proc_id;
    $this->routeType = $dbObject->proc_section;
    $this->transitionId = $dbObject->trans_id;
    $this->sequenceNumber = $dbObject->seq_no;
    $this->fixId = $dbObject->fix_id;
    $this->waypointDescriptionCode = $dbObject->wp_desc;
    $this->turnDirection = $dbObject->turn_dir;
    $this->pathAndTerminator = $dbObject->path_term;
    $this->arcRadius = $dbObject->arc_dist;
    $this->altitude = $dbObject->alt1;
    $this->altitude2 = $dbObject->alt2;
    $this->altitudeDescription = $dbObject->alt_desc;
    $this->speedLimit = $dbObject->speed;
    $this->centerFix = $dbObject->center_fix;
    $this->speedLimitDescription = $dbObject->speed_desc;
    $this->icaoRegion = $dbObject->region;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    if ($this->continuationRecordNo == 0 || $this->continuationRecordNo == 1) {
      $result = array(
        'proc_type'    => $this->procedureType,
        'proc_section' => $this->routeType,
        'airport_id'   => $this->airportId,
        'proc_id'      => $this->sidStarAppId,
        'trans_id'     => $this->transitionId,
        'seq_no'       => $this->sequenceNumber,
        'fix_id'       => $this->fixId,
        'wp_desc'      => $this->waypointDescriptionCode,
        'turn_dir'     => $this->turnDirection,
        'path_term'    => $this->pathAndTerminator,
        'arc_dist'     => $this->arcRadius,
        'alt_desc'     => $this->altitudeDescription,
        'alt1'         => $this->altitude,
        'alt2'         => $this->altitude2,
        'speed'        => $this->speedLimit,
        'speed_desc'   => $this->speedLimitDescription,
        'center_fix'   => $this->centerFix,
        'region'       => $this->icaoRegion,
        'cycle_id'     => $airacId,
        'next'         => $next
      );
    } else {
      $result = null;
    }
    return $result;
  }
}
