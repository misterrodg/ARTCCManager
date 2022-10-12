<?php
namespace App\Classes\NASR;

class Tower
{
  public $masterAirportLid;
  public $satFacId;
  public $inClassB;
  public $inClassC;
  public $inClassD;
  public $inClassE;
  public $radarType1;
  public $radarType2;
  public $radarType3;
  public $radarType4;

  public function __construct(string $line){
    //$recordType = trim(substr($line,0,4)); // TWR1 // IGNORED
    $twrCommId = trim(substr($line,4,4)); // Terminal Comms Facility Id
    //$effectiveDate = trim(substr($line,8,10)); // Effective Date (MM/DD/YYYY) // IGNORED
    $airportFacId = trim(substr($line,18,11)); // FAA Landing Facility Site Number (Example: 04508.*A)
    $regionCode = trim(substr($line,29,3)); // FAA Region Code (Example: AAL - Alaska, AIN - International)
    //$assocStateName = trim(substr($line,32,30)); // Associated State Name // IGNORED
    //$assocStateAbbrev = trim(substr($line,62,2)); // Associated State Post Office Code // IGNORED
    //$assocCity = trim(substr($line,64,40)); // Associated City Name // IGNORED
    //$facilityName = trim(substr($line,104,50)); // Official Facility Name
    //$refPointLatDMS = trim(substr($line,154,14)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    $refPointLatSec = trim(substr($line,168,11)); //  SSSSSS.SSSH (Where H is N/S)
    //$refPointLonDMS = trim(substr($line,179,14)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    $refPointLonSec = trim(substr($line,193,11)); //   SSSSSS.SSSH (Where H is E/W)
    //$fssId = trim(substr($line,204,4)); // Tie-in FSS ID // IGNORED
    //$fssName = trim(substr($line,208,30)); // Tie-in FSS Name // IGNORED
    $facilityType = trim(substr($line,238,12)); // Facility Type: ATCT, TRACON, etc.
      //ATCT - Tower, NON-ATCT - Airport RCO, ATCT-A/C - ATCT plus APP,
      //ATCT-RAPCON - Up-Down (Mil AF), ATCT-RATCF - Up-Down (Mil Navy),
      //TRACON - TRACON, ATCT-TRACAB - ATCT plus radar cab, ATCT-CERAP - ATCT plus center radar APP
    //$facilityHours = trim(substr($line,250,2)); // Hours of operation // IGNORED
    //$facilityRegularity = trim(substr($line,252,2)); // Regularity of operation // IGNORED
      //WDO - WEEKDAYS ONLY, WEO - WEEKENDS ONLY, SEA - SUBJECT TO SEASONAL ADJUSTMENT
      //WDE - WEEKDAYS, OTHER HOURS WEEKENDS, WDS - WEEKDAYS, SUBJECT TO SEASONAL ADJUSTMENT,
      //WES - WEEKENDS, SUBJECT TO SEASONAL ADJUSTMENT
    $masterAirportLid = trim(substr($line,255,4)); // Master Airport LID
    //$masterAirportName = trim(substr($line,259,50)); // Master Airport Name (blank if record is master airport) // IGNORED
    //$dirFindEquip = trim(substr($line,309,15)); // Direction finding equipment // IGNORED
      //VHF, UHF, VHF/UHF, DOPPLER VHF, DOPPLER VHF/UHF
    $assocFacName = trim(substr($line,324,50)); // Associated Facility Name
    //$assocFacCity = trim(substr($line,374,40)); // Associated Facility City // IGNORED
    //$assocFacState = trim(substr($line,414,20)); // Associated Facility State // IGNORED
    //$assocFacCountry = trim(substr($line,434,25)); // Associated Facility Country // IGNORED
    //$assocFacPost = trim(substr($line,459,2)); // Associated Facility State Abbrev // IGNORED
    $regionCode = trim(substr($line,461,3)); // FAA Region Code (Example: AAL - Alaska, AIN - International)
    //$asrPointLatDMS = trim(substr($line,464,14)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    $asrPointLatSec = trim(substr($line,478,11)); //  SSSSSS.SSSH (Where H is N/S)
    //$asrPointLonDMS = trim(substr($line,489,14)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    $asrPointLonSec = trim(substr($line,503,11)); //   SSSSSS.SSSH (Where H is E/W)
    //$dfPointLatDMS = trim(substr($line,514,14)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    //$dfPointLatSec = trim(substr($line,528,11)); //  SSSSSS.SSSH (Where H is N/S) // IGNORED
    //$dfPointLonDMS = trim(substr($line,539,14)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    //$dfPointLonSec = trim(substr($line,553,11)); //   SSSSSS.SSSH (Where H is E/W) // IGNORED
    //$facilityAgency = trim(substr($line,564,40)); // Facility Agency Name // IGNORED
    //$facilityAgencyMil = trim(substr($line,604,40)); // Facility Mil Agency Name // IGNORED
    //$facilityPrimApp = trim(substr($line,644,40)); // Facility Agency for Primary App // IGNORED
    //$facilitySecApp = trim(substr($line,684,40)); // Facility Agency for Secondary App // IGNORED
    //$facilityPrimDep = trim(substr($line,724,40)); // Facility Agency for Primary Dep // IGNORED
    //$facilitySecDep = trim(substr($line,764,40)); // Facility Agency for Secondary Dep // IGNORED
    $facilityCallsign = trim(substr($line,804,26)); // Facility Callsign
    //$facilityMilCallsign = trim(substr($line,830,26)); // Facility Mil Callsign // IGNORED
    $facilityPrimAppCallsign = trim(substr($line,856,26)); // Facility Primary App Callsign
    $facilityPrimAppReliefCallsign = trim(substr($line,882,26)); // Facility Primary App Callsign
    $facilityPrimDepCallsign = trim(substr($line,908,26)); // Facility Primary Dep Callsign
    $facilityPrimDepReliefCallsign = trim(substr($line,934,26)); // Facility Primary Dep Callsign
    //SPACING FROM 960 for 648

    // ASSIGNMENTS TO AWY OBJECT
    $this->masterAirportLid = $masterAirportLid;
    //$this->awyDesignation = $awyDesignation; // [TODO
    //$this->awyPointSeq = intval($awyPointSeq); // [TODO
    //$this->ptpMea = ($ptpMea == '') ? null : intval($ptpMea); // [TODO
    //$this->ptpMeaRev = ($ptpMeaRev == '') ? null : intval($ptpMeaRev); // [TODO
    //$this->ptpMaa = ($ptpMaa == '') ? null : intval($ptpMaa); // [TODO
    //$awyGap = ($awyGap == '') ? 0 : 1; // [TODO
    //$this->awyGap = $awyGap; // [TODO
    //$pointArtcc = ($pointArtcc == '') ? null : $pointArtcc; // [TODO
    //$this->pointArtcc = $pointArtcc; // [TODO
  }

