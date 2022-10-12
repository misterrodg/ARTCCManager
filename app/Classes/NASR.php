<?php

namespace App\Classes;

use Illuminate\Support\Facades\Storage;

use App\Models\Airport;
use App\Models\Airway;
use App\Models\AirwayATS;
use App\Models\AWOS;
use App\Models\Boundary;
use App\Models\CodedRoute;
use App\Models\Fix;
use App\Models\ILS;
use App\Models\ILSDME;
use App\Models\ILSGS;
use App\Models\ILSMKR;
use App\Models\Navaid;
use App\Models\PreferredRoute;
use App\Models\Runway;

class NASR extends Import
{
  const IMPORT_TYPE = "NASR";
  const IMPORT_URL = "FAA_NASR_URL";

  public function __construct(?string $editionName = "current", ?string $date = "now", ?string $editionNumber = "", ?string $editionUrl = "", ?string $airacId = "")
  {
    $this->importType = self::IMPORT_TYPE;
    $this->envName = self::IMPORT_URL;
    $this->editionName = strtoupper($editionName);
    $this->editionDate = new \DateTime($date);
    $this->editionNumber = $editionNumber;
    $this->editionUrl = $editionUrl;
    $this->airacId = $airacId;
    $this->faaResponse = (object)[];
  }

  public function decompress()
  {
    $localNasrDir = "nasr/" . $this->editionName . "/";
    $localZIP = $localNasrDir . "FAANASR.zip";
    $airacFile = $localNasrDir . "AIRAC.json";
    //Set up responses
    $code = 500;
    $message = "Failed";
    $success = FALSE;
    $data = null;
    //Delete existing files if they exist
    $nasrFileArray = array(
      "AFF.txt", "APT.txt", "ARB.txt", "ATS.txt", "AWOS.txt", "AWY.txt", "CDR.txt", "COM.txt", "FIX.txt", "FSS.txt", "HPF.txt", "ILS.txt", "LID.txt", "MAA.txt", "MTR.txt", "NAV.txt", "PFR.txt", "PJA.txt", "STARDP.txt", "TWR.txt", "WXL.txt"
    );
    foreach ($nasrFileArray as $f) {
      if (Storage::exists($localNasrDir . $f)) {
        Storage::delete($localNasrDir . $f);
      }
    }
    //Unzip file
    $nasrFileZip = new ZIP($localZIP);
    $zipResponse = $nasrFileZip->unzip($nasrFileArray);
    //If unzipped, delete ZIP
    if ($zipResponse->isSuccess) {
      //Add AIRAC Data
      Storage::delete($airacFile);
      Storage::put($airacFile, json_encode($this));
      //Delete ZIP
      Storage::delete($localZIP);
      //Update Response
      $code = 200;
      $message = "OK";
      $success = TRUE;
      $data = null;
    }
    $response = new ResponseMessage($code, $message, $success, $data);
    return $response->toJson();
  }

