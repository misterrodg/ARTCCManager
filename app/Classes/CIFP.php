<?php

namespace App\Classes;

use Carbon;
use Illuminate\Support\Facades\Storage;

use App\Classes\ApiCall;

class CIFP
{
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

  public function get()
  {
    return $this;
  }

  public function getData()
  {
    $cifpInfoUrl = env('FAA_CIFP_URL');
    $cifpInfo = new ApiCall($cifpInfoUrl . '?edition=' . $this->editionName);
    $cifpInfo->get('json');
    $jsonObj = $cifpInfo->decode();

    $dtObj = new \DateTime();
    $editionDate = $dtObj->createFromFormat('m/d/Y', $jsonObj->edition[0]->editionDate);

    $this->editionDate = $editionDate->format('Y-m-d H:i:s');
    $this->editionNumber = $jsonObj->edition[0]->editionNumber;
    $this->editionUrl = $jsonObj->edition[0]->product->url;
    $this->airacId = date('y') . str_pad($this->editionNumber, 2, '0', STR_PAD_LEFT);
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
    return $response;
  }

  public function decompress()
  {
    $localCifpDir = "cifp/" . $this->editionName . "/";
    $localZIP = $localCifpDir . "FAACIFP.zip";
    $airacFile = $localCifpDir . "AIRAC.json";
    //Set up responses
    $code = 500;
    $message = "Failed";
    $success = FALSE;
    $data = null;
    //Delete existing files if they exist
    $cifpFileArray = array(
      'FAACIFP18'
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
      $code = 200;
      $message = "OK";
      $success = TRUE;
      $data = null;
    }
    $response = new ResponseMessage($code, $message, $success, $data);
    return $response;
  }
  /*
  public function importCIFPData(string $editionName = 'current',bool $force){
    $cifpFile = new File("/assets/cifp/","FAACIFP18");
    $cifpCurrency = DataCurrency::getDataIdInfo('CIFP');
    if(!empty($cifpCurrency)){
      $expiration = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$cifpCurrency->edition_date)->addDays(28)->startOfDay();
      $this->editionDate = $cifpCurrency->edition_date;
      $this->airacId = $cifpCurrency->cycle_id;
    } else {
      $expiration = Carbon\Carbon::now();
    }
    $date = Carbon\Carbon::now()->startOfDay();
    $updateRequired = (!$cifpFile->exists() || (empty($cifpCurrency)) || $date >= $expiration) ? TRUE : FALSE;
    if($updateRequired || $editionName == 'next' || $force){
      $this->downloadCIFPData($editionName);
    }
    $passData = array();
    $procedureCount = 0;
    $controlledCount = 0;
    $restrictiveCount = 0;
    $firstPass = TRUE;
    $sectionPasses = array('PD','PE','PF','UC','UR');
    foreach($sectionPasses as $sp){
      $cifpHandle = $cifpFile->read();
      if($cifpHandle){
        $lineNo = 1;
        $passSectionCode = substr($sp,0,1);
        $passSubSectionCode = substr($sp,-1,1);
        while(($line = fgets($cifpHandle)) !== FALSE){
          $sectionCode = substr($line,4,1);
          switch($passSectionCode){
            case 'P':$subSectionCode = substr($line,12,1);break;
            case 'U':$subSectionCode = substr($line, 5,1);break;
          }
          //Process Lines
          if(
            ($sectionCode.$subSectionCode == 'PD' && $sp == 'PD') ||
            ($sectionCode.$subSectionCode == 'PE' && $sp == 'PE') ||
            ($sectionCode.$subSectionCode == 'PF' && $sp == 'PF')
          ){
            //SIDs/STARs/APPs
            $procedure = new CIFP\Procedure($line);
            if(!is_null($procedure->toDBArray($this->airacId))){
              array_push($passData,$procedure->toDBArray($this->airacId));
            }
            $procedureCount++;
          }
          if($sectionCode.$subSectionCode == 'UC' && $sp == 'UC'){
            //Controlled Airspace
            $controlled = new CIFP\Controlled($line);
            array_push($passData,$controlled->toDBArray($this->airacId));
            $controlledCount++;
          }
          if($sectionCode.$subSectionCode == 'UR' && $sp == 'UR'){
            //Restrictive Airspace
            $restrictive = new CIFP\Restrictive($line);
            array_push($passData,$restrictive->toDBArray($this->airacId));
            $restrictiveCount++;
          }
          $lineNo++;
        }
        if($sp == 'PD' || $sp == 'PE' || $sp == 'PF'){
          GeoData::deleteProceduresByType(substr($sp,-1));
          foreach(array_chunk($passData,1000) as $p){
            GeoData::insertProcedures($p);
          }
          DataCurrency::updateOrInsertDataId('CIFP_PROC',$this->airacId,$this->editionDate,date('Y-m-d H:i:s'));
        }
        if($sp == 'UC'){
          GeoData::deleteControlled();
          foreach(array_chunk($passData,1000) as $p){
            GeoData::insertControlleds($p);
          }
          DataCurrency::updateOrInsertDataId('CIFP_CONT',$this->airacId,$this->editionDate,date('Y-m-d H:i:s'));
        }
        if($sp == 'UR'){
          GeoData::deleteRestrictive();
          foreach(array_chunk($passData,1000) as $p){
            GeoData::insertRestrictives($p);
          }
          DataCurrency::updateOrInsertDataId('CIFP_REST',$this->airacId,$this->editionDate,date('Y-m-d H:i:s'));
        }
        $passData = array();
        fclose($cifpHandle);
      }
      $firstPass = FALSE;
    }

    echo "Procedures: ".$procedureCount."<br/>";
    echo "Controlled Airspace: ".$controlledCount."<br/>";
    echo "Restricted Records: ".$restrictiveCount."<br/><br/>";
    echo "AIRAC ".$this->airacId." import complete at ".date(sprintf('Y-m-d\TH:i:s%sP', substr(microtime(), 1, 8)))."<br/>";
  }
  */
}
