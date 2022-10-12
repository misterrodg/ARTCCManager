<?php

namespace App\Classes\NASR;

class ILS
{
  public $ilsId;
  public $airportFacId;
  public $ilsType;
  public $ilsCategory;
  public $ilsRunwayEndId;
  public $ilsAppBear;
  public $magVar;
  public $status;
  public $lat;
  public $lon;
  public $distRwyThresh;
  public $distRwyCline;
  public $dirCline;
  public $elevation;
  public $frequency;
  public $bcStatus;
  public $angularWidth;
  public $widthAtRwy;
  public $distRwyOpp;
  public $dirRwyOpp;
  public $hasGs;
  public $hasDme;
  public $hasMkr;

  public function __construct()
  {
    $this->ilsId = null;
    $this->airportFacId = null;
    $this->ilsType = null;
    $this->ilsCategory = null;
    $this->ilsRunwayEndId = null;
    $this->ilsAppBear = null;
    $this->magVar = null;
    $this->status = null;
    $this->lat = null;
    $this->lon = null;
    $this->distRwyThresh = null;
    $this->distRwyCline = null;
    $this->dirCline = null;
    $this->elevation = null;
    $this->frequency = null;
    $this->bcStatus = null;
    $this->angularWidth = null;
    $this->widthAtRwy = null;
    $this->distRwyOpp = null;
    $this->dirRwyOpp = null;
    $this->hasGs = FALSE;
    $this->hasDme = FALSE;
    $this->hasMkr = FALSE;
  }

  public function fromString1(string $line)
  {
    //$recordType = trim(substr($line,0,4)); // ILS1 // IGNORED
    $airportFacId = trim(substr($line, 4, 11)); // FAA Landing Facility Site Number (Example: 04508.*A)
    $ilsRunwayEndId = trim(substr($line, 15, 3)); // ILS Runway End ID
    $ilsType = trim(substr($line, 18, 10)); // ILS type:
    //ILS, SDF, LOCALIZER, LDA, ILS/DME, SDF/DME, LOC/DME, LOC/GS, LDA/DME
    $ilsId = trim(substr($line, 28, 6)); // Identification of ILS (prefixed by I-)
    //$effectiveDate = trim(substr($line,34,10)); // Effective date (MM/DD/YYYY) // IGNORED
    //$airportName = trim(substr($line,44,50)); // Name of airport // IGNORED
    //$ilsCity = trim(substr($line,94,40)); // City associated with navaid // IGNORED
    //$ilsStateId = trim(substr($line,134,2)); // State post office code // IGNORED
    //$ilsState = trim(substr($line,136,20)); // State name where associated city is located // IGNORED
    //$regionCode = trim(substr($line,156,3)); // FAA Region Code
    //$airportFaaId = trim(substr($line,159,4)); // FAA Location ID
    //$ilsRunwayLength = trim(substr($line,163,5)); // ILS runway length (whole feet) // IGNORED
    //$ilsRunwayWidth = trim(substr($line,168,4)); // ILS runway width (whole feet) // IGNORED
    $ilsCategory = trim(substr($line, 172, 9)); // ILS Category
    //$facOwner = trim(substr($line,181,50)); // Name of facility owner // IGNORED
    //$facOperator = trim(substr($line,231,50)); // Name of facility operator // IGNORED
    $ilsAppBear = trim(substr($line, 281, 6)); // ILS Approach Bearing in Degrees (magnetic - NNN.NN)
    $magVar = trim(substr($line, 287, 3)); // Magnetic variation (08W)
    //SPACING FROM 290 FOR 88

    // ASSIGNMENTS TO ILS OBJECT
    $this->ilsId = $ilsId;
    $this->airportFacId = $airportFacId;
    $this->ilsType = $ilsType;
    $this->ilsCategory = $ilsCategory;
    $this->ilsRunwayEndId = $ilsRunwayEndId;
    $this->ilsAppBear = $ilsAppBear;
    $this->magVar = (substr($magVar, -1) == 'E') ? - (intval(substr($magVar, 0, -1))) : (intval(substr($magVar, 0, -1)));
  }

