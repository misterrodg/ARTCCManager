<?php

namespace App\Classes\NASR;

class ILSGS
{
  public $ilsId;
  public $airportFacId;
  public $status;
  public $lat;
  public $lon;
  public $distRwyThresh;
  public $distRwyCline;
  public $dirCline;
  public $elevation;
  public $hasDme;
  public $angle;
  public $frequency;

  public function __construct()
  {
  }

  public function fromString3(string $line)
  {
    //GLIDE SLOPE DATA
    //$recordType = trim(substr($line,0,4)); // ILS3 // IGNORED
    //$airportFacId = trim(substr($line,4,11)); // FAA Landing Facility Site Number (Example: 04508.*A) // IGNORED
    //$ilsRunwayEndId = trim(substr($line,15,3)); // ILS Runway End ID // IGNORED
    //$ilsType = trim(substr($line,18,10)); // ILS type // IGNORED
    $gsOperationalStatus = trim(substr($line, 28, 22)); // Operational status of glide slope
    //Operational IFR, Operational VFR, Operational Restricted, Decommissioned, Shutdown
    //$gsEffectiveDate = trim(substr($line,50,10)); // Effective date of status (MM/DD/YYYY) // IGNORED
    //$gsPointLatDMS = trim(substr($line,60,14)); // LATITUDE DD-MM-SS.SSSH (Where H is N/S) // IGNORED
    $gsPointLatSec = trim(substr($line, 74, 11)); //  SSSSSS.SSSH (Where H is N/S)
    //$gsPointLonDMS = trim(substr($line,85,14)); // LONGITUDE DDD-MM-SS.SSSH (Where H is E/W) // IGNORED
    $gsPointLonSec = trim(substr($line, 99, 11)); //   SSSSSS.SSSH (Where H is E/W)
    //$gsPointSource = trim(substr($line,110,2)); // Code indicating lat/lon info source:
    $gsDistRwyThresh = trim(substr($line, 112, 7)); // Distance of gs array from approach end of runway (feet - negative indicates placement inboard of runway)
    $gsDistRwyCline = trim(substr($line, 119, 4)); // Distance of gs array from runway centerlines (feet)
    $gsDirCline = trim(substr($line, 123, 1)); // Direction from runway centerline (L/R)
    //$gsDistSource = trim(substr($line,124,2)); // Code indicating dist info source (see comment on $locPointSource)
    $gsElevation = trim(substr($line, 126, 7)); // Array Elevation (nearest tenth of a foot MSL)
    $gsCategory = trim(substr($line, 133, 15)); // GS Category (GLIDE SLOPE or GLIDE SLOPE/DME)
    $gsAngle = trim(substr($line, 148, 5)); // GS Angle (NN.NN)
    $gsFrequency = trim(substr($line, 153, 7)); // GS frequency
    //$rwyElevationAbeamGS = trim(substr($line,160,8)); // Runway Elevation abeam GS array (nearest tenth of a foot MSL) // IGNORED
    // RECORD SPACING FROM 168 for 210

    // ASSIGNMENTS TO ILS OBJECT
    $gsString = '';
    $gsStatusArray = explode(' ', $gsOperationalStatus);
    foreach ($gsStatusArray as $ga) {
      switch ($ga) {
          //In/Out of Commission
        case 'OPERATIONAL':
          $gsString .= 'O';
          break;
        case 'DECOMMISSIONED':
          $gsString .= 'D';
          break;
          //IFR/VFR/REST.
        case 'IFR':
          $gsString .= 'I';
          break;
        case 'VFR':
          $gsString .= 'V';
          break;
        case 'RESTRICTED':
          $gsString .= 'R';
          break;
      }
    }
    $this->status = $gsString;
    $latDD = (substr($gsPointLatSec, -1) == 'N') ? (floatval(substr($gsPointLatSec, 0, -1)) / 3600) : - (floatval(substr($gsPointLatSec, 0, -1)) / 3600); // Convert SEC to DD
    $lonDD = (substr($gsPointLonSec, -1) == 'E') ? (floatval(substr($gsPointLonSec, 0, -1)) / 3600) : - (floatval(substr($gsPointLonSec, 0, -1)) / 3600); // Convert SEC to DD
    $this->lat = $latDD;
    $this->lon = $lonDD;
    $this->distRwyThresh = ($gsDistRwyThresh == '') ? null : intval($gsDistRwyThresh);
    $this->distRwyCline = ($gsDistRwyCline == '') ? null : intval($gsDistRwyCline);
    $this->dirCline = $gsDirCline;
    $this->elevation = ($gsElevation == '') ? null : round(floatval($gsElevation));
    $this->hasDme = (strpos($gsCategory, 'DME') > 0) ? TRUE : FALSE;
    $this->angle = ($gsAngle == '') ? null : floatval($gsAngle);
    $this->frequency = ($gsFrequency == '') ? null : (number_format($gsFrequency, 3));
  }

  public function fromModel(object $dbObject)
  {
    $this->ilsId = $dbObject->ils_id;
    $this->airportFacId = $dbObject->fac_id;
    $this->status = $dbObject->status;
    $this->lat = $dbObject->gs_lat;
    $this->lon = $dbObject->gs_lon;
    $this->dirCline = $dbObject->dir_rwy;
    $this->distRwyThresh = $dbObject->dist_thr;
    $this->distRwyCline = $dbObject->dist_cln;
    $this->elevation = $dbObject->elev;
    $this->angle = $dbObject->angle;
    $this->frequency = $dbObject->freq;
    $this->hasDme = $dbObject->has_dme;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'ils_id'   => $this->ilsId,
      'fac_id'   => $this->airportFacId,
      'status'   => $this->status,
      'gs_lat'   => $this->lat,
      'gs_lon'   => $this->lon,
      'dir_rwy'  => $this->dirCline,
      'dist_thr' => $this->distRwyThresh,
      'dist_cln' => $this->distRwyCline,
      'elev'     => $this->elevation,
      'angle'    => $this->angle,
      'freq'     => $this->frequency,
      'has_dme'  => $this->hasDme,
      'cycle_id' => $airacId,
      'next'     => $next
    );
    return $result;
  }
}
