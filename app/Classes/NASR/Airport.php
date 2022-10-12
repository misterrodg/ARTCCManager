<?php

namespace App\Classes\NASR;

use App\Classes\Helpers\TextHelper;

class Airport
{
  public $airportFacId;
  public $faaRegion;
  public $airportId;
  public $airportFaa;
  public $airportName;
  public $artcc;
  public $lat;
  public $lon;
  public $magVar;
  public $elevation;
  public $towered;
  public $fuel;
  public $emergency;
  public $ownership;
  public $facilityUse;
  public $facilityType;
  public $status;

  public function __construct()
  {
    $this->airportFacId = null;
    $this->faaRegion = null;
    $this->airportId = null;
    $this->airportFaa = null;
    $this->airportName = null;
    $this->artcc = null;
    $this->lat = null;
    $this->lon = null;
    $this->magVar = null;
    $this->elevation = null;
    $this->towered = null;
    $this->fuel = null;
    $this->emergency = null;
    $this->ownership = null;
    $this->facilityUse = null;
    $this->facilityType = null;
    $this->status = null;
  }

  public function fromString(string $line)
  {
    $texthelper = new TextHelper;
    /*
      This function is a mess, but the commented lines
      have been left in to show the FAA file definition
      in case they should be useful to anyone.
    */
    //$recordType = trim(substr($line,0,3)); // APT // IGNORED;
    $airportFacId = trim(substr($line, 3, 11)); // FAA Landing Facility Site Number (Example: 04508.*A)
    $airportType = trim(substr($line, 14, 13)); // FAA Landing Facility Type: AIRPORT,BALLOONPORT,SEAPLANE BASE,GLIDERPORT,HELIPORT,ULTRALIGHT
    $airportFaaId = trim(substr($line, 27, 4)); // FAA Location ID
    //$effectiveDate = trim(substr($line,31,10)); // Information Effective Date // IGNORED
    $regionCode = trim(substr($line, 41, 3)); // FAA Region Code (Example: AAL - Alaska, AIN - International)
    //$districtFaa = trim(substr($line,44,4)); // FAA District / Field Office Code // IGNORED
    //$assocStateAbbrev = trim(substr($line,48,2)); // Associated State Post Office Code // IGNORED
    //$assocStateName = trim(substr($line,50,20)); // Associated State Name // IGNORED
    //$assocCounty = trim(substr($line,70,21)); // Associated County Name // IGNORED
    //$assocCountyState = trim(substr($line,91,2)); // Associated County State // IGNORED
    //$assocCity = trim(substr($line,93,40)); // Associated City Name // IGNORED
    $facilityName = trim(substr($line, 133, 50)); // Official Facility Name
    $ownershipType = trim(substr($line, 183, 2)); // Airport Ownership: PU - PUBLIC, PR - PRIVATE, MA - AIR FORCE, MN - NAVY, MR - ARMY, CG - COAST GUARD
    $facilityUse = trim(substr($line, 185, 2)); // Facility Use: PU - OPEN TO THE PUBLIC, PR - PRIVATE
    //$ownerName = trim(substr($line,187,35)); // Facility Owner Name // IGNORED
    //$ownerAddress = trim(substr($line,222,72)); // Facility Owner Address // IGNORED
    //$ownerCityState = trim(substr($line,294,45)); // Facility Owner CityState // IGNORED
    //$ownerPhone = trim(substr($line,340,16)); // Facility Owner Phone Number (FORMATS: NNN-NNN-NNNN (AREA CODE + PHONE NUMBER), 1-NNN-NNNN (DIAL 1-800 THEN NUMBER), 8-NNN-NNNN (DIAL 800 THEN NUMBER) // IGNORED
    //$facilityManagerName = trim(substr($line,355,35)); // Facility Manager Name // IGNORED
    //$facilityManagerAddress = trim(substr($line,390,72)); // Facility Manager Address // IGNORED
    //$facilityManagerCityState = trim(substr($line,462,45)); // Facility Manager CityState // IGNORED
    //$facilityManagerPhone = trim(substr($line,507,16)); // Facility Manager Phone Number (FORMATS: NNN-NNN-NNNN (AREA CODE + PHONE NUMBER), 1-NNN-NNNN (DIAL 1-800 THEN NUMBER), 8-NNN-NNNN (DIAL 800 THEN NUMBER) // IGNORED
    $refPointLatDMS = trim(substr($line, 523, 15)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    //$refPointLatSec = trim(substr($line, 538, 12)); //  SSSSSS.SSSSH (Where H is N/S)
    $refPointLonDMS = trim(substr($line, 550, 15)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    //$refPointLonSec = trim(substr($line, 565, 12)); //   SSSSSS.SSSSH (Where H is E/W)
    //$refPointDet = trim(substr($line,577,1)); // Airport Reference Point Determination Method: E - ESTIMATED, S - SURVEYED // IGNORED
    $elevation = round(floatval(trim(substr($line, 578, 7)))); // Airport Elevation (nearest tenth of a foot MSL)
    //$elevationDet = trim(substr($line,585,1)); // Airport Elevation Determination Method: E - ESTIMATED, S - SURVEYED // IGNORED
    $magVar = trim(substr($line, 586, 3)); // Airport Magnetic Variation (Example: 03W)
    //$magVarEpochYear = trim(substr($line,589,4)); // Airport MagVar Epoch Year (Example: 1985) // IGNORED
    //$trafficPattern = intval(trim(substr($line,593,4))); // Traffic Pattern Altitude (AGL) // IGNORED
    //$sectionalChart = trim(substr($line,597,30)); // Sectional Chart on which Facility Appears // IGNORED
    //$distanceFromCBD = intval(trim(substr($line,627,2))); // Distance from Central Business District of Associated City // IGNORED
    //$directionFromCBD = trim(substr($line,629,3)); // Direction from CBD // IGNORED
    //$landArea = intval(trim(substr($line,632,5))); // Land Area Covered by Airport (acres) // IGNORED
    //$boundArtccId = trim(substr($line,637,4)); // Boundary ARTCC ID (Boundary Airport WITHIN) // IGNORED
    //$boundArtccCompId = trim(substr($line,641,3)); // Boundary ARTCC Computer ID // IGNORED
    //$boundArtccName = trim(substr($line,644,30)); // Boundary ARTCC Name // IGNORED
    $respArtccId = trim(substr($line, 674, 4)); // Responsible ARTCC ID (Boundary Airport WITHIN)
    //$respArtccCompId = trim(substr($line,678,3)); // Responsible ARTCC Computer ID // IGNORED
    //$respArtccName = trim(substr($line,681,30)); // Responsible ARTCC Name // IGNORED
    //$fssOnField = trim(substr($line,711,1)); // Tie-in FSS physically on facility (Y/N) // IGNORED
    //$fssId = trim(substr($line,712,4)); // Tie-in FSS ID // IGNORED
    //$fssName = trim(substr($line,716,30)); // Tie-in FSS Name // IGNORED
    //$fssNumber = trim(substr($line,746,16)); // Phone number to FSS from airport // IGNORED
    //$fssNumberNoToll = trim(substr($line,762,16)); // Toll free phone number to FSS from airport // IGNORED
    //$fssAltId = trim(substr($line,778,04)); // Alternate FSS ID
    //$fssAltName = trim(substr($line,782,30)); // Tie-in Alternate FSS Name // IGNORED
    //$fssAltNumberNoToll = trim(substr($line,812,16)); // Toll free phone number to Alternate FSS from airport // IGNORED
    //$notamFacility = trim(substr($line,828,4)); // Facility ID of NOTAM facility // IGNORED
    //$notamServiceD = trim(substr($line,832,1)); // NOTAM 'D' Availability (Y/N) // IGNORED
    //$activationDate = trim(substr($line,833,7)); // Facility activation date (only tracked from 06/1981) // IGNORED
    $statusCode = trim(substr($line, 840, 2)); // Status code: CI - CLOSED INDEFINITELY, CP - CLOSED PERMANENTLY, O - OPERATIONAL
    $arffCertification = trim(substr($line, 842, 15)); // ARFF Certification (I/II/III), Certificate Code (A/B/C/D/E or L), Sched/Unsched (S/U) and Cert Date (MM/YYYY)
    //$npiasAgreements = trim(substr($line,857,7)); // IGNORED
    // NPAIS Agreements Codes (N - NPIAS, B - INSTALLATION OF NAVIGATIONAL FACILITIES ON PRIVATELY OWNED AIRPORTS UNDER F&E PROGRAM ...
    //G - GRANT AGREEMENTS UNDER FAAP/ADAP/AIP, H - COMPLIANCE WITH ACCESSIBILITY TO THE HANDICAPPED
    //P - SURPLUS PROPERTY AGREEMENT UNDER PUBLIC LAW 289, R - SURPLUS PROPERTY AGREEMENT UNDER REGULATION 16-WAA
    //S - CONVEYANCE UNDER SECTION 16 FEDERAL AIRPORT ACT OF 1946 OR SECTION 23 AIRPORT AND AIRWAY DEVELOPMENT ACT OF 1970
    //V - ADVANCE PLANNING AGREEMENT UNDER FAAP, X - OBLIGATIONS ASSUMED BY TRANSFER, Y - ASSURANCES PURSUANT TO TITLE VI CIVIL RIGHTS ACT OF 1964
    //Z - CONVEYANCE UNDER SECTION 303(C) FEDERAL AVIATION ACT OF 1958
    //1 - GRANT AGREEMENT HAS EXPIRED; HOWEVER AGREEMENT REMAINS IN EFFECT FOR THIS FACILITY AS LONG AS IT IS PUBLIC USE
    //2 - SECTION 303(C) AUTHORITY FROM FAA ACT OF 1958 HAS EXPIRED; HOWEVER AGREEMENT REMAINS IN EFFECT FOR THIS FACILITY AS LONG AS IT IS PUBLIC USE
    //3 - AP-4 AGREEMENT UNDER DLAND OR DCLA HAS EXPIRED,NONE - NO GRANT AGREEMENT EXISTS, BLANK- NO GRANT AGREEMENT EXISTS
    //$determination = trim(substr($line,864,13)); // Airport airspace determination: CONDL (CONDITIONAL), NOT ANALYZED, NO OBJECTION, OBJECTIONABLE // IGNORED
    // $customs = trim(substr($line,877,1)); // Designated as Customs Port of Entry (Y/N) // IGNORED
    // $customsWithFee = trim(substr($line,878,1)); // Designated as Customs Port of Entry with a user fee (Y/N) // IGNORED
    // $jointUseFacility = trim(substr($line,879,1)); // Joint military/public use facility (Y/N) // IGNORED
    // $milUseAgreement = trim(substr($line,880,1)); // Landing rights granted to military (Y/N) // IGNORED
    // $inspectionMethod = trim(substr($line,881,2)); // Airport inspection method : F - FEDERAL, S - STATE, C - CONTRACTOR, 1 - 5010-1 PUBLIC USE MAILOUT PROGRAM, 2 - 5010-2 PRIVATE USE MAILOUT PROGRAM // IGNORED
    // $inspectionAgency = trim(substr($line,883,1)); // Agency performing inspection: F - FAA AIRPORTS FIELD PERSONNEL, S - STATE AERONAUTICAL PERSONNEL, C - PRIVATE CONTRACT PERSONNEL, N - OWNER // IGNORED
    // $inpectionLast = trim(substr($line,884,8)); // Inspection date (MMDDYYYY) // IGNORED
    // $inpectionRequest = trim(substr($line,892,8)); // Last date information request was completed by owner/manager (MMDDYYYY) // IGNORED
    $fuelTypes = trim(substr($line, 900, 40)); // Fuel types available for public use
    // $repairAirframe = trim(substr($line,940,5)); // Airframe repair availability: MAJOR, MINOR, NONE // IGNORED
    // $repairPowerplant = trim(substr($line,945,5)); // Powerplant repair availability: MAJOR, MINOR, NONE // IGNORED
    // $oxygenTypes = trim(substr($line,950,8)); // Oxygen availability: HIGH, LOW, HIGH/LOW, NONE // IGNORED
    // $oxygenBulkTypes = trim(substr($line,958,8)); // Bulk Oxygen availability: HIGH, LOW, HIGH/LOW, NONE // IGNORED
    // $lightingSched = trim(substr($line,966,7)); // Airport lighting schedule // IGNORED
    // $beaconSched = trim(substr($line,973,7)); // Airport lighting schedule // IGNORED
    $towered = trim(substr($line, 980, 1)); // Towered (Y/N)
    // $unicom = trim(substr($line,981,7)); // UNICOM Freq // IGNORED
    //$ctaf = trim(substr($line,988,7)); // CTAF // IGNORED
    // $segmentedCircle = trim(substr($line,995,4)); // segmented circled on airport (Y/N/NONE/Y-L, latter for 'lighted') // IGNORED
    // $beaconColor = trim(substr($line,999,3)); // Beacon colors // IGNORED
    //CG    CLEAR-GREEN (LIGHTED LAND AIRPORT)
    //CY    CLEAR-YELLOW (LIGHTED SEAPLANE BASE)
    //CGY   CLEAR-GREEN-YELLOW (HELIPORT)
    //SCG   SPLIT-CLEAR-GREEN (LIGHTED MILITARY AIRPORT)
    //C     CLEAR (UNLIGHTED LAND AIRPORT)
    //Y     YELLOW (UNLIGHTED SEAPLANE BASE)
    //G     GREEN  (LIGHTED LAND AIRPORT)
    //N     NONE
    // $landingFee = trim(substr($line,1002,1)); // Landing fee (Y/N) // IGNORED
    // $medFacility = trim(substr($line,1003,1)); // Facility used for medical purposes (Y) // IGNORED
    // $aircraftSEGA = intval(trim(substr($line,1004,3))); // Single engine GA Aircraft // IGNORED
    // $aircraftMEGA = intval(trim(substr($line,1007,3))); // Multi engine GA Aircraft // IGNORED
    // $aircraftJGA  = intval(trim(substr($line,1010,3))); // Jet engine GA Aircraft // IGNORED
    // $aircraftHGA  = intval(trim(substr($line,1013,3))); // Helo GA // IGNORED
    // $aircraftGli  = intval(trim(substr($line,1016,3))); // Gliders // IGNORED
    // $aircraftMil  = intval(trim(substr($line,1019,3))); // Mil Aircraft (incl helos) // IGNORED
    // $aircraftUli  = intval(trim(substr($line,1022,3))); // Ultralights // IGNORED
    // $servicesComm   = intval(trim(substr($line,1025,6))); // Commercial scheduled services // IGNORED
    // $servicesCommu  = intval(trim(substr($line,1031,6))); // Commuter scheduled services // IGNORED
    // $servicesAirT   = intval(trim(substr($line,1037,6))); // Air taxi services // IGNORED
    // $servicesGAL    = intval(trim(substr($line,1043,6))); // GA Local services // IGNORED
    // $servicesGAI    = intval(trim(substr($line,1049,6))); // GA Itinerant services // IGNORED
    // $servicesMil    = intval(trim(substr($line,1055,6))); // Mil services // IGNORED
    // $servicesDate   = trim(substr($line,1061,10)); // 12-month end date scheduled services collected (MM/DD/YYYY) for above 6 fields // IGNORED
    // $positionSource = trim(substr($line,1071,16)); // Position source // IGNORED
    // $positionSourceDate = trim(substr($line,1087,10)); // Position source date // IGNORED
    // $elevationSource = trim(substr($line,1097,16)); // Elevation source // IGNORED
    // $elevationSourceDate = trim(substr($line,1113,10)); // Elevation source date // IGNORED
    // $contractFuelAvail = trim(substr($line,1123,1)); // Contract fuel available (Y/N) // IGNORED
    // $transientStorageFac = trim(substr($line,1124,12)); // Transient storage facilities: BUOY, HGR, TIE // IGNORED
    // $servicesOther = trim(substr($line,1136,71)); // Other airport services // IGNORED
    //AFRT  - AIR FREIGHT SERVICES, AGRI  - CROP DUSTING SERVICES, AMB   - AIR AMBULANCE SERVICES, AVNCS - AVIONICS, BCHGR - BEACHING GEAR
    //CARGO - CARGO HANDLING SERVICES, CHTR  - CHARTER SERVICE, GLD   - GLIDER SERVICE, INSTR - PILOT INSTRUCTION, PAJA  - PARACHUTE JUMP ACTIVITY
    //RNTL  - AIRCRAFT RENTAL, SALES - AIRCRAFT SALES, SURV  - ANNUAL SURVEYING, TOW   - GLIDER TOWING SERVICES
    // $windInd = trim(substr($line,1207,3)); // Wind indicator: (Y/N/Y-L) // IGNORED
    $airportId = trim(substr($line, 1210, 7)); // ICAO ID
    // $minimumOpNet = trim(substr($line,1217,1)); // Airport part of Minimum Operation Network // IGNORED
    // RECORD SPACING FROM 1218 for 313

    // ASSIGNMENTS TO AIRPORT OBJECT
    switch ($airportType) {
      case 'AIRPORT':
        $airportType = 'A';
        break;
      case 'BALLOONPORT':
        $airportType = 'B';
        break;
      case 'SEAPLANE BASE':
        $airportType = 'S';
        break;
      case 'GLIDERPORT':
        $airportType = 'G';
        break;
      case 'HELIPORT':
        $airportType = 'H';
        break;
      case 'ULTRALIGHT':
        $airportType = 'U';
        break;
      default:
        $airportType = 'A';
    }
    $this->airportFacId = $airportFacId;
    $this->airportId = $airportId;
    $this->airportFaa = $airportFaaId;
    $this->airportName = $facilityName;
    $this->faaRegion = $regionCode;
    $this->artcc = $respArtccId;
    $coord = $texthelper->handleDMSFormatted($refPointLatDMS, 'DD-MM-SS.SSSSA', $refPointLonDMS, "DDD-MM-SS.SSSSA");
    $lat = $coord->lat;
    $lon = $coord->lon;
    $this->lat = $lat;
    $this->lon = $lon;
    $this->magVar = (substr($magVar, -1) == 'E') ? - (intval(substr($magVar, 0, -1))) : (intval(substr($magVar, 0, -1)));
    $this->elevation = $elevation;
    $this->towered = ($towered == 'Y') ? 1 : 0;
    $this->fuel = ($fuelTypes != '') ? 1 : 0;
    $this->emergency = (strpos($arffCertification, 'I') !== FALSE) ? 1 : 0;
    $this->ownership = $ownershipType;
    $this->facilityUse = $facilityUse;
    $this->facilityType = $airportType;
    $this->status = $statusCode;
  }

  public function fromModel(object $dbObject)
  {
    $this->airportFacId = $dbObject->fac_id;
    $this->airportId = $dbObject->icao_id;
    $this->airportFaa = $dbObject->faa_id;
    $this->airportName = $dbObject->name;
    $this->lat = $dbObject->apt_lat;
    $this->lon = $dbObject->apt_lon;
    $this->magVar = $dbObject->mag_var;
    $this->elevation = $dbObject->elev;
    $this->faaRegion = $dbObject->faa_region;
    $this->artcc = $dbObject->artcc_id;
    $this->facilityType = $dbObject->type;
    $this->ownership = $dbObject->ownership;
    $this->facilityUse = $dbObject->use_id;
    $this->towered = $dbObject->towered;
    $this->fuel = $dbObject->fuel;
    $this->emergency = $dbObject->emergency;
    $this->status = $dbObject->status;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'fac_id'     => $this->airportFacId,
      'icao_id'    => $this->airportId,
      'faa_id'     => $this->airportFaa,
      'name'       => $this->airportName,
      'apt_lat'    => $this->lat,
      'apt_lon'    => $this->lon,
      'mag_var'    => $this->magVar,
      'elev'       => $this->elevation,
      'faa_region' => $this->faaRegion,
      'artcc_id'   => $this->artcc,
      'type'       => $this->facilityType,
      'ownership'  => $this->ownership,
      'use_id'     => $this->facilityUse,
      'towered'    => $this->towered,
      'fuel'       => $this->fuel,
      'emergency'  => $this->emergency,
      'status'     => $this->status,
      'cycle_id'   => $airacId,
      'next'       => $next
    );
    return $result;
  }

  public function getAirportId()
  {
    $result = ($this->airportId != '') ? $this->airportId : $this->airportFaa;
    return $result;
  }
}
