<?php

namespace App\Classes\NASR;

class Airway
{
  public $awyDesignation;
  public $pointId;
  public $awyPointSeq;
  public $awyGap;
  public $ptpMea;
  public $ptpMeaRev;
  public $ptpMaa;
  public $pointArtcc;

  public function __construct()
  {
    $this->awyDesignation = null;
    $this->pointId = null;
    $this->awyPointSeq = null;
    $this->awyGap = null;
    $this->ptpMea = null;
    $this->ptpMeaRev = null;
    $this->ptpMaa = null;
    $this->pointArtcc = null;
  }

  public function fromString1(string $line)
  {
    /*
      This function is a mess, but the commented lines
      have been left in to show the FAA file definition
      in case they should be useful to anyone.
    */
    //$recordType = trim(substr($line,0,4)); // AWY1 // IGNORED
    $awyDesignation = trim(substr($line, 4, 5)); // Airway Designation
    //J = JET, V = VOR, A = AMBER, B = BLUE, G = GREEN, R = RED, Q = GPS HI, T = GPS LO
    //R IN THE LAST POSITION INDICATES NAVAID BASED RNAV ROUTE
    //$awyType = trim(substr($line,9,1)); // Airway type // IGNORED
    //A - ALASKA, H - HAWAII VOR, BLANK - VOR FEDERAL AIRWAY
    $awyPointSeq = trim(substr($line, 10, 5)); // AWY point sequence number
    //$effectiveDate = trim(substr($line,15,10)); // Effective Date (MM/DD/YYYY) // IGNORED
    //$trackAngleOutboundRnav = trim(substr($line,25,7)); // Track Angle Outbound - RNAV (NNN/NNN) // IGNORED
    //$changeoverPointDistRnav = trim(substr($line,32,5)); // Distance to changeover point - RNAV (NNNNN) // IGNORED
    //$trackAngleInboundRnav = trim(substr($line,37,7)); // Track Angle Inbound - RNAV (NNN/NNN) // IGNORED
    //$pointDistRnav = trim(substr($line,44,6)); // Distance to next point in NM - RNAV (NNN.NN) // IGNORED
    //$bearing = trim(substr($line,50,6)); // RESERVED/INACTIVE // Bearing (NNN.NN) // IGNORED
    //$magCourse = trim(substr($line,56,6)); // Mag Course (NNN.NN) // IGNORED
    //$magCourseRev = trim(substr($line,62,6)); // Mag Course Opposite Direction (NNN.NN) // IGNORED
    //$pointDist = trim(substr($line,68,6)); // Distance to next point in NM (NNN.NN) // IGNORED
    $ptpMea = trim(substr($line, 74, 5)); // Point to Point MEA (NNNNN)
    //$ptpMeaDir = trim(substr($line,79,6)); // Point to Point MEA Direction (AAAAAAA) // IGNORED
    $ptpMeaRev = trim(substr($line, 85, 5)); // Point to Point MEA Opposite Direction (NNNNN)
    //$ptpMeaDirRev = trim(substr($line,91,6)); // Point to Point MEA Direction Opposite Direction (AAAAAAA) // IGNORED
    $ptpMaa = trim(substr($line, 96, 5)); // Point to Point MAA (NNNNN)
    //$ptpMoca = trim(substr($line,101,5)); // Point to Point MEA (NNNNN) // IGNORED
    $awyGap = trim(substr($line, 106, 1)); // Airway Gap Flag ('X' entered when airway discontinued)
    //$changeoverPointDist = trim(substr($line,107,3)); // Distance to changeover point (NNN) // IGNORED
    //NOTE: THIS FIELD CONTAINS THE DISTANCE IN NAUTICAL MILES OF THE CHANGEOVER POINT BETWEEN
    //THIS NAVAID FACILITY(ATS2 RECORD) AND THE NEXT NAVAID FACILITY(ATS3 RECORD) WHEN THE CHANGE-
    //OVER POINT IS MORE THAN ONE MILE FROM HALF-WAY POINT.
    //$mca = intval(trim(substr($line,110,5))); // MCA (NNNNN) // IGNORED
    //$direction = trim(substr($line,115,7)); // Direction of MCA (AAAAAAA) // IGNORED
    //$mcaRev = intval(trim(substr($line,122,5))); // MCA Opposite Direction (NNNNN) // IGNORED
    //$directionRev = trim(substr($line,127,7)); // Direction of MCA Opposite Direction (AAAAAAA) // IGNORED
    //$gapInSignalCoverage = trim(substr($line,134,1)); // Gap in signal coverage ('Y'/BLANK) // IGNORED
    //$usAirspaceOnly = trim(substr($line,135,1)); // US Airspace only ('Y'/BLANK) // IGNORED
    //$navaidMagVar = trim(substr($line,136,5)); // Magnetic Variation at navaid (NNA) // IGNORED
    $pointArtcc = trim(substr($line, 141, 3)); // Point ARTCC ID
    //$toPoint = trim(substr($line,144,33)); // RESERVED/INACTIVE
    //$nextMeaPoint = trim(substr($line,177,40)); // RESERVED/INACTIVE
    //$ptpMeaGnss = trim(substr($line,217,5)); // Point to Point MEA - GNSS (NNNNN) // IGNORED
    //$ptpMeaDirGnss = trim(substr($line,222,6)); // Point to Point MEA Direction - GNSS (AAAAAAA) // IGNORED
    //$ptpMeaRevGnss = trim(substr($line,228,5)); // Point to Point MEA Opposite Direction - GNSS (NNNNN) // IGNORED
    //$ptpMeaDirRevGnss = trim(substr($line,233,6)); // Point to Point MEA Direction Opposite Direction - GNSS (AAAAAAA) // IGNORED
    //$mcaPoint = trim(substr($line,239,40)); // MCA Point // IGNORED
    //$ptpMeaDdi = trim(substr($line,279,5)); // Point to Point MEA - DME/DME/IRU (NNNNN) // IGNORED
    //$ptpMeaDirDdi = trim(substr($line,284,6)); // Point to Point MEA Direction - DME/DME/IRU (AAAAAA) // IGNORED
    //$ptpMeaRevDdi = trim(substr($line,290,5)); // Point to Point MEA Opposite Direction - DME/DME/IRU (NNNNN) // IGNORED
    //$ptpMeaDirRevDdi = trim(substr($line,295,6)); // Point to Point MEA Direction Opposite Direction - DME/DME/IRU (AAAAAA) // IGNORED
    //$dogleg = trim(substr($line,301,1)); //Turn point not at Navaid? ('Y'/BLANK) // IGNORED
    //GPS/RNAV ROUTES [Q, T, TK] WILL HAVE DOGLEG=Y AT FIRST POINT, END POINT, AND ALL TURN POINTS INBETWEEN
    //$rnp = trim(substr($line,302,5)); //RNP in NM (NN.NN) // IGNORED
    //$seq = trim(substr($line,307,7)); // Record Sort Seq Number

    // ASSIGNMENTS TO AWY OBJECT
    $this->awyDesignation = $awyDesignation;
    $this->awyPointSeq = intval($awyPointSeq);
    $this->ptpMea = ($ptpMea == '') ? null : intval($ptpMea);
    $this->ptpMeaRev = ($ptpMeaRev == '') ? null : intval($ptpMeaRev);
    $this->ptpMaa = ($ptpMaa == '') ? null : intval($ptpMaa);
    $awyGap = ($awyGap == '') ? 0 : 1;
    $this->awyGap = $awyGap;
    $pointArtcc = ($pointArtcc == '') ? null : $pointArtcc;
    $this->pointArtcc = $pointArtcc;
  }

