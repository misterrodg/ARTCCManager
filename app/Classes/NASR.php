<?php

namespace App\Classes;

use Carbon;
use Illuminate\Support\Facades\Storage;

use App\Classes\ApiCall;

class NASR
{
  public $editionName;
  public $editionDate;
  public $editionNumber;
  public $editionUrl;
  public $airacId;
  public $faaResponse;

  public function __construct(?string $version = "current", ?string $date = "now", ?string $editionNumber = "", ?string $editionUrl = "", ?string $airacId = "")
  {
    $this->editionName = strtoupper($version);
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
    $nasrInfoUrl = env('FAA_NASR_URL');
    $nasrInfo = new ApiCall($nasrInfoUrl . '?edition=' . $this->editionName);
    $nasrInfo->get('json');
    $jsonObj = $nasrInfo->decode();

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
    $localZIP = "nasr/" . $this->editionName . "/FAANASR.zip";
    //Remove old files
    if (Storage::exists($localZIP)) {
      Storage::delete($localZIP);
    }
    //Download file to local NASR dir
    $faaFile = file_get_contents($this->editionUrl);
    $path = Storage::put($localZIP, $faaFile);
    //Set up responses
    $code = 500;
    $message = "Failed to copy from " . $this->editionUrl . ". Check the NASR URL in the ENV.";
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
    $localNasrDir = "nasr/" . $this->editionName . "/";
    $localZIP = $localNasrDir . "FAANASR.zip";
    $airacFile = $localNasrDir . "AIRAC";
    //Set up responses
    $code = 500;
    $message = "Failed";
    $success = FALSE;
    $data = null;
    //Delete existing files if they exist
    $nasrFileArray = array(
      'AFF.txt', 'APT.txt', 'ARB.txt', 'ATS.txt', 'AWOS.txt', 'AWY.txt', 'CDR.txt', 'COM.txt', 'FIX.txt', 'FSS.txt', 'HPF.txt', 'ILS.txt', 'LID.txt', 'MAA.txt', 'MTR.txt', 'NAV.txt', 'PFR.txt', 'PJA.txt', 'STARDP.txt', 'TWR.txt', 'WXL.txt'
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
      Storage::put($airacFile, $this->airacId);
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
    public function importNASRData(string $version = 'current', bool $force = false)
    {
        $localNasrDir = "/assets/nasr/";
        $nasrDir = new DirPath($localNasrDir);
        $nasrFiles = $nasrDir->scanFilesInDir(array('txt'));
        $nasrCurrency = DataCurrency::getDataIdInfo('NASR');
        if (!empty($nasrCurrency)) {
            $expiration = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $nasrCurrency->edition_date)->addDays(28)->startOfDay();
            $this->editionDate = $nasrCurrency->edition_date;
            $this->airacId = $nasrCurrency->cycle_id;
        } else {
            $expiration = Carbon\Carbon::now();
        }
        $date = Carbon\Carbon::now()->startOfDay();
        $updateRequired = (count($nasrFiles) == 0 || (empty($nasrCurrency)) || $date >= $expiration) ? TRUE : FALSE;
        if ($updateRequired || $version == 'next' || $force) {
            $this->downloadNASRData($version);
        }

        if (!$this->downloadFailed) {
            foreach ($nasrFiles as $nf) {
                $thisFile = new File($nasrDir->relPath, $nf);
                switch ($nf) {
                        //case 'AFF.txt':     $affFile = new NASR\FileAFF($thisFile,$this->airacId,$this->editionDate);break; // [TODO] // ARTCC Freqs (For XMTR Audits)
                    case 'AWY.txt':
                        $awyFile = new NASR\FileAWY($thisFile, $this->airacId, $this->editionDate);
                        break;
                    case 'APT.txt':
                        $aptFile = new NASR\FileAPT($thisFile, $this->airacId, $this->editionDate);
                        break;
                    case 'ARB.txt':
                        $arbFile = new NASR\FileARB($thisFile, $this->airacId, $this->editionDate);
                        break;
                    case 'ATS.txt':
                        $atsFile = new NASR\FileATS($thisFile, $this->airacId, $this->editionDate);
                        break;
                    case 'AWOS.txt':
                        $awosFile = new NASR\FileAWOS($thisFile, $this->airacId, $this->editionDate);
                        break;
                    case 'CDR.txt':
                        $cdrFile = new NASR\FileCDR($thisFile, $this->airacId, $this->editionDate);
                        break;
                        //////case 'COM.txt':     $comFile = new NASR\FileCOM($thisFile,$this->airacId,$this->editionDate);break; // IGNORED
                    case 'FIX.txt':
                        $fixFile = new NASR\FileFIX($thisFile, $this->airacId, $this->editionDate);
                        break;
                        //////case 'FSS.txt':     $fssFile = new NASR\FileFSS($thisFile,$this->airacId,$this->editionDate);break; // IGNORED
                        //case 'HPF.txt':     $hpfFile = new NASR\FileHPF($thisFile,$this->airacId,$this->editionDate);break; // [TODO] // Holds
                    case 'ILS.txt':
                        $ilsFile = new NASR\FileILS($thisFile, $this->airacId, $this->editionDate);
                        break; // ILS Data
                        //////case 'LID.txt':     $lidFile = new NASR\FileLID($thisFile,$this->airacId,$this->editionDate);break; // IGNORED
                        //case 'MAA.txt':     $maaFile = new NASR\FileMAA($thisFile,$this->airacId,$this->editionDate);break; // [TODO] // Misc Activity Areas
                        //case 'MTR.txt':     $mtrFile = new NASR\FileMTR($thisFile,$this->airacId,$this->editionDate);break; // [TODO] // Mil Training Routes
                    case 'NAV.txt':
                        $navFile = new NASR\FileNAV($thisFile, $this->airacId, $this->editionDate);
                        break;
                    case 'PFR.txt':
                        $pfrFile = new NASR\FilePFR($thisFile, $this->airacId, $this->editionDate);
                        break;
                        //case 'PJA.txt':     $pjaFile = new NASR\FilePJA($thisFile,$this->airacId,$this->editionDate);break; // [TODO] // Para Jump Areas
                        //////case 'STARDP.txt':  $stardpFile = new NASR\FileSTARDP($thisFile,$this->airacId,$this->editionDate);break; // IGNORED IN FAVOR OF CIFP
                        //case 'TWR.txt':     $twrFile = new NASR\FileTWR($thisFile,$this->airacId,$this->editionDate);break; // [TODO] // Terminal Freqs (For XMTR Audits)
                        //case 'WXL.txt':      $wxlFile = new NASR\FileWXL($thisFile,$this->airacId,$this->editionDate);break; // [TODO] // Additional WX reporting (upper wind, etc)
                }
            }

            //echo "ARTCC Facilities: ".$affFile->artccCount."<br/>";
            echo "NAVAIDs: " . $navFile->navCount . "<br/>";
            echo "Fixes: " . $fixFile->fixCount . "<br/>";
            echo "Airways (Regulatory): " . $awyFile->awyCount . "<br/>";
            echo "Airways (Non-Regulatory): " . $atsFile->atsCount . "<br/>";
            echo "Landing Facilities: " . $aptFile->landingFacilityCount . "<br/>";
            echo "Runways: " . $aptFile->runwayCount . "<br/>";
            echo "ILS/LOCs: " . $ilsFile->ilsCount . "<br/>";
            echo "AWOS Facilities: " . $awosFile->stationCount . "<br/>";
            //echo "Weather Facilities: ".$wxlFile->stationCount."<br/>";
            echo "Boundaries: " . $arbFile->boundaryCount . "<br/>";
            echo "PREFs: " . $pfrFile->prfCount . "<br/>";
            echo "CDRs: " . $cdrFile->cdrCount . "<br/>";
            //echo "Holds: ".$hpfFile->holdCount."<br/>";
            //echo "Misc Activity Areas: ".$maaFile->maaCount."<br/>";
            //echo "Para Areas: ".$pjaFile->paraCount."<br/>";
            //echo "Tower Facilities: ".$twrFile->towerCount."<br/><br/>";
            //////echo "Procedures: ".$stardpFile->procedureCount."<br/>"; // IGNORED IN FAVOR OF CIFP
            //////echo "FSS Communication Facilities: ".$comFile->commCount."<br/>"; // IGNORED
            echo "<br/>AIRAC " . $this->airacId . " import complete at " . date(sprintf('Y-m-d\TH:i:s%sP', substr(microtime(), 1, 8))) . "<br/>";
        } else {
            echo "No response from data source.";
        }
    }
    */
}
