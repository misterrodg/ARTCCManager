<?php

namespace App\Classes;

class CIFPRoute
{
  public $routeId;
  public $sequenceNumber;
  public $fixId;
  public $waypointDescriptionCode;
  public $routeType;
  public $level;
  public $minimumAltitude;
  public $minimumAltitudeRev;
  public $maximumAltitude;
  public $icaoRegion;

  public function __construct(string $line)
  {
    //Padding/Spacing 6-12
    $this->routeId = trim(substr($line, 13, 5));
    //Reserved 18
    //Padding/Spacing 19-24
    $this->sequenceNumber = intval(substr($line, 25, 4));
    $this->fixId = substr($line, 29, 5);
    $this->icaoRegion = substr($line, 34, 2);
    //$section_code2 = substr($line,36,1);
    //$subsection_code2 = substr($line,37,1);
    //$continuation_record_no = substr($line,38,1);
    $this->waypointDescriptionCode = substr($line, 39, 4);
    //COL 40: A:Airport,E:Essential,F:Off-Airway,G:Runway/Helipad,H:Heliport,N:NDB,P:Phantom,R:Non-essential,T:Transition,V:VHF
    //COL 41: B:Flyover/EndSID/STAR/ApprTrans/Final,E:End of ENR,U:Uncharted,Y:Flyover
    //COL 42: A:UnnamedStepAfterFAF,B:UnnamedStepBeforeFAF,C:ATCCompulsory,G:OceanicGateway,M:FirstMAPLeg,P:PathPoint,S:UnnamedStep
    //COL 43: A:IAF,B:IF,C:IAF/Hold,D:IAF/FAC,E:FinalEnd,F:PublishedFAF/DatabaseFAF,H:Holding,I:FAC,M:PublishedMAPF
    //$boundary_code = substr($line,43,1);
    $this->routeType = substr($line, 44, 1); //A:Airline,C:Control,D:Direct,H:Helo,O:Official,R:RNAV,S:Undesignated ATS
    $this->level = substr($line, 45, 1); //B:Hi/Lo,H:Hi,L:Lo
    //$direction_restriction = substr($line,46,1); //F:Forward,B:Backward,[Blank]:No restrictions (direction in relation to seq)
    //$cruise_table_indicator = substr($line,47,2);
    //$eu_indicator = substr($line,49,1);
    //$recommended_navaid = substr($line,50,4);
    //$icao_code2 = substr($line,54,2);
    //$rnp = intval(substr($line,56,3))/10;
    //Padding/Spacing 59-61
    //$theta = substr(62,4);
    //$rho = substr(66,4);
    //$outbound_magnetic_course = (intval(substr($line,70,4))/10);
    //$route_distance_from = (intval(substr($line,74,4))/10);
    //$inbound_magnetic_course = (intval(substr($line,78,4))/10);
    //Padding/Spacing 82
    $this->minimumAltitude = (is_numeric(substr($line, 83, 5))) ? intval(substr($line, 83, 5)) : null;
    $this->minimumAltitudeRev = (is_numeric(substr($line, 88, 5))) ? intval(substr($line, 88, 5)) : null;
    $this->maximumAltitude = (is_numeric(substr($line, 93, 5))) ? intval(substr($line, 93, 5)) : null;
    //$fix_radius_transition_indicator = intval(substr($line,98,3))/10;
    //$vertical_scale_factor = substr($line,101,3);
    //$rvsm_minimum_level = substr($line,104,3);
    //$vsf_rvsm_max_level = substr($line,107,3);
    //Reserved 110-113
    //Padding/Spacing 114-122
    //$file_record_no = substr($line,123,5);
    //$cycle_data = substr($line,128,4);
  }

  public function toDBArray(string $airacId)
  {
    $result = array(
      'route_id'                    => $this->routeId,
      'fix_id'                      => $this->fixId,
      'seq_no'                      => $this->sequenceNumber,
      'waypoint_desc'               => $this->waypointDescriptionCode,
      'route_type'                  => $this->routeType,
      'level'                       => $this->level,
      'min_alt'                     => $this->minimumAltitude,
      'min_alt_rev'                 => $this->minimumAltitudeRev,
      'max_alt'                     => $this->maximumAltitude,
      'region'                      => $this->icaoRegion,
      'AIRAC'                       => $airacId
    );
    return $result;
  }
}
