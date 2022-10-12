<?php

namespace App\Classes\NASR;

use App\Classes\Helpers\TextHelper;

class Navaid
{
  public $navId;
  public $navType;
  public $navName;
  public $regionCode;
  public $navClass;
  public $artccHi;
  public $artccLo;
  public $navLat;
  public $navLon;
  public $tacLat;
  public $tacLon;
  public $elev;
  public $magVar;
  public $frequency;
  public $trueBearing;
  public $vorSVol;
  public $dmeSVol;
  public $loUsedInHi;
  public $navStatus;
  public $isPitch;
  public $isCatch;
  public $isSuaAtcaa;

  public function __construct()
  {
    $this->navId = null;
    $this->navType = null;
    $this->navName = null;
    $this->regionCode = null;
    $this->navClass = null;
    $this->artccHi = null;
    $this->artccLo = null;
    $this->navLat = null;
    $this->navLon = null;
    $this->tacLat = null;
    $this->tacLon = null;
    $this->elev = null;
    $this->magVar = null;
    $this->frequency = null;
    $this->trueBearing = null;
    $this->vorSVol = null;
    $this->dmeSVol = null;
    $this->loUsedInHi = null;
    $this->navStatus = null;
    $this->isPitch = null;
    $this->isCatch = null;
    $this->isSuaAtcaa = null;
  }

