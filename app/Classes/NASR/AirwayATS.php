<?php

namespace App\Classes\NASR;

class AirwayATS
{
  public $atsId;
  public $pointId;
  public $atsPointSeq;
  public $atsGap;
  public $ptpMea;
  public $ptpMeaRev;
  public $ptpMaa;
  public $pointArtcc;

  public function __construct()
  {
    $this->atsId = null;
    $this->pointId = null;
    $this->atsPointSeq = null;
    $this->atsGap = null;
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
    //$recordType = trim(substr($line,0,4)); // ATS1 // IGNORED
    //$atsDesignation = trim(substr($line,4,2)); // ATS Airway Designation // IGNORED
    //AT - Atlantic, BF - Bahamas, PA - Pacific, PR - Puerto Rico
    $atsId = trim(substr($line, 6, 12)); // ATS Airway ID
    //$rnav = trim(substr($line,18,1)); // RNAV indicator // IGNORED
    //R - RNAV, BLANK - Non-RNAV
    $atsPointSeq = trim(substr($line, 19, 6)); // AWY point sequence number
    //$effectiveDate = trim(substr($line,25,10)); // Effective Date (MM/DD/YYYY) // IGNORED
    //$trackAngleOutboundRnav = trim(substr($line,35,7)); // Track Angle Outbound - RNAV (NNN/NNN) // IGNORED
    //$changeoverPointDistRnav = intval(trim(substr($line,42,5))); // Distance to changeover point - RNAV (NNNNN) // IGNORED
    //$trackAngleInboundRnav = trim(substr($line,47,7)); // Track Angle Inbound - RNAV (NNN/NNN) // IGNORED
    //$pointDistRnav = floatval(trim(substr($line,54,6))); // Distance to next point in NM - RNAV (NNN.NN) // IGNORED
    //$bearing = trim(substr($line,60,6)); // RESERVED/INACTIVE // Bearing (NNN.NN) // IGNORED
    //$magCourse = trim(substr($line,66,6)); // Mag Course (NNN.NN) // IGNORED
    //$magCourseRev = trim(substr($line,72,6)); // Mag Course Opposite Direction (NNN.NN) // IGNORED
    //$pointDist = trim(substr($line,78,6)); // Distance to next point in NM (NNN.NN) // IGNORED
    $ptpMea = intval(trim(substr($line, 84, 5))); // Point to Point MEA (NNNNN)
    //$ptpMeaDir = trim(substr($line,89,7)); // Point to Point MEA Direction (AAAAAAA)
    $ptpMeaRev = intval(trim(substr($line, 96, 5))); // Point to Point MEA Opposite Direction (NNNNN)
    //$ptpMeaDirRev = trim(substr($line,101,7)); // Point to Point MEA Direction Opposite Direction (AAAAAAA) // IGNORED
    $ptpMaa = intval(trim(substr($line, 108, 5))); // Point to Point MAA (NNNNN)
    //$ptpMoca = intval(trim(substr($line,113,5))); // Point to Point MEA (NNNNN) // IGNORED
    $atsGap = trim(substr($line, 118, 1)); // Airway Gap Flag ('X' entered when airway discontinued)
    //$changeoverPointDist = intval(trim(substr($line,119,3))); // Distance to changeover point (NNN) // IGNORED
    //NOTE: THIS FIELD CONTAINS THE DISTANCE IN NAUTICAL MILES OF THE CHANGEOVER POINT BETWEEN
    //THIS NAVAID FACILITY(ATS2 RECORD) AND THE NEXT NAVAID FACILITY(ATS3 RECORD) WHEN THE CHANGE-
    //OVER POINT IS MORE THAN ONE MILE FROM HALF-WAY POINT.
    //$mca = intval(trim(substr($line,122,5))); // MCA (NNNNN) // IGNORED
    //$direction = trim(substr($line,127,7)); // Direction of MCA (AAAAAAA) // IGNORED
    //$mcaRev = intval(trim(substr($line,134,5))); // MCA Opposite Direction (NNNNN) // IGNORED
    //$directionRev = trim(substr($line,139,7)); // Direction of MCA Opposite Direction (AAAAAAA) // IGNORED
    //$gapInSignalCoverage = trim(substr($line,146,1)); // Gap in signal coverage ('Y'/BLANK) // IGNORED
    //$usAirspaceOnly = trim(substr($line,147,1)); // US Airspace only ('Y'/BLANK) // IGNORED
    //$navaidMagVar = trim(substr($line,148,5)); // Magnetic Variation at navaid (NNA) // IGNORED
    $pointArtcc = trim(substr($line, 153, 3)); // Point ARTCC ID
    //$toPoint = trim(substr($line,156,40)); // RESERVED/INACTIVE
    //$nextMeaPoint = trim(substr($line,196,50)); // RESERVED/INACTIVE
    //$ptpMeaGnss = intval(trim(substr($line,246,5))); // Point to Point MEA - GNSS (NNNNN) // IGNORED
    //$ptpMeaDirGnss = trim(substr($line,251,7)); // Point to Point MEA Direction - GNSS (AAAAAAA) // IGNORED
    //$ptpMeaRevGnss = intval(trim(substr($line,258,5))); // Point to Point MEA Opposite Direction - GNSS (NNNNN) // IGNORED
    //$ptpMeaDirRevGnss = trim(substr($line,263,7)); // Point to Point MEA Direction Opposite Direction - GNSS (AAAAAAA) // IGNORED
    //$mcaPoint = trim(substr($line,270,50)); // MCA Point // IGNORED
    //$ptpMeaDdi = intval(trim(substr($line,320,5))); // Point to Point MEA - DME/DME/IRU (NNNNN) // IGNORED
    //$ptpMeaDirDdi = trim(substr($line,325,6)); // Point to Point MEA Direction - DME/DME/IRU (AAAAAA) // IGNORED
    //$ptpMeaRevDdi = intval(trim(substr($line,331,5))); // Point to Point MEA Opposite Direction - DME/DME/IRU (NNNNN) // IGNORED
    //$ptpMeaDirRevDdi = trim(substr($line,336,6)); // Point to Point MEA Direction Opposite Direction - DME/DME/IRU (AAAAAA) // IGNORED
    //$dogleg = trim(substr($line,342,1)); //Turn point not at Navaid? ('Y'/BLANK) // IGNORED
    //$rnp = floatval(trim(substr($line,343,5))); //RNP in NM (NN.NN) // IGNORED
    //$seq = intval(trim(substr($line,348,7))); // Record Sort Seq Number // IGNORED HERE (Duplicate of ATS1)

    // ASSIGNMENTS TO AWY OBJECT
    $this->atsId = $atsId;
    $this->atsPointSeq = intval($atsPointSeq);
    $this->ptpMea = ($ptpMea == '') ? null : intval($ptpMea);
    $this->ptpMeaRev = ($ptpMeaRev == '') ? null : intval($ptpMeaRev);
    $this->ptpMaa = ($ptpMaa == '') ? null : intval($ptpMaa);
    $atsGap = ($atsGap == '') ? 0 : 1;
    $this->atsGap = $atsGap;
    $pointArtcc = ($pointArtcc == '') ? null : $pointArtcc;
    $this->pointArtcc = $pointArtcc;
  }

