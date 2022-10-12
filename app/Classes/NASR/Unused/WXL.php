<?php
namespace App\Classes\NASR;

use App\Classes\Coordinate;

class WXL
{
  public $wxlId;
  public $lat;
  public $lon;
  public $services;
  public $collectiveWxType;
  public $affectedAreaType;
  public $affectedArea;

  public function __construct(string $line){
    $wxlId = trim(substr($line,0,5)); // Weather reporting location identifier (ex. AGS, AK21)
    $lat = trim(substr($line,5,8)); // Latitude (DDMMSSTC - DD deg, MM min, SS sec, T tenths, C N/S)
    $lon = trim(substr($line,1398)); // Longitude (DDDMMSSTC - DDD deg, MM min, SS sec, T tenths, C E/W)
    //$assocCity = trim(substr($line,22,40)); // Associated City // IGNORED
    //$assocStateAbbrev = trim(substr($line,62,2)); // Associated state post code // IGNORED
    //$assocCountryCode = trim(substr($line,64,3)); // Asso country numeric code (non-US only; ex. 484) // IGNORED
    //$elevation = trim(substr($line,67,5)); // Elevation in whole feet // IGNORED
    //$elevationSource = trim(substr($line,72,1)); // Surveyed (S) or Estimated (E) // IGNORED
    $servicesAtLocation = trim(substr($line,73,60)); // Weather services avail at location (up to 5 char each)
      //AC - SEVERE WEATHER OUTLOOK NARRATIVE, AWW - SEVERE WEATHER FORECAST ALERT, CWA - CENTRAL WEATHER ADVISORY
      //FA - AREA FORECAST, FD - WINDS & TEMPERATURE ALOFT FORECAST, FT - AVIATION TERMINAL FORECAST
      //FX - MISCELLANEOUS FORECASTS, METAR - AVIATION ROUTINE WEATHER REPORT (ICAO), MIS - METEOROLOGICAL IMPACT SUMMARY
      //NOTAM - NOTICE TO AIRMEN, SA - SURFACE OBSERVATION REPORT, SD - RADAR WEATHER REPORT, SPECI - AVIATION SPECIAL WEATHER REPORT (ICAO)
      //SYNS - TRANSCRIBED WEATHER BROADCAST SYNOPSES, TAF - AVIATION TERMINAL FORECAST (ICAO), TWEB - TRANSCRIBED WEATHER BROADCAST
      //UA - AIRCRAFT REPORT (PIREP), WA - WEATHER ADVISORY, WH - ABBREVIATED HURRICANE ADVISORY, WO - TROPICAL DEPRESSIONS
      //WS - SIGMET, WST - CONVECTIVE SIGMET, WW - SEVERE WEATHER BROADCASTS OR BULLETINS
    // RECORD SPACING FROM 133 for 2

    // ASSIGNMENTS TO AWOS OBJECT
    $this->wxlId = $wxlId;
    if($lat == ''){
      $lat = null;
      $lon = null;
    } else {
      $latNs = substr($lat,-1);
      $latD = intval(substr($lat,0,2));
      $latM = intval(substr($lat,2,2));
      $latS = intval(substr($lat,4,3))/10;
      $lonEw = substr($lon,-1);
      $lonD = intval(substr($lon,0,3));
      $lonM = intval(substr($lon,3,2));
      $lonS = intval(substr($lon,5,3))/10;
      $coord = new Coordinate(0.0,0.0);
      $coord->fromDms($latNs,$latD,$latM,$latS,$lonEw,$lonD,$lonM,$lonS);

      $lat = $coord->lat;
      $lon = $coord->lon;
    }
    $this->lat = $lat;
    $this->lon = $lon;
    $this->services = $servicesAtLocation;
  }

  public function wxl2Line(string $line){
    //$continuationRecordId = trim(substr($line,0,1)); // If '*' in this position, this is a continuation of the previous WXL record // IGNORED
    if(strpos($line,'FT') !== FALSE || strpos($line,'SD') !== FALSE){
      //COLLECTIVES
      $collectiveWxType = trim(substr($line,1,5)); // Collective weather service type
        //FT - AVIATION TERMINAL FORECAST, SD - RADAR WEATHER REPORT
      $collectiveNumber = trim(substr($line,6,1)); // Collective number (0 - 9)
      // RECORD SPACING FROM 7 for 128

      // ASSIGNMENTS TO WXL OBJECT
      $this->collectiveWxType = ($collectiveWxType == '') ? null : $collectiveWxType;
    } else {
      //AFFECTED AREAS
      $affectedAreaType = trim(substr($line,1,5)); // Affected area service type
        //CWA - CENTRAL WEATHER ADVISORY, FA - AREA FORECAST, MIS - METEOROLOGICAL IMPACT SUMMARY
        //WH - ABBREVIATED HURRICANE ADVISORY, WO - TROPICAL DEPRESSIONS, WST - CONVECTIVE SIGMET
      $affectedArea = trim(substr($line,6,114)); // Affected areas - states/areas (two-letter abbreviations)
        //Great Lakes: LE, LH, LM, LO, LS (ERIE, HURON, MICHIGAN, ONTARIO, SUPERIOR)
        //Oceanic Areas: OA, OP (Atlantic, Pacific)
      // RECORD SPACING FROM 120 for 15

      // ASSIGNMENTS TO WXL OBJECT
      $this->affectedAreaType = ($affectedAreaType == '') ? null : $affectedAreaType;
      $this->affectedArea = ($affectedArea == '') ? null : $affectedArea;
    }
  }

  public function toDBArray(string $airacId){
    $result = array(
      null
      //[TODO]
      /*
      'station_id'    => $this->stationId,
      'station_type'  => $this->stationType,
      'is_functional' => $this->isFunctional,
      'is_assoc_nav'  => $this->isAssocNavaid,
      'lat'           => $this->lat,
      'lon'           => $this->lon,
      'elev'          => $this->elev,
      'frequency'     => $this->frequency,
      'frequency2'    => $this->frequency2,
      'assoc_fac'     => $this->facilityAssoc,
      'AIRAC'         => $airacId
      */
    );
    return $result;
  }
}