  public function fromString1(string $line)
  {
    $texthelper = new TextHelper;
    /*
      This function is a mess, but the commented lines
      have been left in to show the FAA file definition
      in case they should be useful to anyone.
    */
    //$recordType = trim(substr($line,0,4)); // NAV1 // IGNORED
    $navId = trim(substr($line, 4, 4)); // Navaid Facility ID
    $navType = trim(substr($line, 8, 20)); // Navaid facility type:
    //VORTAC, VOR/DME, FAN MARKER, CONSOLAN, MARINE NDB, MARINE NDB/DME
    //VOT, NDB, NDB/DME, TACAN, UHF/NDB, VOR, DME
    //$nasId = trim(substr($line,28,4)); // Official Navaid Identifier // IGNORED
    //$effectiveDate = trim(substr($line,32,10)); // Effective date (MM/DD/YYYY) // IGNORED
    $navName = trim(substr($line, 42, 30)); // Name of navaid
    //$navCity = trim(substr($line,72,40)); // City associated with navaid // IGNORED
    //$navState = trim(substr($line,112,30)); // State name where associated city is located // IGNORED
    //$navStateId = trim(substr($line,142,2)); // State post office code // IGNORED
    $regionCode = trim(substr($line, 144, 3)); // FAA Region Code
    //$country = trim(substr($line,147,30)); // Country associated with navaid // IGNORED
    //$countryId = trim(substr($line,177,2)); // Country post code (if not US) // IGNORED
    //$navOwner = trim(substr($line,179,50)); // Navaid Owner (ex. US NAVY) // IGNORED
    //$navOperator = trim(substr($line,229,50)); // Navaid Operator (ex. US NAVY) // IGNORED
    //$isCommonUsage = trim(substr($line,279,1)); // Common system usage (Y/N) // IGNORED
    //$isPublicUse = trim(substr($line,280,1)); // Public use (Y/N) // IGNORED
    $navClass = trim(substr($line, 281, 11)); // Class of Navaid:
    //H - high, L - low, T - terminal
    //AB - AUTOMATIC WEATHER BROADCAST, DME - DISTANCE MEASURING EQUIPMENT
    //DME(Y) (TACAN COMPATIBLE), H - NDB (50-2000W), HH - NDB (>2000W),
    //H-SAB - NDB with TWEB, LMM - COMPASS LOCATOR AT MIDDLE MARKER, LOM - COMPASS LOCATOR AT OUTER MARKER
    //MH - NDB (<50W), S - SIMULTANEOUS RANGE HOMING SIGNAL AND/OR VOICE, SABH - NDB VFR ONLY
    //TACAN - TACAN, VOR - VOR, VOR/DME - VOR with DME, VORTAC - VOR with TACAN,
    //W - No voice on radio frequency, Z - VHF location marker at LF radio facility
    //MULTIPLE CLASS CODE TYPES MAY BE SEPARATED BY A / (SLANT) OR A - (DASH)

    //CANADA CODES:
    //A - ATIS, C - TWEB, B - SCHEDULED WEATHER BROADCAST, T - FSS OR OTHER ATC AGENCY (EXCEPT PAR),
    //P - PRECISION APPROACH RADAR BACK-UP FREQUENCY,L - NDB (<50W), M - NDB (50-2000W),
    //H - NDB (>2000W), Z - STATION LOCATION MARKER OR FAN MARKER
    //$opHours = trim(substr($line,292,11)); // Hours of operation // IGNORED
    $artccHi = trim(substr($line, 303, 4)); // ARTCC where Navaid resides (high altitude)
    //$artccHiName = trim(substr($line,307,30)); // ARTCC Name where Navaid resides (high altitude) // IGNORED
    $artccLo = trim(substr($line, 337, 4)); // ARTCC where Navaid resides (low altitude)
    //$artccLoName = trim(substr($line,341,30)); // ARTCC Name where Navaid resides (low altitude) // IGNORED
    $navPointLatDMS = trim(substr($line, 371, 14)); // LATITUDE DD-MM-SS.SSSH (Where H is N/S) // IGNORED
    //$navPointLatSec = trim(substr($line,385,11)); //  SSSSSS.SSSH (Where H is N/S)
    $navPointLonDMS = trim(substr($line, 396, 14)); // LONGITUDE DDD-MM-SS.SSSH (Where H is E/W) // IGNORED
    //$navPointLonSec = trim(substr($line,410,11)); //  SSSSSS.SSSH (Where H is E/W)
    //$surveyAccuracy = trim(substr($line,421,1)); // Lat/Lon survey accuracy: // IGNORED
    //0 - UNKNOWN, 1 - DEGREE, 2 - 10 MINUTES, 3 - 1 MINUTE, 4 - 10 SECONDS, 5 - 1 SECOND OR BETTER, 6 - NOS, 7 - 3RD ORDER TRIANGULATION
    $tacPointLatDMS = trim(substr($line, 422, 14)); // TACAN (when not co-located) LATITUDE DD-MM-SS.SSSH (Where H is N/S) // IGNORED
    //$tacPointLatSec = trim(substr($line,436,11)); // TACAN (when not co-located) SSSSSS.SSSH (Where H is N/S)
    $tacPointLonDMS = trim(substr($line, 447, 14)); // TACAN (when not co-located) LONGITUDE DDD-MM-SS.SSSH (Where H is E/W) // IGNORED
    //$tacPointLonSec = trim(substr($line,461,11)); // TACAN (when not co-located) SSSSSS.SSSH (Where H is E/W)
    $elev = trim(substr($line, 472, 7)); // Elevation in tenths of a foot MSL
    $magVar = trim(substr($line, 479, 5)); // Magnetic variation (08W)
    // DME, VOT and FM navaids do not have magvar - reject any value in this field in those cases
    //$magVarYear = trim(substr($line,484,4)); // Magnetic variation assessment year // IGNORED
    //$simulVoice = trim(substr($line,488,3)); // Simultaneous voice feature (Y/N/NULL) // IGNORED
    //$power = intval(trim(substr($line,491,4))); // Power output (watts) // IGNORED
    //$voiceId = trim(substr($line,495,3)); // Voice identification feature (Y/N/NULL) // IGNORED
    //$monitoring = trim(substr($line,498,1)); // Monitoring category: // IGNORED
    //1-INTERNAL MONITORING PLUS A STATUS INDICATOR INSTALLED AT CONTROL POINT. (REVERTS TO A TEMPORARY CATEGORY 3 STATUS WHEN THE CONTROL POINT IS NOT MANNED.)
    //2-INTERNAL MONITORING WITH STATUS INDICATOR AT CONTROL POINT INOPERATIVE BUT PILOT REPORTS INDICATE FACILITY IS OPERATING NORMALLY. (THIS IS A TEMPORARY SITUATION THAT REQUIRES NO PROCEDURAL ACTION.)
    //3-INTERNAL MONITORING ONLY. STATUS INDICATOR NON INSTALLED AT CONTROL POINT.
    //4-INTERNAL MONITOR NOT INSTALLED. REMOTE STATUS INDICATOR PROVIDED AT CONTROL POINT. THIS CATEGORY IS APPLICABLE ONLY TO NON-DIRECTIONAL  BEACONS.
    //$radioVoiceCall = trim(substr($line,499,30)); // Radio voice call (name)
    //$tacanChannel = trim(substr($line,529,4)); // TACAN Channel (ex. 51X) // IGNORED
    $frequency = trim(substr($line, 533, 6)); // Frequency navaid transmits on (except tacan)
    //$morseId = trim(substr($line,539,24)); // Fan marker/marine radio beacon ID (dot/dash sequence) // IGNORED
    //$fanType = trim(substr($line,563,10)); // Fan marker type: bone or elliptical // IGNORED
    $trueBearing = trim(substr($line, 573, 3)); // True bearing of major axis of fan marker (whole degrees)
    $vorSVol = trim(substr($line, 576, 2)); // VOR service volume (H, L, T, VH, VL)
    $dmeSVol = trim(substr($line, 578, 2)); // DME service volume (H, L, T, DH, DL)
    $loUsedInHi = trim(substr($line, 580, 3)); // Low altitude facility used in high structure (Y/N/NULL)
    //$zMarkerAvail = trim(substr($line,583,3)); // Navaid Z marker available (Y/N/NULL) // IGNORED
    //$twebHours = trim(substr($line,586,9)); // TWEB hours (ex. 0500-2200) // IGNORED
    //$twebPhone = trim(substr($line,595,20)); // TWEB phone number // IGNORED
    //$assocFssId = trim(substr($line,615,4)); // Associated FSS Ident // IGNORED
    //$assocFssName = trim(substr($line,619,30)); // Associated FSS Name // IGNORED
    //$assocFssHours = trim(substr($line,649,100)); // Associated FSS Hours // IGNORED
    //$notamCode = trim(substr($line,749,4)); // NOTAM code (ident) // IGNORED
    //$quadrantId = trim(substr($line,753,16)); // Quadrant ID and range leg bearing (LFR only - ex. 151N190A311N036A) // IGNORED
    $navStatus = trim(substr($line, 769, 30)); // Navaid status (operational/decommissioned and VFR/IFR)
    $isPitch = trim(substr($line, 799, 1)); // Is a pitch point: Y/N
    $isCatch = trim(substr($line, 800, 1)); // Is a catch point: Y/N
    $isSuaAtcaa = trim(substr($line, 801, 1)); // Is SUA/ATCAA: Y/N
    //$hasRestriction = trim(substr($line,802,1)); // Navaid has restriction (Y/N/NULL) // IGNORED
    //$hasHiwas = trim(substr($line,803,1)); // Has HIWAS (Y/N/NULL) // IGNORED
    //$hasTwebRestriction = trim(substr($line,804,1)); // TWEB restriction (Y/N/NULL) // IGNORED

    // ASSIGNMENTS TO FIX OBJECT
    $this->navId = $navId;
    $this->navType = $navType;
    $this->navName = $navName;
    $this->regionCode = $regionCode;
    $this->navClass = $navClass;
    if ($navPointLatDMS == '') {
      $navLat = null;
      $navLon = null;
    } else {
      $coordNav = $texthelper->handleDMSFormatted($navPointLatDMS, 'DD-MM-SS.SSSA', $navPointLonDMS, "DDD-MM-SS.SSSA");
      $navLat = $coordNav->lat;
      $navLon = $coordNav->lon;
    }
    $this->navLat = $navLat;
    $this->navLon = $navLon;
    if ($tacPointLatDMS == '') {
      $tacLat = null;
      $tacLon = null;
    } else {
      $coordTac = $texthelper->handleDMSFormatted($tacPointLatDMS, 'DD-MM-SS.SSSA', $tacPointLonDMS, "DDD-MM-SS.SSSA");
      $tacLat = $coordTac->lat;
      $tacLon = $coordTac->lon;
    }
    $this->tacLat = $tacLat;
    $this->tacLon = $tacLon;
    $this->artccHi = ($artccHi == '') ? null : $artccHi;
    $this->artccLo = ($artccLo == '') ? null : $artccLo;
    $this->elev = ($elev == '') ? null : round(floatval($elev));
    $this->magVar = (substr($magVar, -1) == 'E') ? - (intval(substr($magVar, 0, -1))) : (intval(substr($magVar, 0, -1)));
    $this->frequency = ($frequency != '') ? (number_format($frequency, 3)) : null;
    $this->trueBearing = intval($trueBearing);
    $this->vorSVol = ($vorSVol == '') ? null : $vorSVol;
    $this->dmeSVol = ($dmeSVol == '') ? null : $dmeSVol;
    $this->loUsedInHi = ($loUsedInHi == 'Y') ? 1 : 0;
    $navString = '';
    $navStatusArray = explode(' ', $navStatus);
    foreach ($navStatusArray as $na) {
      switch ($na) {
          //In/Out of Commission
        case 'OPERATIONAL':
          $navString .= 'O';
          break;
        case 'DECOMMISSIONED':
          $navString .= 'D';
          break;
          //IFR/VFR/REST.
        case 'IFR':
          $navString .= 'I';
          break;
        case 'VFR':
          $navString .= 'V';
          break;
        case 'RESTRICTED':
          $navString .= 'R';
          break;
      }
    }
    $this->navStatus = $navString;
    $this->isPitch = ($isPitch == 'Y') ? 1 : 0;
    $this->isCatch = ($isCatch == 'Y') ? 1 : 0;
    $this->isSuaAtcaa = ($isSuaAtcaa == 'Y') ? 1 : 0;
  }

