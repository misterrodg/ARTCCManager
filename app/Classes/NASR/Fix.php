<?php

namespace App\Classes\NASR;

use App\Classes\Helpers\TextHelper;

class Fix
{
  public $fixId;
  public $icaoRegion;
  public $lat;
  public $lon;
  public $prevName;
  public $useType;
  public $nasId;
  public $artccHi;
  public $artccLo;
  public $isPitch;
  public $isCatch;
  public $isSuaAtcaa;

  public function __construct()
  {
    $this->fixId = null;
    $this->icaoRegion = null;
    $this->lat = null;
    $this->lon = null;
    $this->prevName = null;
    $this->useType = null;
    $this->nasId = null;
    $this->artccHi = null;
    $this->artccLo = null;
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
    //$recordType = trim(substr($line,0,4)); // FIX // IGNORED
    $fixId = trim(substr($line, 4, 30)); // Record ID (Fix ID)
    //$fixState = trim(substr($line,34,30)); // Record State (Fix State Name) // IGNORED
    $icaoRegion = trim(substr($line, 64, 2)); // ICAO Region Code
    $lat = trim(substr($line, 66, 14)); // Fix Lat (NN-NN-NN.NNNA)
    $lon = trim(substr($line, 80, 14)); // Fix Lon (NNN-NN-NN.NNNA)
    if ($lat == '') {
      $lat = null;
      $lon = null;
    } else {
      $coord = $texthelper->handleDMSFormatted($lat, 'DD-MM-SS.SSSA', $lon, "DDD-MM-SS.SSSA");
      $lat = $coord->lat;
      $lon = $coord->lon;
    }
    //$civMil = trim(substr($line,94,3)); // Fix use type: MIL or FIX (civil) // IGNORED
    //$definitionId = trim(substr($line,97,22)); // 3 or 4 letter Ident * Facility Type * Direction OR course of MLS component to define fix
    //$approachId = trim(substr($line,119,22)); // Airport Id * Approach end of runway * distance of radar component to define fix
    $prevName = trim(substr($line, 141, 33)); // Previous fix name
    //$chartingInfo = trim(substr($line,174,38)); // Charting information // IGNORED
    //$toBePublished = trim(substr($line,212,1)); // Fix to be published: Y/N // IGNORED
    $useType = trim(substr($line, 213, 15)); // Fix use: // IGNORED
    //CNF (COMPUTER NAVIGATION FIX), MIL-REP-PT (MILITARY REPORTING POINT), MIL-WAYPOINT (MILITARY WAYPOINT)
    //NRS-WAYPOINT (NRS WAYPOINT), RADAR (RADAR), REP-PT (REPORTING POINT), VFR-WP (VFR WAYPOINT), WAYPOINT (WAYPOINT)
    switch ($useType) {
      case 'CNF':
        $useType = 'CNFIX';
        break;
      case 'MIL-REP-PT':
        $useType = 'MILRP';
        break;
      case 'MIL-WAYPOINT':
        $useType = 'MILWP';
        break;
      case 'NRS-WAYPOINT':
        $useType = 'NRSWP';
        break;
      case 'RADAR':
        $useType = 'RADAR';
        break;
      case 'REP-PT':
        $useType = 'REPPT';
        break;
      case 'VFR-WP':
        $useType = 'VFRWP';
        break;
      case 'WAYPOINT':
        $useType = 'WAYPT';
        break;
      default:
        $useType = null;
    }
    $nasId = trim(substr($line, 228, 5)); // NAS ID for fix (if Fix overlies navaid, this field shows the navaid ID)
    $artccHi = trim(substr($line, 233, 4)); // High ARTCC ID
    $artccLo = trim(substr($line, 237, 4)); //  Low ARTCC ID
    //$fixCountry = trim(substr($line,241,30)); // Fix Country (outside CONUS) // IGNORED
    $isPitch = trim(substr($line, 271, 1)); // Is a pitch point: Y/N
    $isPitch = ($isPitch == 'Y') ? 1 : 0;
    $isCatch = trim(substr($line, 272, 1)); // Is a catch point: Y/N
    $isCatch = ($isCatch == 'Y') ? 1 : 0;
    $isSuaAtcaa = trim(substr($line, 273, 1)); // Is SUA/ATCAA: Y/N
    $isSuaAtcaa = ($isSuaAtcaa == 'Y') ? 1 : 0;
    // RECORD SPACING FROM 274 for 192

    // ASSIGNMENTS TO FIX OBJECT
    $this->fixId = $fixId;
    $this->icaoRegion = $icaoRegion;
    $this->lat = $lat;
    $this->lon = $lon;
    $this->prevName = $prevName;
    $this->useType = $useType;
    $this->nasId = $nasId;
    $this->artccHi = $artccHi;
    $this->artccLo = $artccLo;
    $this->isPitch = $isPitch;
    $this->isCatch = $isCatch;
    $this->isSuaAtcaa = $isSuaAtcaa;
  }