  public function fromString2(string $line)
  {
    //AIRWAY POINT DESCRIPTION
    //$recordType = trim(substr($line,0,4)); // ATS2 // IGNORED
    //$atsDesignation = trim(substr($line,4,2)); // ATS Airway Designation // IGNORED
    //$atsId = trim(substr($line,6,12)); // ATS Airway ID // IGNORED
    //$rnav = trim(substr($line,18,1)); // RNAV indicator // IGNORED
    //$atsType = trim(substr($line,19,1)); // Airway type // IGNORED
    //$atsPointSeq = intval(trim(substr($line,20,5))); // ATS point sequence number // IGNORED
    $pointName = trim(substr($line, 25, 40)); // Navaid/Fix Name
    //$pointType = trim(substr($line,65,25)); // Navaid/Fix Type // IGNORED
    $pointPubCat = trim(substr($line, 90, 15)); // Publication category - named fixes only
    //$pointState = trim(substr($line,105,2)); // Navaid/Fix State Code // IGNORED
    //$icaoRegion = trim(substr($line,107,2)); // ICAO Region (Fixes only) // IGNORED
    //$pointLat = trim(substr($line,109,14)); // Navaid/Point Lat NN-NN-NN.NNA // IGNORED
    //$pointLon = trim(substr($line,123,14)); // Navaid/Point Lon NN-NN-NN.NNA // IGNORED
    //$mra = intval(trim(substr($line,137,5))); // MRA (NNNNN) // IGNORED
    $navaidId = trim(substr($line, 142, 4)); // Navaid Identifier (AAAA)
    //$fromPoint = trim(substr($line,146,57)); // RESERVED/INACTIVE
    // RECORD SPACING FROM 203 for 145
    //$seq = intval(trim(substr($line,348,7))); // Record Sort Seq Number // IGNORED

    // ASSIGNMENTS TO AIRWAY OBJECT
    $this->pointId = ($pointPubCat == 'FIX') ? $pointName : $navaidId;
  }