  public function processAirports()
  {
    $airportData = array();
    $runwayData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("APT.txt");
    if ($nasrHandle) {
      //Read through lines
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $recordType = substr($line, 0, 3);
        if ($recordType == 'APT') {
          $airport = new NASR\Airport;
          $airport->fromString($line);
          array_push($airportData, $airport->toDBArray($this->airacId, $next));
        }
        if ($recordType == 'RWY') {
          $runway = new NASR\Runway;
          $runway->fromString($line);
          array_push($runwayData, $runway->toDBArray($this->airacId, $next));
        }
      }
      fclose($nasrHandle);
      //Chunk through data and bulk upsert
      foreach (array_chunk($airportData, 1000) as $d) {
        Airport::upsert($d, ["fac_id", "next"]);
      }
      foreach (array_chunk($runwayData, 1000) as $d) {
        Runway::upsert($d, ["fac_id", "rwy_id", "next"]);
      }
      //Update Data Currency
      $this->updateDataCurrency("APT");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processAirways()
  {
    $airwayData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("AWY.txt");
    if ($nasrHandle) {
      //Read through lines
      $airway = new NASR\Airway;
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $recordType = substr($line, 0, 4);
        if ($recordType == 'AWY1') {
          if (!empty($airway->pointId)) {
            array_push($airwayData, $airway->toDBArray($this->airacId, $next));
          }
          $airway = new NASR\Airway;
          $airway->fromString1($line);
        }
        if ($recordType == 'AWY2') {
          $airway->fromString2($line);
        }
      }
      fclose($nasrHandle);
      //Delete old data
      Airway::truncate();
      //Chunk through data and bulk upsert
      foreach (array_chunk($airwayData, 1000) as $d) {
        Airway::insert($d);
      }
      //Update Data Currency
      $this->updateDataCurrency("AWY");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processAirwaysAts()
  {
    $airwayAtsData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("ATS.txt");
    if ($nasrHandle) {
      //Read through lines
      $airway = new NASR\AirwayATS;
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $recordType = substr($line, 0, 4);
        if ($recordType == 'ATS1') {
          if (!empty($airway->pointId)) {
            array_push($airwayAtsData, $airway->toDBArray($this->airacId, $next));
          }
          $airway = new NASR\AirwayATS;
          $airway->fromString1($line);
        }
        if ($recordType == 'ATS2') {
          $airway->fromString2($line);
        }
      }
      fclose($nasrHandle);
      //Delete old  data
      AirwayATS::truncate();
      //Chunk through data and bulk upsert
      foreach (array_chunk($airwayAtsData, 1000) as $d) {
        AirwayATS::insert($d);
      }
      //Update Data Currency
      $this->updateDataCurrency("ATS");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processAwos()
  {
    $awosData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("AWOS.txt");
    if ($nasrHandle) {
      //Read through lines
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $recordType = substr($line, 0, 5);
        if ($recordType == 'AWOS1') {
          $awos = new NASR\AWOS;
          $awos->fromString1($line);
          array_push($awosData, $awos->toDBArray($this->airacId, $next));
        }
      }
      fclose($nasrHandle);
      //Chunk through data and bulk upsert
      foreach (array_chunk($awosData, 1000) as $d) {
        AWOS::upsert($d, ["awos_id", "next"]);
      }
      //Update Data Currency
      $this->updateDataCurrency("AWOS");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processBoundaries()
  {
    $boundaryData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("ARB.txt");
    if ($nasrHandle) {
      //Read through lines
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $boundary = new NASR\Boundary;
        $boundary->fromString($line);
        array_push($boundaryData, $boundary->toDBArray($this->airacId, $next));
      }
      fclose($nasrHandle);
      //Delete old data
      Boundary::truncate();
      //Chunk through data and bulk upsert
      foreach (array_chunk($boundaryData, 1000) as $d) {
        Boundary::insert($d);
      }
      //Update Data Currency
      $this->updateDataCurrency("ARB");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processCodedRoutes()
  {
    $codedRouteData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("CDR.txt");
    if ($nasrHandle) {
      //Read through lines
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $codedRoute = new NASR\CodedRoute;
        $codedRoute->fromString($line);
        array_push($codedRouteData, $codedRoute->toDBArray($this->airacId, $next));
      }
      fclose($nasrHandle);
      //Delete old data
      CodedRoute::truncate();
      //Chunk through data and bulk upsert
      foreach (array_chunk($codedRouteData, 1000) as $d) {
        CodedRoute::insert($d);
      }
      //Update Data Currency
      $this->updateDataCurrency("CDR");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processFixes()
  {
    $fixData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("FIX.txt");
    if ($nasrHandle) {
      //Read through lines
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $recordType = substr($line, 0, 4);
        if ($recordType == 'FIX1') {
          $fix = new NASR\Fix;
          $fix->fromString1($line);
          array_push($fixData, $fix->toDBArray($this->airacId, $next));
        }
      }
      fclose($nasrHandle);
      //Chunk through data and bulk upsert
      foreach (array_chunk($fixData, 1000) as $d) {
        Fix::upsert($d, ["fix_id", "next"]);
      }
      //Update Data Currency
      $this->updateDataCurrency("FIX");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processILS()
  {
    $ilsData = array();
    $ilsDmeData = array();
    $ilsGsData = array();
    $ilsMkrData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("ILS.txt");
    if ($nasrHandle) {
      //Read through lines
      $ils = new NASR\ILS;
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $recordType = substr($line, 0, 4);
        if ($recordType == 'ILS1') {
          if (!empty($ils->ilsId)) {
            array_push($ilsData, $ils->toDBArray($this->airacId, $next));
          }
          $ils = new NASR\ILS;
          $ils->fromString1($line);
        }
        if ($recordType == 'ILS2') {
          $ils->fromString2($line);
        }
        if ($recordType == 'ILS3') {
          $gs = new NASR\ILSGS;
          $gs->fromString3($line);
          $ils->hasGs = TRUE;
          $gs->ilsId = $ils->ilsId;
          $gs->airportFacId = $ils->airportFacId;
          if (!is_null($gs->lat)) {
            array_push($ilsGsData, $gs->toDBArray($this->airacId, $next));
          }
        }
        if ($recordType == 'ILS4') {
          $dme = new NASR\ILSDME;
          $dme->fromString4($line);
          $ils->hasDme = TRUE;
          $dme->ilsId = $ils->ilsId;
          $dme->airportFacId = $ils->airportFacId;
          if (!is_null($dme->lat)) {
            array_push($ilsDmeData, $dme->toDBArray($this->airacId, $next));
          }
        }
        if ($recordType == 'ILS5') {
          $mkr = new NASR\ILSMKR;
          $mkr->fromString5($line);
          $ils->hasMkr = TRUE;
          $mkr->ilsId = $ils->ilsId;
          $mkr->airportFacId = $ils->airportFacId;
          if (!is_null($mkr->mkrId)) {
            array_push($ilsMkrData, $mkr->toDBArray($this->airacId, $next));
          }
        }
      }
      fclose($nasrHandle);
      //Delete old data
      ILS::truncate();
      ILSGS::truncate();
      ILSDME::truncate();
      ILSMKR::truncate();
      //Chunk through data and bulk upsert
      foreach (array_chunk($ilsData, 1000) as $d) {
        ILS::insert($d);
      }
      foreach (array_chunk($ilsGsData, 1000) as $d) {
        ILSGS::insert($d);
      }
      foreach (array_chunk($ilsDmeData, 1000) as $d) {
        ILSDME::insert($d);
      }
      foreach (array_chunk($ilsMkrData, 1000) as $d) {
        ILSMKR::insert($d);
      }
      //Update Data Currency
      $this->updateDataCurrency("ILS");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processNavaids()
  {
    $navaidData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("NAV.txt");
    if ($nasrHandle) {
      //Read through lines
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $recordType = substr($line, 0, 4);
        if ($recordType == 'NAV1') {
          $navaid = new NASR\Navaid;
          $navaid->fromString1($line);
          array_push($navaidData, $navaid->toDBArray($this->airacId, $next));
        }
      }
      fclose($nasrHandle);
      //Chunk through data and bulk upsert
      foreach (array_chunk($navaidData, 1000) as $d) {
        Navaid::upsert($d, ["nav_id", "nav_type", "next"]);
      }
      //Update Data Currency
      $this->updateDataCurrency("NAV");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processPreferredRoutes()
  {
    $preferredRouteData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $nasrHandle = $this->getFileHandle("PFR.txt");
    if ($nasrHandle) {
      //Read through lines
      $preferredRoute = new NASR\PreferredRoute;
      while (($line = fgets($nasrHandle)) !== FALSE) {
        $recordType = substr($line, 0, 4);
        if ($recordType == 'PFR1') {
          if (count($preferredRoute->route) > 0) {
            array_push($preferredRouteData, $preferredRoute->toDBArray($this->airacId, $next));
          }
          $preferredRoute = new NASR\PreferredRoute;
          $preferredRoute->fromString1($line);
        }
        if ($recordType == 'PFR2') {
          $preferredRoute->fromString2($line);
        }
      }
      fclose($nasrHandle);
      //Delete old data
      PreferredRoute::truncate();
      //Chunk through data and bulk upsert
      foreach (array_chunk($preferredRouteData, 1000) as $d) {
        PreferredRoute::insert($d);
      }
      //Update Data Currency
      $this->updateDataCurrency("PFR");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }
}
