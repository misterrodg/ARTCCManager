<?php

namespace App\Classes\NASR;

class ILSDME
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
  public $channel;
  public $distRwyOpp;

  public function __construct()
  {
    $this->ilsId = null;
    $this->airportFacId = null;
    $this->status = null;
    $this->lat = null;
    $this->lon = null;
    $this->distRwyThresh = null;
    $this->distRwyCline = null;
    $this->dirCline = null;
    $this->elevation = null;
    $this->channel = null;
    $this->distRwyOpp = null;
  }

  public function fromString4(string $line)
  {
    //DISTANCE MEASURING EQUIPMENT (DME) DATA
    //$recordType = trim(substr($line,0,4)); // ILS4 // IGNORED
    //$airportFacId = trim(substr($line,4,11)); // FAA Landing Facility Site Number (Example: 04508.*A) // IGNORED
    //$ilsRunwayEndId = trim(substr($line,15,3)); // ILS Runway End ID // IGNORED
    //$ilsType = trim(substr($line,18,10)); // ILS type // IGNORED
    $dmeOperationalStatus = trim(substr($line, 28, 22)); // Operational status of DME
    //Operational IFR, Operational VFR, Operational Restricted, Decommissioned, Shutdown
    //$dmeEffectiveDate = trim(substr($line,50,10)); // Effective date of status (MM/DD/YYYY) // IGNORED
    //$dmePointLatDMS = trim(substr($line,60,14)); // LATITUDE DD-MM-SS.SSSH (Where H is N/S) // IGNORED
    $dmePointLatSec = trim(substr($line, 74, 11)); //  SSSSSS.SSSH (Where H is N/S)
    //$dmePointLonDMS = trim(substr($line,85,14)); // LONGITUDE DDD-MM-SS.SSSH (Where H is E/W) // IGNORED
    $dmePointLonSec = trim(substr($line, 99, 11)); //   SSSSSS.SSSH (Where H is E/W)
    //$dmePointSource = trim(substr($line,110,2)); // Code indicating lat/lon info source:
    $dmeDistRwyThresh = trim(substr($line, 112, 7)); // Distance of DME array from approach end of runway (feet - negative indicates placement inboard of runway)
    $dmeDistRwyCline = trim(substr($line, 119, 4)); // Distance of DME array from runway centerlines (feet)
    $dmeDirCline = trim(substr($line, 123, 1)); // Direction from runway centerline (L/R)
    //$dmeDistSource = trim(substr($line,124,2)); // Code indicating dist info source (see comment on $locPointSource)
    $dmeElevation = trim(substr($line, 126, 7)); // Array Elevation (nearest tenth of a foot MSL)
    $dmeChannel = trim(substr($line, 133, 4)); // DME Channel
    $dmeDistRwyOpp = trim(substr($line, 137, 7)); // DME dist from stop end of runway (feet - negative indicates placement inboard of runway)
    // RECORD SPACING FROM 144 for 234

    // ASSIGNMENTS TO ILS OBJECT
    $dmeString = '';
    $dmeStatusArray = explode(' ', $dmeOperationalStatus);
    foreach ($dmeStatusArray as $da) {
      switch ($da) {
          //In/Out of Commission
        case 'OPERATIONAL':
          $dmeString .= 'O';
          break;
        case 'DECOMMISSIONED':
          $dmeString .= 'D';
          break;
          //IFR/VFR/REST.
        case 'IFR':
          $dmeString .= 'I';
          break;
        case 'VFR':
          $dmeString .= 'V';
          break;
        case 'RESTRICTED':
          $dmeString .= 'R';
          break;
      }
    }
    $this->status = $dmeString;
    $latDD = (substr($dmePointLatSec, -1) == 'N') ? (floatval(substr($dmePointLatSec, 0, -1)) / 3600) : - (floatval(substr($dmePointLatSec, 0, -1)) / 3600); // Convert SEC to DD
    $lonDD = (substr($dmePointLonSec, -1) == 'E') ? (floatval(substr($dmePointLonSec, 0, -1)) / 3600) : - (floatval(substr($dmePointLonSec, 0, -1)) / 3600); // Convert SEC to DD
    $this->lat = $latDD;
    $this->lon = $lonDD;
    $this->distRwyThresh = ($dmeDistRwyThresh == '') ? null : intval($dmeDistRwyThresh);
    $this->distRwyCline = ($dmeDistRwyCline == '') ? null : intval($dmeDistRwyCline);
    $this->dirCline = $dmeDirCline;
    $this->elevation = ($dmeElevation == '') ? null : round(floatval($dmeElevation));
    $this->channel = ($dmeChannel == '') ? null : $dmeChannel;
    $this->distRwyOpp = ($dmeDistRwyOpp == '') ? null : intval($dmeDistRwyOpp);
  }

  public function fromModel(object $dbObject)
  {
    $this->ilsId = $dbObject->ils_id;
    $this->airportFacId = $dbObject->fac_id;
    $this->status = $dbObject->status;
    $this->lat = $dbObject->dme_lat;
    $this->lon = $dbObject->dme_lon;
    $this->dirCline = $dbObject->dir_rwy;
    $this->distRwyThresh = $dbObject->dist_thr;
    $this->distRwyCline = $dbObject->dist_cln;
    $this->distRwyOpp = $dbObject->dist_rwy_opp;
    $this->elevation = $dbObject->elev;
    $this->channel = $dbObject->channel;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'ils_id'       => $this->ilsId,
      'fac_id'       => $this->airportFacId,
      'status'       => $this->status,
      'dme_lat'      => $this->lat,
      'dme_lon'      => $this->lon,
      'dir_rwy'      => $this->dirCline,
      'dist_thr'     => $this->distRwyThresh,
      'dist_cln'     => $this->distRwyCline,
      'dist_rwy_opp' => $this->distRwyOpp,
      'elev'         => $this->elevation,
      'channel'      => $this->channel,
      'cycle_id'     => $airacId,
      'next'         => $next
    );
    return $result;
  }
}
