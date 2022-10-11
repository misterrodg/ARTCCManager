<?php

namespace App\Classes;

use Illuminate\Support\Facades\Storage;

use App\Classes\ApiCall;

use App\Models\ControlledBoundary;
use App\Models\DataCurrency;
use App\Models\Procedure;
use App\Models\RestrictiveBoundary;

class CIFP
{
  const SID_ID = "PD";
  const STAR_ID = "PE";
  const IAP_ID = "PF";
  const CONTROLLED_ID = "UC";
  const RESTRICTIVE_ID = "UR";

  public $editionName;
  public $editionDate;
  public $editionNumber;
  public $editionUrl;
  public $airacId;
  public $faaResponse;

  public function __construct(?string $editionName = "current", ?string $date = "now", ?string $editionNumber = "", ?string $editionUrl = "", ?string $airacId = "")
  {
    $this->editionName = strtoupper($editionName);
    $this->editionDate = new \DateTime($date);
    $this->editionNumber = $editionNumber;
    $this->editionUrl = $editionUrl;
    $this->airacId = $airacId;
    $this->faaResponse = (object)[];
  }

  public function fromLocalFile(?string $editionName = "current")
  {
    $localFile = Storage::get("cifp/" . $editionName . "/AIRAC.json");
    $jsonData = json_decode($localFile);
    $this->editionName = $jsonData->editionName;
    $this->editionDate = $jsonData->editionDate;
    $this->editionNumber = $jsonData->editionNumber;
    $this->editionUrl = $jsonData->editionUrl;
    $this->airacId = $jsonData->airacId;
    $this->faaResponse = $jsonData->faaResponse;
    return $this;
  }

  public function get()
  {
    return $this;
  }

  public function getData()
  {
    $cifpInfoUrl = env("FAA_CIFP_URL");
    $cifpInfo = new ApiCall($cifpInfoUrl . "?edition=" . $this->editionName);
    $cifpInfo->get("json");
    $jsonObj = $cifpInfo->decode();

    $dtObj = new \DateTime();
    $editionDate = $dtObj->createFromFormat("m/d/Y", $jsonObj->edition[0]->editionDate);

    $this->editionDate = $editionDate->format("Y-m-d");
    $this->editionNumber = $jsonObj->edition[0]->editionNumber;
    $this->editionUrl = $jsonObj->edition[0]->product->url;
    $this->airacId = date("y") . str_pad($this->editionNumber, 2, "0", STR_PAD_LEFT);
    $this->faaResponse = $jsonObj;

    return $this;
  }

  public function download()
  {
    $localZIP = "cifp/" . $this->editionName . "/FAACIFP.zip";
    //Remove old files
    if (Storage::exists($localZIP)) {
      Storage::delete($localZIP);
    }
    //Download file to local CIFP dir
    $faaFile = file_get_contents($this->editionUrl);
    $path = Storage::put($localZIP, $faaFile);
    //Set up responses
    $code = 500;
    $message = "Failed to copy from " . $this->editionUrl . ". Check the CIFP URL in the ENV.";
    $success = FALSE;
    $data = null;
    if ($path != "") {
      $code = 200;
      $message = "OK";
      $success = TRUE;
      $data = $path;
    }
    $response = new ResponseMessage($code, $message, $success, $data);
    return $response->toJson();
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
    $cifpFilePath = "cifp/" . $this->editionName . "/FAACIFP";
    $controlledData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from FAACIFP file
    if (Storage::exists($cifpFilePath)) {
      $cifpFile = Storage::path($cifpFilePath);
      $cifpHandle = fopen($cifpFile, "r");
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
        //Delete old Controlled data
        ControlledBoundary::truncate();
        //Chunk through controlledData array and bulk insert
        foreach (array_chunk($controlledData, 1000) as $d) {
          ControlledBoundary::insert($d);
        }
        //Update Response
        $response->update(200, "OK", TRUE, null);
        DataCurrency::updateOrCreate(
          ["data_id" => "CIFP_CONT", "edition" => $this->editionName],
          ["cycle_id" => $this->airacId, "edition_date" => $this->editionDate->date]
        );
        fclose($cifpHandle);
      }
    }
    return $response->toJson();
  }

  public function processRestrictive()
  {
    $cifpFilePath = "cifp/" . $this->editionName . "/FAACIFP";
    $restrictiveData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from FAACIFP file
    if (Storage::exists($cifpFilePath)) {
      $cifpFile = Storage::path($cifpFilePath);
      $cifpHandle = fopen($cifpFile, "r");
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
        //Delete old Restrictive data
        RestrictiveBoundary::truncate();
        //Chunk through restrictiveData array and bulk insert
        foreach (array_chunk($restrictiveData, 1000) as $d) {
          RestrictiveBoundary::insert($d);
        }
        //Update Response
        $response->update(200, "OK", TRUE, null);
        DataCurrency::updateOrCreate(
          ["data_id" => "CIFP_REST", "edition" => $this->editionName],
          ["cycle_id" => $this->airacId, "edition_date" => $this->editionDate->date]
        );
        fclose($cifpHandle);
      }
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
    $cifpFilePath = "cifp/" . $this->editionName . "/FAACIFP";
    $proceduresData = array();
    $next = ($this->editionName == "NEXT") ? true : false;
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Get data from FAACIFP file
    if (Storage::exists($cifpFilePath)) {
      $cifpFile = Storage::path($cifpFilePath);
      $cifpHandle = fopen($cifpFile, "r");
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
        //Delete old Procedures data
        Procedure::where("proc_type", "=", $procedureId)->delete();
        //Chunk through proceduresData array and bulk insert
        foreach (array_chunk($proceduresData, 1000) as $d) {
          Procedure::insert($d);
        }
        //Update Response
        $response->update(200, "OK", TRUE, null);
        DataCurrency::updateOrCreate(
          ["data_id" => "CIFP_PROC", "edition" => $this->editionName],
          ["cycle_id" => $this->airacId, "edition_date" => $this->editionDate->date]
        );
        fclose($cifpHandle);
      }
    }
    return $response->toJson();
  }

  public function finalize()
  {
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    $cifpData = DataCurrency::updateOrCreate(
      ["data_id" => "CIFP", "edition" => $this->editionName],
      ["cycle_id" => $this->airacId, "edition_date" => $this->editionDate->date]
    );
    if ($cifpData) {
      //Update Response
      $record = DataCurrency::where("data_id", "=", "CIFP")->where("edition", "=", $this->editionName)->first();
      $response->update(200, "OK", TRUE, $record);
    }
    return $response->toJson();
  }
}
