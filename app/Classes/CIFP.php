<?php

namespace App\Classes;

use Illuminate\Support\Facades\Storage;

use App\Models\ControlledBoundary;
use App\Models\Procedure;
use App\Models\RestrictiveBoundary;

class CIFP extends Import
{
  const IMPORT_TYPE = "CIFP";
  const IMPORT_URL = "FAA_CIFP_URL";
  const SID_ID = "PD";
  const STAR_ID = "PE";
  const IAP_ID = "PF";
  const CONTROLLED_ID = "UC";
  const RESTRICTIVE_ID = "UR";

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
    $localCifpDir = "cifp/" . $this->editionName . "/";
    $localZIP = $localCifpDir . "FAACIFP.zip";
    $airacFile = $localCifpDir . "AIRAC.json";
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Delete existing files if they exist
    $cifpFileArray = array(
      "FAACIFP18"
    );
    $faaFile = $localCifpDir . "FAACIFP18";
    $localFile = $localCifpDir . "FAACIFP";
    if (Storage::exists($localFile)) {
      Storage::delete($localFile);
    }
    //Unzip file
    $cifpFileZip = new ZIP($localZIP);
    $zipResponse = $cifpFileZip->unzip($cifpFileArray);
    //If unzipped, rename file and delete ZIP
    if ($zipResponse->isSuccess) {
      //Add AIRAC Data
      Storage::delete($airacFile);
      Storage::put($airacFile, json_encode($this));
      //Rename
      Storage::move($faaFile, $localFile);
      //Delete ZIP
      Storage::delete($localZIP);
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processControlled()
  {
    $controlledData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $cifpHandle = $this->getFileHandle("FAACIFP");
    if ($cifpHandle) {
      //Read through lines for Controlled lines
      while (($line = fgets($cifpHandle)) !== FALSE) {
        $sectionCode = substr($line, 4, 1);
        $subSectionCode = substr($line, 5, 1);
        if ($sectionCode . $subSectionCode == self::CONTROLLED_ID) {
          //Controlled Airspace
          $controlled = new CIFP\Controlled;
          $controlled->fromString($line);
          array_push($controlledData, $controlled->toDBArray($this->airacId, $next));
        }
      }
      fclose($cifpHandle);
      //Delete old data data
      ControlledBoundary::truncate();
      //Chunk through data and bulk insert
      foreach (array_chunk($controlledData, 1000) as $d) {
        ControlledBoundary::insert($d);
      }
      //Update Data Currency
      $this->updateDataCurrency("CONT");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processRestrictive()
  {
    $restrictiveData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $cifpHandle = $this->getFileHandle("FAACIFP");
    if ($cifpHandle) {
      //Read through lines for Restrictive lines
      while (($line = fgets($cifpHandle)) !== FALSE) {
        $sectionCode = substr($line, 4, 1);
        $subSectionCode = substr($line, 5, 1);
        if ($sectionCode . $subSectionCode == self::RESTRICTIVE_ID) {
          //Restrictive Airspace
          $restrictive = new CIFP\Restrictive;
          $restrictive->fromString($line);
          array_push($restrictiveData, $restrictive->toDBArray($this->airacId, $next));
        }
      }
      fclose($cifpHandle);
      //Delete old data
      RestrictiveBoundary::truncate();
      //Chunk through data and bulk insert
      foreach (array_chunk($restrictiveData, 1000) as $d) {
        RestrictiveBoundary::insert($d);
      }
      //Update Data Currency
      $this->updateDataCurrency("REST");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function processProcedures(string $procedureType)
  {
    switch ($procedureType) {
      case "sid":
        $procedureId = self::SID_ID;
        break;
      case "star":
        $procedureId = self::STAR_ID;
        break;
      case "iap":
        $procedureId = self::IAP_ID;
        break;
    }
    $proceduresData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from file
    $cifpHandle = $this->getFileHandle("FAACIFP");
    if ($cifpHandle) {
      //Read through lines for Procedures lines
      while (($line = fgets($cifpHandle)) !== FALSE) {
        $sectionCode = substr($line, 4, 1);
        $subSectionCode = substr($line, 12, 1);
        if ($sectionCode . $subSectionCode == $procedureId) {
          //Procedure
          $procedure = new CIFP\Procedure;
          $procedure->fromString($line);
          if (!is_null($procedure->toDBArray($this->airacId))) {
            array_push($proceduresData, $procedure->toDBArray($this->airacId, $next));
          }
        }
      }
      fclose($cifpHandle);
      //Delete old data
      Procedure::where("proc_type", "=", $procedureId)->delete();
      //Chunk through data and bulk insert
      foreach (array_chunk($proceduresData, 1000) as $d) {
        Procedure::insert($d);
      }
      //Update Data Currency
      $this->updateDataCurrency("PROC");
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }
}