  public function fromString2(string $line)
  {
    //FIX NAVAID MAKEUP TEXT // UNUSED
    //$recordType = trim(substr($line,0,4)); // FIX // IGNORED
    //$fixId = trim(substr($line,4,30)); // Record ID (Fix ID) // IGNORED
    //$fixState = trim(substr($line,34,30)); // Record State (Fix State Name) // IGNORED
    //$icaoRegion = trim(substr($line,64,2)); // ICAO Region Code // IGNORED
    //$definitionType = trim(substr($line, 66, 23)); // Fix defined by type:
    //VORTAC - C, TACAN - T, VOR/DME - D, FAN MARKER - F, CONSOLAN - K
    //LOW FREQUENCY RANGE - L, MARINE NDB - M, MARINE NDB/DME - MD
    //VOT - O, DME - OD, NDB - R, NDB/DME - RD, UHF/NDB - U, VOR - V
    // RECORD SPACING FROM 89 for 377

    // ASSIGNMENTS TO FIX OBJECT
  }

  public function fromString3(string $line)
  {
    //FIX ILS MAKEUP TEXT // UNUSED
    //$recordType = trim(substr($line,0,4)); // FIX // IGNORED
    //$fixId = trim(substr($line,4,30)); // Record ID (Fix ID) // IGNORED
    //$fixState = trim(substr($line,34,30)); // Record State (Fix State Name) // IGNORED
    //$icaoRegion = trim(substr($line,64,2)); // ICAO Region Code // IGNORED
    //$definitionType = trim(substr($line, 66, 23)); // Fix defined by type:
    //3 or 4 letter ID * Facility Type * Direction OR course of ILS used for fix
    //LDA/DME - DD, LDA - LA, LOCALIZER - LC, ILS/DME - LD, LOC/DME - LE
    //LOC/GS - LG, ILS - LS, SDF/DME - SD, SDF - SF
    // RECORD SPACING FROM 89 for 377

    // ASSIGNMENTS TO FIX OBJECT
  }

  public function fromString4(string $line)
  {
    //FIX REMARKS TEXT // UNUSED
    //$recordType = trim(substr($line,0,4)); // FIX // IGNORED
    //$fixId = trim(substr($line,4,30)); // Record ID (Fix ID) // IGNORED
    //$fixState = trim(substr($line,34,30)); // Record State (Fix State Name) // IGNORED
    //$icaoRegion = trim(substr($line,64,2)); // ICAO Region Code // IGNORED
    //$remark = trim(substr($line, 66, 100)); // Fix remark
    // RECORD SPACING FROM 166 for 300

    // ASSIGNMENTS TO FIX OBJECT
  }

  public function fromString5(string $line)
  {
    //CHARTING TYPES // UNUSED
    //$recordType = trim(substr($line,0,4)); // FIX // IGNORED
    //$fixId = trim(substr($line,4,30)); // Record ID (Fix ID) // IGNORED
    //$fixState = trim(substr($line,34,30)); // Record State (Fix State Name) // IGNORED
    //$icaoRegion = trim(substr($line,64,2)); // ICAO Region Code // IGNORED
    //$chart = trim(substr($line, 66, 22)); // Chart on which fix is to be depicted
    // RECORD SPACING FROM 88 for 378

    // ASSIGNMENTS TO FIX OBJECT
  }

  public function fromModel(object $dbObject)
  {
    $this->fixId = $dbObject->fix_id;
    $this->icaoRegion = $dbObject->region;
    $this->lat = $dbObject->fix_lat;
    $this->lon = $dbObject->fix_lon;
    $this->prevName = $dbObject->prev_name;
    $this->useType = $dbObject->use_type;
    $this->nasId = $dbObject->nas_id;
    $this->artccHi = $dbObject->artcc_hi;
    $this->artccLo = $dbObject->artcc_lo;
    $this->isPitch = $dbObject->is_pitch;
    $this->isCatch = $dbObject->is_catch;
    $this->isSuaAtcaa = $dbObject->is_suaatcaa;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'fix_id'      => $this->fixId,
      'region'      => $this->icaoRegion,
      'fix_lat'     => $this->lat,
      'fix_lon'     => $this->lon,
      'prev_name'   => $this->prevName,
      'use_type'    => $this->useType,
      'nas_id'      => $this->nasId,
      'artcc_hi'    => $this->artccHi,
      'artcc_lo'    => $this->artccLo,
      'is_pitch'    => $this->isPitch,
      'is_catch'    => $this->isCatch,
      'is_suaatcaa' => $this->isSuaAtcaa,
      'cycle_id'    => $airacId,
      'next'        => $next
    );
    return $result;
  }
}