  public function twr2Line($line){
    //OPERATION HOURS DATA // UNUSED
    //$recordType = trim(substr($line,0,4)); // TWR2 // IGNORED
    //$twrCommId = trim(substr($line,4,4)); // Terminal Comms Facility Id // IGNORED
    //$pmsvHours = trim(substr($line,8,200)); // Pilot-to-Metro Service Hours of Operation (Mil) // IGNORED
    //$macpHours = trim(substr($line,208,200)); // Mil Acft Comm Post Service Hours of Operation (Mil) // IGNORED
    //$milHours = trim(substr($line,408,200)); // Mil ops hours // IGNORED
    //$primAppHours = trim(substr($line,608,200)); // Primary App Hours of Operation // IGNORED
    //$secAppHours = trim(substr($line,808,200)); // Secondary App Hours of Operation // IGNORED
    //$primDepHours = trim(substr($line,1008,200)); // Primary Dep Hours of Operation // IGNORED
    //$secDepHours = trim(substr($line,1208,200)); // Secondary Dep Hours of Operation // IGNORED
    //$twrHours = trim(substr($line,1408,200)); // Tower Hours of Operation // IGNORED

    // ASSIGNMENTS TO TOWER OBJECT
  }

  public function twr3Line($line){
    //COMMUNICATIONS FREQUENCIES AND USE DATA
    //$recordType = trim(substr($line,0,4)); // TWR3 // IGNORED
    //$twrCommId = trim(substr($line,4,4)); // Terminal Comms Facility Id // IGNORED
    $freq1 = trim(substr($line,8,44));
    $freq1use = trim(substr($line,52,50));
    $freq2 = trim(substr($line,102,44));
    $freq2use = trim(substr($line,146,50));
    $freq3 = trim(substr($line,196,44));
    $freq3use = trim(substr($line,240,50));
    $freq4 = trim(substr($line,290,44));
    $freq4use = trim(substr($line,334,50));
    $freq5 = trim(substr($line,384,44));
    $freq5use = trim(substr($line,428,50));
    $freq6 = trim(substr($line,478,44));
    $freq6use = trim(substr($line,522,50));
    $freq7 = trim(substr($line,572,44));
    $freq7use = trim(substr($line,616,50));
    $freq8 = trim(substr($line,666,44));
    $freq8use = trim(substr($line,710,50));
    $freq9 = trim(substr($line,760,44));
    $freq9use = trim(substr($line,804,50));
    $freq1NoTrunc = trim(substr($line,854,60));
    $freq2NoTrunc = trim(substr($line,914,60));
    $freq3NoTrunc = trim(substr($line,974,60));
    $freq4NoTrunc = trim(substr($line,1034,60));
    $freq5NoTrunc = trim(substr($line,1094,60));
    $freq6NoTrunc = trim(substr($line,1154,60));
    $freq7NoTrunc = trim(substr($line,1214,60));
    $freq8NoTrunc = trim(substr($line,1274,60));
    $freq9NoTrunc = trim(substr($line,1334,60));
    //SPACING FROM 1394 for 214

    // ASSIGNMENTS TO TOWER OBJECT
  }