  public function fromString2(string $line)
  {
    //LOCALIZER DATA
    //$recordType = trim(substr($line,0,4)); // ILS2 // IGNORED
    //$airportFacId = trim(substr($line,4,11)); // FAA Landing Facility Site Number (Example: 04508.*A) // IGNORED
    //$ilsRunwayEndId = trim(substr($line,15,3)); // ILS Runway End ID // IGNORED
    //$ilsType = trim(substr($line,18,10)); // ILS type // IGNORED
    $locOperationalStatus = trim(substr($line, 28, 22)); // Operational status of localizer
    //Operational IFR, Operational VFR, Operational Restricted, Decommissioned, Shutdown
    //$locEffectiveDate = trim(substr($line,50,10)); // Effective date of status (MM/DD/YYYY) // IGNORED
    //$locPointLatDMS = trim(substr($line,60,14)); // LATITUDE DD-MM-SS.SSSH (Where H is N/S) // IGNORED
    $locPointLatSec = trim(substr($line, 74, 11)); //  SSSSSS.SSSH (Where H is N/S)
    //$locPointLonDMS = trim(substr($line,85,14)); // LONGITUDE DDD-MM-SS.SSSH (Where H is E/W) // IGNORED
    $locPointLonSec = trim(substr($line, 99, 11)); //   SSSSSS.SSSH (Where H is E/W)
    //$locPointSource = trim(substr($line,110,2)); // Code indicating lat/lon info source:
    //A - AIR FORCE, C - COAST GUARD, D - CANADIAN AIRAC, F - FAA, FS- TECH OPS (AFS-530)
    //G - NOS (HISTORICAL), K - NGS, M - DOD (NGA), N - US NAVY, O - OWNER, P - NOS PHOTO SURVEY (HISTORICAL)
    //Q - QUAD PLOT (HISTORICAL), R - ARMY, S - SIAP, T - 3RD PARTY SURVEY, Z - SURVEYED
    $locDistRwyThresh = trim(substr($line, 112, 7)); // Distance of loc array from approach end of runway (feet - negative indicates placement inboard of runway)
    $locDistRwyCline = trim(substr($line, 119, 4)); // Distance of loc array from runway centerlines (feet)
    $locDirCline = trim(substr($line, 123, 1)); // Direction from runway centerline (L/R)
    //$locDistSource = trim(substr($line,124,2)); // Code indicating dist info source (see comment on $locPointSource)
    $locElevation = trim(substr($line, 126, 7)); // Array Elevation (nearest tenth of a foot MSL)
    $locFrequency = trim(substr($line, 133, 7)); // localizer frequency
    $locBcStatus = trim(substr($line, 140, 15)); // Loc back course status (restricted, no restrictions, usable, unusable)
    $locAngularWidth = trim(substr($line, 155, 5)); // Loc width (NN.NN)
    $locWidthAtRwy = trim(substr($line, 160, 7)); // Loc width at runway threshold
    $locDistRwyOpp = trim(substr($line, 167, 7)); // Loc dist from stop end of runway (feet - negative indicates placement inboard of runway)
    $locDirRwyOpp = trim(substr($line, 174, 1)); // Direction from runway opposite end (L/R)
    //$locServicesCode = trim(substr($line,175,2)); // Localizer services code (AP - approach control, AT - ATIS, NV - no voice) // UNUSED
    // RECORD SPACING FROM 177 for 201

    // ASSIGNMENTS TO ILS OBJECT
    $locString = '';
    $locStatusArray = explode(' ', $locOperationalStatus);
    foreach ($locStatusArray as $la) {
      switch ($la) {
          //In/Out of Commission
        case 'OPERATIONAL':
          $locString .= 'O';
          break;
        case 'DECOMMISSIONED':
          $locString .= 'D';
          break;
          //IFR/VFR/REST.
        case 'IFR':
          $locString .= 'I';
          break;
        case 'VFR':
          $locString .= 'V';
          break;
        case 'RESTRICTED':
          $locString .= 'R';
          break;
      }
    }
    $this->locOperationalStatus = $locString;
    $latDD = (substr($locPointLatSec, -1) == 'N') ? (floatval(substr($locPointLatSec, 0, -1)) / 3600) : - (floatval(substr($locPointLatSec, 0, -1)) / 3600); // Convert SEC to DD
    $lonDD = (substr($locPointLonSec, -1) == 'E') ? (floatval(substr($locPointLonSec, 0, -1)) / 3600) : - (floatval(substr($locPointLonSec, 0, -1)) / 3600); // Convert SEC to DD
    $this->lat = $latDD;
    $this->lon = $lonDD;
    $this->distRwyThresh = ($locDistRwyThresh == '') ? null : intval($locDistRwyThresh);
    $this->distRwyCline = ($locDistRwyCline == '') ? null : intval($locDistRwyCline);
    $this->dirCline = $locDirCline;
    $this->elevation = ($locElevation == '') ? null : round(floatval($locElevation));
    $this->frequency = ($locFrequency == '') ? null : (number_format($locFrequency, 3));
    $bcString = '';
    $bcStatusArray = explode(' ', $locBcStatus);
    foreach ($bcStatusArray as $ba) {
      switch ($ba) {
          //In/Out of Commission
        case 'OPERATIONAL':
          $bcString .= 'O';
          break;
        case 'DECOMMISSIONED':
          $bcString .= 'D';
          break;
          //IFR/VFR/REST.
        case 'IFR':
          $bcString .= 'I';
          break;
        case 'VFR':
          $bcString .= 'V';
          break;
        case 'RESTRICTED':
          $bcString .= 'R';
          break;
      }
    }
    $this->bcStatus = $bcString;
    $this->angularWidth = ($locAngularWidth == '') ? null : floatval($locAngularWidth);
    $this->widthAtRwy = ($locWidthAtRwy == '') ? null : floatval($locWidthAtRwy);
    $this->distRwyOpp = ($locDistRwyOpp == '') ? null : intval($locDistRwyOpp);
    $this->dirRwyOpp = $locDirRwyOpp;
  }