  public function fromString2(string $line)
  {
    //AIRWAY POINT DESCRIPTION
    //$recordType = trim(substr($line,0,4)); // AWY2 // IGNORED
    //$awyDesignation = trim(substr($line,4,5)); // Airway Designation // IGNORED
    //$awyType = trim(substr($line,9,1)); // Airway Type // IGNORED
    //$awyPointSeq = trim(substr($line,10,5)); // Airway point sequence number // IGNORED
    $pointName = trim(substr($line, 15, 30)); // Navaid/Fix Name
    //$pointType = trim(substr($line,45,19)); // Navaid/Fix Type // IGNORED
    $pointPubCat = trim(substr($line, 64, 15)); // Publication category - named fixes only
    //$pointState = trim(substr($line,79,2)); // Navaid/Fix State Code // IGNORED
    //$icaoRegion = trim(substr($line,81,2)); // ICAO Region (Fixes only) // IGNORED
    //$pointLat = trim(substr($line,83,14)); // Navaid/Point Lat NN-NN-NN.NNA // IGNORED
    //$pointLon = trim(substr($line,97,14)); // Navaid/Point Lon NN-NN-NN.NNA // IGNORED
    //$mra = trim(substr($line,111,5)); // MRA (NNNNN) // IGNORED
    $navaidId = trim(substr($line, 116, 4)); // Navaid Identifier (AAAA)
    //$fromPoint = trim(substr($line,120,40)); // RESERVED/INACTIVE
    // RECORD SPACING FROM 160 for 147
    //$seq = trim(substr($line,307,7)); // Record Sort Seq Number

    // ASSIGNMENTS TO AIRWAY OBJECT
    $this->pointId = ($pointPubCat == 'FIX') ? $pointName : $navaidId;
  }