  public function twr4Line($line){
    //SERVICES PROVIDED TO SATELLITE AIRPORT DATA
    //$recordType = trim(substr($line,0,4)); // TWR4 // IGNORED
    //$twrCommId = trim(substr($line,4,4)); // Terminal Comms Facility Id // IGNORED
    $masterAirportServices = trim(substr($line,8,100)); // Master Airport Services
    //SPACING FROM 108 for 1500

    // ASSIGNMENTS TO TOWER OBJECT
  }

  public function twr5Line($line){
    //INDICATION OF RADAR OR TYPE OF RADAR DATA
    //$recordType = trim(substr($line,0,4)); // TWR5 // IGNORED
    //$twrCommId = trim(substr($line,4,4)); // Terminal Comms Facility Id // IGNORED
    $primaryAppCall = trim(substr($line,8,9)); // Primary approach call
    $secondaryAppCall = trim(substr($line,17,9)); // Secondary approach call
    $primaryDepCall = trim(substr($line,26,9)); // Primary departure call
    $secondaryDepCall = trim(substr($line,35,9)); // Secondary departure call
    $radarType1 = trim(substr($line,44,10)); // Radar type in tower
    $radarHours1 = trim(substr($line,54,200)); // Radar hours of operation
    $radarType2 = trim(substr($line,254,10)); // Radar type in tower
    $radarHours2 = trim(substr($line,264,200)); // Radar hours of operation
    $radarType3 = trim(substr($line,464,10)); // Radar type in tower
    $radarHours3 = trim(substr($line,474,200)); // Radar hours of operation
    $radarType4 = trim(substr($line,674,10)); // Radar type in tower
    $radarHours4 = trim(substr($line,684,200)); // Radar hours of operation
    //SPACING FROM 884 for 724

    // ASSIGNMENTS TO TOWER OBJECT
    $this->radarType1 = $radarType1;
    $this->radarType2 = $radarType2;
    $this->radarType3 = $radarType3;
    $this->radarType4 = $radarType4;
  }

  public function twr6Line($line){
    //TERMINAL COMMUNICATIONS FACILITY REMARKS DATA
    //$recordType = trim(substr($line,0,4)); // TWR6 // IGNORED
    //$twrCommId = trim(substr($line,4,4)); // Terminal Comms Facility Id // IGNORED
    $twrElementNo = trim(substr($line,8,5)); // Tower element number
    $twrRemark = trim(substr($line,13,800)); // Tower remark text
    //SPACING FROM 813 for 795

    // ASSIGNMENTS TO TOWER OBJECT
  }