  public function fromString3(string $line)
  {
    //GLIDE SLOPE DATA
    //Handled by NASR\ILSGS
  }

  public function fromString4(string $line)
  {
    //DISTANCE MEASURING EQUIPMENT (DME) DATA
    //Handled by NASR\ILSDME
  }

  public function fromString5(string $line)
  {
    //MARKER BEACON DATA
    //Handled by NASR\ILSMKR
  }

  public function fromString6(string $line)
  {
    //INSTRUMENT LANDING SYSTEM REMARKS // UNUSED
    //$recordType = trim(substr($line,0,4)); // ILS6 // IGNORED
    //$airportFacId = trim(substr($line,4,11)); // FAA Landing Facility Site Number (Example: 04508.*A) // IGNORED
    //$ilsRunwayEndId = trim(substr($line,15,3)); // ILS Runway End ID // IGNORED
    //$ilsType = trim(substr($line,18,10)); // ILS type // IGNORED
    //$remark = trim(substr($line, 28, 350)); // Remarks

    // ASSIGNMENTS TO ILS OBJECT
    //$this->remark = $remark;
  }

  public function fromModel(object $dbObject)
  {
    $this->ilsId = $dbObject->ils_id;
    $this->airportFacId = $dbObject->fac_id;
    $this->ilsType = $dbObject->type;
    $this->ilsCategory = $dbObject->cat;
    $this->ilsRunwayEndId = $dbObject->rwy_id;
    $this->ilsAppBear = $dbObject->bear;
    $this->magVar = $dbObject->mag_var;
    $this->status = $dbObject->status;
    $this->lat = $dbObject->ils_lat;
    $this->lon = $dbObject->ils_lon;
    $this->distRwyThresh = $dbObject->dist_thr;
    $this->distRwyCline = $dbObject->dist_cln;
    $this->elevation = $dbObject->elev;
    $this->frequency = $dbObject->freq;
    $this->bcStatus = $dbObject->bc_status;
    $this->angularWidth = $dbObject->width_ang;
    $this->widthAtRwy = $dbObject->width_rwy;
    $this->distRwyOpp = $dbObject->dist_rwy;
    $this->dirCline = $dbObject->dir_rwy;
    $this->hasGs = $dbObject->has_gs;
    $this->hasDme = $dbObject->has_dme;
    $this->hasMkr = $dbObject->has_mkr;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'ils_id'        => $this->ilsId,
      'fac_id'        => $this->airportFacId,
      'type'          => $this->ilsType,
      'cat'           => $this->ilsCategory,
      'rwy_id'        => $this->ilsRunwayEndId,
      'bear'          => $this->ilsAppBear,
      'mag_var'       => $this->magVar,
      'status'        => $this->status,
      'ils_lat'       => $this->lat,
      'ils_lon'       => $this->lon,
      'dir_rwy'       => $this->dirCline,
      'dist_thr'      => $this->distRwyThresh,
      'dist_cln'      => $this->distRwyCline,
      'dist_rwy_opp'  => $this->distRwyOpp,
      'elev'          => $this->elevation,
      'freq'          => $this->frequency,
      'bc_status'     => $this->bcStatus,
      'width_ang'     => $this->angularWidth,
      'width_rwy'     => $this->widthAtRwy,
      'has_gs'        => $this->hasGs,
      'has_dme'       => $this->hasDme,
      'has_mkr'       => $this->hasMkr,
      'cycle_id'      => $airacId,
      'next'          => $next
    );
    return $result;
  }
}
