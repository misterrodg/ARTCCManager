<?php

namespace App\Classes;

use Illuminate\Support\Facades\Storage;

use App\Classes\ApiCall;

use App\Models\DataCurrency;

class Import
{
  public $importType;
  public $envName;
  public $editionName;
  public $editionDate;
  public $editionNumber;
  public $editionUrl;
  public $airacId;
  public $faaResponse;

  public function __construct(string $importType, ?string $envName = "", ?string $editionName = "current", ?string $date = "now", ?string $editionNumber = "", ?string $editionUrl = "", ?string $airacId = "")
  {
    $this->importType = strtoupper($importType);
    $this->envName = $envName;
    $this->editionName = strtoupper($editionName);
    $this->editionDate = new \DateTime($date);
    $this->editionNumber = $editionNumber;
    $this->editionUrl = $editionUrl;
    $this->airacId = $airacId;
    $this->faaResponse = (object)[];
  }

  public function download(string $fileName)
  {
    $localZIP = strtolower($this->importType) . "/" . $this->editionName . "/" . $fileName;
    //Remove old files
    if (Storage::exists($localZIP)) {
      Storage::delete($localZIP);
    }
    //Download file to local CIFP dir
    $faaFile = file_get_contents($this->editionUrl);
    $path = Storage::put($localZIP, $faaFile);
    //Set up responses
    $code = 500;
    $message = "Failed to copy from " . $this->editionUrl . ". Check the " . $this->importType . " URL in the ENV.";
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

  public function fromLocalFile(?string $editionName = "current")
  {
    $airacFile = strtolower($this->importType) . "/" . $editionName . "/AIRAC.json";
    if (Storage::exists($airacFile)) {
      $localFile = Storage::get($airacFile);
      $jsonData = json_decode($localFile);
      $this->editionName = $jsonData->editionName;
      $this->editionDate = $jsonData->editionDate;
      $this->editionNumber = $jsonData->editionNumber;
      $this->editionUrl = $jsonData->editionUrl;
      $this->airacId = $jsonData->airacId;
      $this->faaResponse = $jsonData->faaResponse;
      return $this;
    }
    return false;
  }

  public function getData()
  {
    $cifpInfoUrl = env($this->envName);
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

  public function getFileHandle(string $fileName)
  {
    //Gets data from file and returns an iterable handle
    $filePath = strtolower($this->importType) . "/" . $this->editionName . "/" . $fileName;
    if (Storage::exists($filePath)) {
      $file = Storage::path($filePath);
      $fileHandle = fopen($file, "r");
      return $fileHandle;
    }
    return false;
  }

  public function updateDataCurrency(?string $dataId = null)
  {
    $dataIdentifier = $this->importType;
    if (!is_null($dataId)) {
      $dataIdentifier .= "_" . $dataId;
    }
    $result = DataCurrency::updateOrCreate(
      ["data_id" => $dataIdentifier, "edition" => $this->editionName],
      ["cycle_id" => $this->airacId, "edition_date" => $this->editionDate->date]
    );
    return $result;
  }

  public function finalize()
  {
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    $data = $this->updateDataCurrency();
    if ($data) {
      //Update Response
      $record = DataCurrency::where("data_id", "=", strtoupper($this->importType))->where("edition", "=", $this->editionName)->first();
      $response->update(200, "OK", TRUE, $record);
    }
    return $response->toJson();
  }
}