  public function fromString2(string $line)
  {
    //NAVAID REMARKS // UNUSED
    //$recordType = trim(substr($line,0,4)); // NAV2 // IGNORED
    //$navId = trim(substr($line,4,4)); // Navaid ID // IGNORED
    //$navType = trim(substr($line,8,20)); // Navaid facility type // IGNORED
    //$remarks = trim(substr($line,28,600)); // Navaid remarks (free form text)
    // RECORD SPACING FROM 628 for 177

    // ASSIGNMENTS TO NAVAID OBJECT
  }

  public function fromString3(string $line)
  {
    //COMPULSORY AND NON-COMPULSORY AIRSPACE FIXES ASSOCIATED WITH NAVAID // UNUSED
    //$recordType = trim(substr($line,0,4)); // NAV3 // IGNORED
    //$navId = trim(substr($line,4,4)); // Navaid ID // IGNORED
    //$navType = trim(substr($line,8,20)); // Navaid facility type // IGNORED
    //$fixNames = trim(substr($line,28,36)); // Fixes: FixName*FixState*IcaoRegion (ex. WHITE*TX*K1)
    //$additionalNames = trim(substr($line,64,720)); // Additional fixes (up to 21)
    // RECORD SPACING FROM 784 for 21

    // ASSIGNMENTS TO NAVAID OBJECT
  }