  public function fromString3(string $line)
  {
    //CHANGEOVER TO POINT DESCRIPTION
    //$recordType = trim(substr($line,0,4)); // ATS3 // IGNORED
    //$atsDesignation = trim(substr($line,4,2)); // ATS Airway Designation // IGNORED
    //$atsId = trim(substr($line,6,12)); // ATS Airway ID // IGNORED
    //$rnav = trim(substr($line,18,1)); // RNAV indicator // IGNORED
    //$atsType = trim(substr($line,19,1)); // Airway type // IGNORED
    //$atsPointSeq = intval(trim(substr($line,20,5))); // ATS point sequence number // IGNORED
    //$pointName = trim(substr($line,25,30)); // Navaid/Fix Name // IGNORED
    //$pointType = trim(substr($line,55,25)); // Navaid/Fix Type // IGNORED
    //$pointState = trim(substr($line,80,2)); // Navaid/Fix State Code // IGNORED
    //$pointLat = trim(substr($line,82,14)); // Navaid/Point Lat NN-NN-NN.NNA
    //$pointLon = trim(substr($line,96,14)); // Navaid/Point Lon NN-NN-NN.NNA
    // RECORD SPACING FROM 110 for 238
    //$seq = trim(substr($line,348,7)); // Record Sort Seq Number // IGNORED

    // ASSIGNMENTS TO AIRWAY OBJECT
  }

  public function fromString4(string $line)
  {
    //AIRWAY POINT REMARKS TEXT
    //$recordType = trim(substr($line,0,4)); // ATS4 // IGNORED
    //$atsDesignation = trim(substr($line,4,2)); // ATS Airway Designation // IGNORED
    //$atsId = trim(substr($line,6,12)); // ATS Airway ID // IGNORED
    //$rnav = trim(substr($line,18,1)); // RNAV indicator // IGNORED
    //$atsType = trim(substr($line,19,1)); // Airway type // IGNORED
    //$atsPointSeq = intval(trim(substr($line,20,5))); // ATS point sequence number // IGNORED
    //$remarks = trim(substr($line,25,200)); // Remarks
    // RECORD SPACING FROM 225 for 123
    //$seq = trim(substr($line,348,7)); // Record Sort Seq Number // IGNORED

    // ASSIGNMENTS TO AIRWAY OBJECT
  }

  public function fromString5(string $line)
  {
    //CHANGEOVER POINT EXCEPTION TEXT
    //$recordType = trim(substr($line,0,4)); // ATS5 // IGNORED
    //$atsDesignation = trim(substr($line,4,2)); // ATS Airway Designation // IGNORED
    //$atsId = trim(substr($line,6,12)); // ATS Airway ID // IGNORED
    //$rnav = trim(substr($line,18,1)); // RNAV indicator // IGNORED
    //$atsType = trim(substr($line,19,1)); // Airway type // IGNORED
    //$atsPointSeq = intval(trim(substr($line,20,5))); // ATS point sequence number // IGNORED
    //$remarks = trim(substr($line,25,200)); //Remarks
    // RECORD SPACING FROM 225 for 123
    //$seq = trim(substr($line,348,7)); // Record Sort Seq Number // IGNORED

    // ASSIGNMENTS TO AIRWAY OBJECT
  }

  public function fromString6(string $line)
  {
    //AIRWAY REMARK TEXT
    //$recordType = trim(substr($line,0,4)); // RMK // IGNORED
    //$atsDesignation = trim(substr($line,4,2)); // ATS Airway Designation // IGNORED
    //$atsId = trim(substr($line,6,12)); // ATS Airway ID // IGNORED
    //$rnav = trim(substr($line,18,1)); // RNAV indicator // IGNORED
    //$atsType = trim(substr($line,19,1)); // Airway type // IGNORED
    //$atsPointSeq = intval(trim(substr($line,20,5))); // ATS point sequence number // IGNORED
    //$rmkRef = trim(substr($line,23,5)); // Remark reference: BLANK - General, DESIG - Airway Designation, TYPE - Airway Type, RNAV - RNAV Indicator
    //$remarks = trim(substr($line,28,200)); // Remarks
    // RECORD SPACING FROM 228 for 120
    //$seq = trim(substr($line,348,7)); // Record Sort Seq Number // IGNORED

    // ASSIGNMENTS TO AIRWAY OBJECT
  }

  public function fromModel(object $dbObject)
  {
    $this->atsId = $dbObject->airway_id;
    $this->pointId = $dbObject->point_id;
    $this->atsPointSeq = $dbObject->seq_no;
    $this->atsGap = $dbObject->route_end;
    $this->ptpMea = $dbObject->min_alt;
    $this->ptpMeaRev = $dbObject->min_alt_rev;
    $this->ptpMaa = $dbObject->max_alt;
    $this->pointArtcc = $dbObject->artcc_id;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'airway_id'    => $this->atsId,
      'point_id'    => $this->pointId,
      'seq_no'      => $this->atsPointSeq,
      'route_end'   => $this->atsGap,
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