  public function twr7Line($line){
    //SATELLITE AIRPORT DATA
    //$recordType = trim(substr($line,0,4)); // TWR7 // IGNORED
    //$twrCommId = trim(substr($line,4,4)); // Terminal Comms Facility Id // IGNORED
    $satFreq = trim(substr($line,8,44)); // Satellite Freq
    $satFreqUse = trim(substr($line,52,50)); // Satellite Freq Use
    $satFacId = trim(substr($line,102,11)); // Satellite Site Number
    //$satFacLocId = trim(substr($line,113,4)); // Satellite Location Id // IGNORED
    //$satRegionCode = trim(substr($line,117,3)); // Satellite Region Code / IGNORED
    //$satStateName = trim(substr($line,120,30)); // Satellite State Name // IGNORED
    //$satPostCode = trim(substr($line,150,2)); // Satellite State Post Code // IGNORED
    //$satFacCity = trim(substr($line,152,40)); // Satellite Facility City // IGNORED
    //$satFacName = trim(substr($line,192,50)); // Satellite Facility Name // IGNORED
    //$facPointLatDMS = trim(substr($line,242,14)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    $facPointLatSec = trim(substr($line,256,11)); //  SSSSSS.SSSH (Where H is N/S)
    //$facPointLonDMS = trim(substr($line,267,14)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    $facPointLonSec = trim(substr($line,281,11)); //  SSSSSS.SSSH (Where H is E/W)
    //$satFssId = trim(substr($line,292,4)); // Tie-in FSS ID // IGNORED
    //$satFssName = trim(substr($line,296,30)); // Tie-in FSS Name // IGNORED
    $masterFacId = trim(substr($line,326,11)); // Master Facility Site Number (Example: 04508.*A)
    //$masterRegionCode = trim(substr($line,337,3)); // Master Region Code (Example: AAL - Alaska, AIN - International) // IGNORED
    //$masterStateName = trim(substr($line,340,30)); // Master State Name // IGNORED
    //$masterStateAbbrev = trim(substr($line,370,2)); // Master State Post Office Code // IGNORED
    //$masterCity = trim(substr($line,372,40)); // Master City Name // IGNORED
    //$masterName = trim(substr($line,412,50)); // Master Facility Name
    $satFreqNoTrunc = trim(substr($line,462,60)); // Satellite frequency
    //SPACING FROM 523 for 1086

    // ASSIGNMENTS TO TOWER OBJECT
    $this->satFacId = $satFacId;
  }

  public function twr8Line($line){
    //CLASS B/C/D/E AIRSPACE AND AIRSPACE HOURS DATA
    //$recordType = trim(substr($line,0,4)); // TWR8 // IGNORED
    //$twrCommId = trim(substr($line,4,4)); // Terminal Comms Facility Id // IGNORED
    $inClassB = trim(substr($line,8,1)); // Is in Class B Airspace
    $inClassC = trim(substr($line,9,1)); // Is in Class C Airspace
    $inClassD = trim(substr($line,10,1)); // Is in Class D Airspace
    $inClassE = trim(substr($line,11,1)); // Is in Class E Airspace
    //$airspaceHours = trim(substr($line,12,300)); // Airspace hours
    // RECORD SPACING FROM 312 for 1296

    // ASSIGNMENTS TO TOWER OBJECT
    $this->inClassB = ($inClassB == 'Y') ? 1 : 0;
    $this->inClassC = ($inClassC == 'Y') ? 1 : 0;
    $this->inClassD = ($inClassD == 'Y') ? 1 : 0;
    $this->inClassE = ($inClassE == 'Y') ? 1 : 0;
  }

  public function twr9Line($line){
    //AUTOMATIC TERMINAL INFORMATION SYSTEM (ATIS) DATA
    //$recordType = trim(substr($line,0,4)); // TWR9 // IGNORED
    //$twrCommId = trim(substr($line,4,4)); // Terminal Comms Facility Id // IGNORED
    //$atisSerial = trim(substr($line,8,4)); // ATIS Serial
    //$atisHours = trim(substr($line,12,200)); // ATIS Hours of Op // IGNORED
    //$purpose = trim(substr($line,212,100)); // Description of purpose
    //$atisPhone = trim(substr($line,312,18)); // ATIS Phone number
    // RECORD SPACING FROM 330 for 1278

    // ASSIGNMENTS TO TOWER OBJECT
  }

  public function toString(){
    echo $this->masterAirportLid.' : ['.
        'B '.$this->inClassB.','.
        'C '.$this->inClassC.','.
        'D '.$this->inClassD.','.
        'E '.$this->inClassE.','.
        '] : '.$this->radarType1.','.$this->radarType2.','.$this->radarType3.','.$this->radarType4.
    "<br/>";
  }

  public function toDBArray(string $airacId){
    $result = array(
      'route_id'                    => $this->awyDesignation,
      'point_id'                    => $this->pointId,
      'seq_no'                      => $this->awyPointSeq,
      'route_end'                   => $this->awyGap,
      'min_alt'                     => $this->ptpMea,
      'min_alt_rev'                 => $this->ptpMeaRev,
      'max_alt'                     => $this->ptpMaa,
      'artcc_id'                    => $this->pointArtcc,
      'AIRAC'                       => $airacId
    );
    return $result;
  }
}