  public function fromString4(string $line)
  {
    //HOLDING PATTERNs (HPF) ASSOCIATED WITH NAVAID // UNUSED
    //$recordType = trim(substr($line,0,4)); // NAV4 // IGNORED
    //$navId = trim(substr($line,4,4)); // Navaid ID // IGNORED
    //$navType = trim(substr($line,8,20)); // Navaid Type // IGNORED
    //$holdNames = trim(substr($line,28,80)); // Names of holding patterns and state location (Name Type*State - ex. GEORGETOWN NDB*TX)
    //$pattern = trim(substr($line,108,3)); // Pattern (number) of the holding pattern
    //$additionalHolds = trim(substr($line,111,664)); // Additional holds (up to 8 - 80 for name and 3 for pattern for each)
    // RECORD SPACING FROM 775 for 30

    // ASSIGNMENTS TO NAVAID OBJECT
  }

  public function fromString5(string $line)
  {
    //FAN MARKERS ASSOCIATED WITH NAVAID // UNUSED
    //$recordType = trim(substr($line,0,4)); // NAV5 // IGNORED
    //$navId = trim(substr($line,4,4)); // Navaid ID // IGNORED
    //$navType = trim(substr($line,8,20)); // Navaid Type // IGNORED
    //$fanNames = trim(substr($line,28,80)); // Names of fan markers
    //$additionalFans = trim(substr($line,58,690)); // Additional fans (up to 24 fan markers)
    // RECORD SPACING FROM 748 for 57

    // ASSIGNMENTS TO NAVAID OBJECT
  }

