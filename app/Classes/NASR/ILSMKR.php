<?php

namespace App\Classes\NASR;

class ILSMKR
{
  public $ilsId;
  public $airportFacId;
  public $mkrId;
  public $type;
  public $status;
  public $lat;
  public $lon;
  public $distRwyThresh;
  public $distRwyCline;
  public $dirCline;
  public $elevation;
  public $frequency;
  public $navId;

  public function __construct()
  {
    $this->ilsId = null;
    $this->airportFacId = null;
    $this->mkrId = null;
    $this->type = null;
    $this->status = null;
    $this->lat = null;
    $this->lon = null;
    $this->distRwyThresh = null;
    $this->distRwyCline = null;
    $this->dirCline = null;
    $this->elevation = null;
    $this->frequency = null;
    $this->navId = null;
  }

  public function fromString5(string $line)
  {
    //MARKER BEACON DATA
    //$recordType = trim(substr($line,0,4)); // ILS5 // IGNORED
    //$airportFacId = trim(substr($line,4,11)); // FAA Landing Facility Site Number (Example: 04508.*A) // IGNORED
    //$ilsRunwayEndId = trim(substr($line,15,3)); // ILS Runway End ID // IGNORED
    //$ilsType = trim(substr($line,18,10)); // ILS type // IGNORED
    $markerType = trim(substr($line, 28, 2)); // Marker Type (IM, MM, OM)
    $markerOperationalStatus = trim(substr($line, 30, 22)); // Operational status of DME
    //Operational IFR, Operational VFR, Operational Restricted, Decommissioned, Shutdown
    //$markerEffectiveDate = trim(substr($line,52,10)); // Effective date of status (MM/DD/YYYY) // IGNORED
    //$markerPointLatDMS = trim(substr($line,62,14)); // LATITUDE DD-MM-SS.SSSH (Where H is N/S) // IGNORED
    $markerPointLatSec = trim(substr($line, 76, 11)); //  SSSSSS.SSSH (Where H is N/S)
    //$markerPointLonDMS = trim(substr($line,87,14)); // LONGITUDE DDD-MM-SS.SSSH (Where H is E/W) // IGNORED
    $markerPointLonSec = trim(substr($line, 101, 11)); //   SSSSSS.SSSH (Where H is E/W)
    //$markerPointSource = trim(substr($line,112,2)); // Code indicating lat/lon info source:
    $markerDistRwyThresh = trim(substr($line, 114, 7)); // Distance of DME array from approach end of runway (feet - negative indicates placement inboard of runway)
    $markerDistRwyCline = trim(substr($line, 121, 4)); // Distance of DME array from runway centerlines (feet)
    $markerDirCline = trim(substr($line, 125, 1)); // Direction from runway centerline (L/R)
    //$markerDistSource = trim(substr($line,126,2)); // Code indicating dist info source (see comment on $locPointSource)
    $markerElevation = trim(substr($line, 128, 7)); // Array Elevation (nearest tenth of a foot MSL)
    $markerEquip = trim(substr($line, 135, 15)); // Marker Equipment:
    //MARKER, COMLO - Compass Locator, NDB, MARKER/COMLO, MARKER/NDB
    $markerId = trim(substr($line, 150, 2)); // Marker ID
    //$markerName = trim(substr($line,152,30)); // Marker Name // IGNORED
    $markerFrequency = trim(substr($line, 182, 3)); // Frequency in khz
    $navaidId = trim(substr($line, 185, 25)); // Navaid ID of colocated navaid (blank if not colocated)
    //$lowPoweredNDBOprationalStatus = trim(substr($line,210,22)); // Low powered NDB status
    //$markerService = trim(substr($line,232,30)); // Service provided by marker // IGNORED
    // RECORD SPACING FROM 262 for 116

    // ASSIGNMENTS TO ILS OBJECT
    $markerString = '';
    $markerStatusArray = explode(' ', $markerOperationalStatus);
    foreach ($markerStatusArray as $ma) {
      switch ($ma) {
          //In/Out of Commission
        case 'OPERATIONAL':
          $markerString .= 'O';
          break;
        case 'DECOMMISSIONED':
          $markerString .= 'D';
          break;
          //IFR/VFR/REST.
        case 'IFR':
          $markerString .= 'I';
          break;
        case 'VFR':
          $markerString .= 'V';
          break;
        case 'RESTRICTED':
          $markerString .= 'R';
          break;
      }
    }
    $this->markerOperationalStatus = $markerString;
    $latDD = (substr($markerPointLatSec, -1) == 'N') ? (floatval(substr($markerPointLatSec, 0, -1)) / 3600) : - (floatval(substr($markerPointLatSec, 0, -1)) / 3600); // Convert SEC to DD
    $lonDD = (substr($markerPointLonSec, -1) == 'E') ? (floatval(substr($markerPointLonSec, 0, -1)) / 3600) : - (floatval(substr($markerPointLonSec, 0, -1)) / 3600); // Convert SEC to DD
    $this->lat = $latDD;
    $this->lon = $lonDD;
    $this->distRwyThresh = ($markerDistRwyThresh == '') ? null : intval($markerDistRwyThresh);
    $this->distRwyCline = ($markerDistRwyCline == '') ? null : intval($markerDistRwyCline);
    $this->dirCline = $markerDirCline;
    $this->elevation = ($markerElevation == '') ? null : round(floatval($markerElevation));
    $this->equip = ($markerEquip == '') ? null : $markerEquip;
    $this->type = $markerType;
    $this->mkrId = ($markerId == '') ? null : $markerId;
    $this->frequency = ($markerFrequency == '') ? null : $markerFrequency;
    $this->navId = ($navaidId == '') ? null : substr($navaidId, 0, strpos($navaidId, '*'));
  }

  public function fromModel(object $dbObject)
  {
    $this->ilsId = $dbObject->ils_id;
    $this->airportFacId = $dbObject->fac_id;
    $this->mkrId = $dbObject->mkr_id;
    $this->type = $dbObject->type;
    $this->status = $dbObject->status;
    $this->lat = $dbObject->mkr_lat;
    $this->lon = $dbObject->mkr_lon;
    $this->distRwyThresh = $dbObject->dist_thr;
    $this->distRwyCline = $dbObject->dist_cln;
    $this->dirCline = $dbObject->dir_rwy;
    $this->elevation = $dbObject->elev;
    $this->frequency = $dbObject->freq;
    $this->navId = $dbObject->nav_id;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'ils_id'   => $this->ilsId,
      'fac_id'   => $this->airportFacId,
      'mkr_id'   => $this->mkrId,
      'type'     => $this->type,
      'status'   => $this->status,
      'mkr_lat'  => $this->lat,
      'mkr_lon'  => $this->lon,
      'dir_rwy'  => $this->dirCline,
      'dist_thr' => $this->distRwyThresh,
      'dist_cln' => $this->distRwyCline,
      'elev'     => $this->elevation,
      'freq'     => $this->frequency,
      'nav_id'   => $this->navId,
      'cycle_id' => $airacId,
      'next'     => $next
    );
    return $result;
  }
}