  public function fromString3(string $line)
  {
    //CHANGEOVER TO POINT DESCRIPTION
    //$recordType = trim(substr($line,0,4)); // AWY3 // IGNORED
    //$awyDesignation = trim(substr($line,4,5)); // Airway Designation // IGNORED
    //$awyType = trim(substr($line,9,1)); // Airway Type // IGNORED
    //$awyPointSeq = trim(substr($line,10,5)); // Airway point sequence number // IGNORED
    //$pointName = trim(substr($line,15,30)); // Navaid/Fix Name // IGNORED
    //$pointType = trim(substr($line,45,19)); // Navaid/Fix Type // IGNORED
    //$pointState = trim(substr($line,64,2)); // Navaid/Fix State Code // IGNORED
    //$pointLat = trim(substr($line, 66, 14)); // Navaid/Point Lat NN-NN-NN.NNA
    //$pointLon = trim(substr($line, 80, 14)); // Navaid/Point Lon NN-NN-NN.NNA
    // RECORD SPACING FROM 94 for 213
    //$seq = trim(substr($line, 307, 7)); // Record Sort Seq Number

    // ASSIGNMENTS TO AIRWAY OBJECT
  }

  public function fromString4(string $line)
  {
    //AIRWAY POINT REMARKS TEXT
    //$recordType = trim(substr($line,0,4)); // AWY4 // IGNORED
    //$awyDesignation = trim(substr($line,4,5)); // Airway Designation // IGNORED
    //$awyType = trim(substr($line,9,1)); // Airway Type // IGNORED
    //$awyPointSeq = trim(substr($line,10,5)); // Airway point sequence number // IGNORED
    //$remarks = trim(substr($line, 15, 202)); // Navaid/Point Lon NN-NN-NN.NNA
    // RECORD SPACING FROM 217 for 90
    //$seq = trim(substr($line, 307, 7)); // Record Sort Seq Number

    // ASSIGNMENTS TO AIRWAY OBJECT
  }

  public function fromString5(string $line)
  {
    //CHANGEOVER POINT EXCEPTION TEXT
    //$recordType = trim(substr($line,0,4)); // AWY5 // IGNORED
    //$awyDesignation = trim(substr($line,4,5)); // Airway Designation // IGNORED
    //$awyType = trim(substr($line,9,1)); // Airway Type // IGNORED
    //$awyPointSeq = trim(substr($line,10,5)); // Airway point sequence number // IGNORED
    //$remarks = trim(substr($line, 15, 202)); //Remarks
    // RECORD SPACING FROM 217 for 90
    //$seq = trim(substr($line, 307, 7)); // Record Sort Seq Number

    // ASSIGNMENTS TO AIRWAY OBJECT
  }

  public function fromString6(string $line)
  {
    //AIRWAY REMARK TEXT
    //$recordType = trim(substr($line,0,4)); // AWY5 // IGNORED
    //$awyDesignation = trim(substr($line,4,5)); // Airway Designation // IGNORED
    //$awyType = trim(substr($line,9,1)); // Airway Type // IGNORED
    //$rmkSeq = trim(substr($line, 10, 3)); // Remark sequence number
    //$rmkRef = trim(substr($line, 13, 6)); // Remark reference: BLANK - General, DESIG - Airway Designation, TYPE - Airway Type, RNAV - RNAV Indicator
    //$remarks = trim(substr($line, 19, 220)); // Remarks
    // RECORD SPACING FROM 239 for 68
    //$seq = trim(substr($line, 307, 7)); // Record Sort Seq Number

    // ASSIGNMENTS TO AIRWAY OBJECT
  }

  public function fromModel(object $dbObject)
  {
    $this->awyDesignation = $dbObject->airway_id;
    $this->pointId = $dbObject->point_id;
    $this->awyPointSeq = $dbObject->seq_no;
    $this->awyGap = $dbObject->route_end;
    $this->ptpMea = $dbObject->min_alt;
    $this->ptpMeaRev = $dbObject->min_alt_rev;
    $this->ptpMaa = $dbObject->max_alt;
    $this->pointArtcc = $dbObject->artcc_id;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'airway_id'   => $this->awyDesignation,
      'point_id'    => $this->pointId,
      'seq_no'      => $this->awyPointSeq,
      'route_end'   => $this->awyGap,
      'min_alt'     => $this->ptpMea,
      'min_alt_rev' => $this->ptpMeaRev,
      'max_alt'     => $this->ptpMaa,
      'artcc_id'    => $this->pointArtcc,
      'cycle_id'    => $airacId,
      'next'        => $next
    );
    return $result;
  }
}