  public function fromString6(string $line)
  {
    //VOR RECEIVER CHECKPOINTS ASSOCIATED WITH NAVAID // UNUSED
    //$recordType = trim(substr($line,0,4)); // NAV6 // IGNORED
    //$navId = trim(substr($line,4,4)); // Navaid ID // IGNORED
    //$navType = trim(substr($line,8,20)); // Navaid Type // IGNORED
    //$airGround = trim(substr($line,28,2)); // Air/Ground code (A - Air, G - Ground, G1 - Ground One)
    //$bearing = trim(substr($line,30,3)); // Bearing of checkpoint
    //$altitude = trim(substr($line,33,5)); // Altitude (when airGround is A)
    //$airportId = trim(substr($line,38,4)); // Airport ID
    //$stateCode = trim(substr($line,42,2)); // State code of location // IGNORED
    //$airDesc = trim(substr($line,44,75)); // Description associated with Air checkpoint
    //$gndDesc = trim(substr($line,119,75)); // Description associated with Ground checkpoint
    // RECORD SPACING FROM 194 for 611

    // ASSIGNMENTS TO NAVAID OBJECT
  }

  public function fromModel(object $dbObject)
  {
    $this->navId = $dbObject->nav_id;
    $this->navType = $dbObject->nav_type;
    $this->navName = $dbObject->name;
    $this->regionCode = $dbObject->faa_region;
    $this->navClass = $dbObject->nav_class;
    $this->navLat = $dbObject->nav_lat;
    $this->navLon = $dbObject->nav_lon;
    $this->tacLat = $dbObject->tac_lat;
    $this->tacLon = $dbObject->tac_lon;
    $this->artccHi = $dbObject->artcc_hi;
    $this->artccLo = $dbObject->artcc_lo;
    $this->elev = $dbObject->elev;
    $this->magVar = $dbObject->mag_var;
    $this->frequency = $dbObject->freq;
    $this->trueBearing = $dbObject->bear;
    $this->vorSVol = $dbObject->vor_vol;
    $this->dmeSVol = $dbObject->dme_vol;
    $this->loUsedInHi = $dbObject->is_lo_in_hi;
    $this->navStatus = $dbObject->nav_status;
    $this->isPitch = $dbObject->is_pitch;
    $this->isCatch = $dbObject->is_catch;
    $this->isSuaAtcaa = $dbObject->is_suaatcaa;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'nav_id'      => $this->navId,
      'nav_type'    => $this->navType,
      'name'        => $this->navName,
      'faa_region'  => $this->regionCode,
      'nav_class'   => $this->navClass,
      'nav_lat'     => $this->navLat,
      'nav_lon'     => $this->navLon,
      'tac_lat'     => $this->tacLat,
      'tac_lon'     => $this->tacLon,
      'artcc_hi'    => $this->artccHi,
      'artcc_lo'    => $this->artccLo,
      'elev'        => $this->elev,
      'mag_var'     => $this->magVar,
      'freq'        => $this->frequency,
      'bear'        => $this->trueBearing,
      'vor_vol'     => $this->vorSVol,
      'dme_vol'     => $this->dmeSVol,
      'is_lo_in_hi' => $this->loUsedInHi,
      'nav_status'  => $this->navStatus,
      'is_pitch'    => $this->isPitch,
      'is_catch'    => $this->isCatch,
      'is_suaatcaa' => $this->isSuaAtcaa,
      'cycle_id'    => $airacId,
      'next'        => $next
    );
    return $result;
  }
}
