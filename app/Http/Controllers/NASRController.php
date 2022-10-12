<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Classes\NASR;
use App\Classes\ResponseMessage;

class NASRController extends Controller
{
  public function getInfo($editionName = "current")
  {
    $nasr = new NASR($editionName);
    $nasrData = $nasr->getData();

    $success = (intval($nasr->faaResponse->status->code) == 200) ? TRUE : FALSE;
    $response = new ResponseMessage($nasr->faaResponse->status->code, $nasr->faaResponse->status->message, $success, $nasrData);
    $result = $response->toJson();
    return $result;
  }

  public function getDownload(Request $request)
  {
    $nasr = new NASR($request->editionName, $request->editionDate, $request->editionNumber, $request->editionUrl, $request->airacId);
    $result = $nasr->download("FAANASR.zip");
    return $result;
  }

  public function decompressDownload(Request $request)
  {
    $nasr = new NASR($request->editionName, $request->editionDate, $request->editionNumber, $request->editionUrl, $request->airacId);
    $result = $nasr->decompress();
    return $result;
  }

  public function processAirports(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result = $nasr->processAirports();
    return $result;
  }

  public function processAirways(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result = $nasr->processAirways();
    return $result;
  }

  public function processAirwaysAts(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result = $nasr->processAirwaysAts();
    return $result;
  }

  public function processAwos(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result = $nasr->processAwos();
    return $result;
  }

  public function processBoundaries(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result = $nasr->processBoundaries();
    return $result;
  }

  public function processCodedRoutes(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result = $nasr->processCodedRoutes();
    return $result;
  }

  public function processFixes(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result  = $nasr->processFixes();
    return $result;
  }

  public function processILS(Request $request)
  {
    $request->editionName = "CURRENT";
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result = $nasr->processILS();
    return $result;
  }

  public function processNavaids(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result  = $nasr->processNavaids();
    return $result;
  }

  public function processPreferredRoutes(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result = $nasr->processPreferredRoutes();
    return $result;
  }

  public function finalize(Request $request)
  {
    $nasr = new NASR;
    $nasr->fromLocalFile($request->editionName);
    $result = $nasr->finalize();
    return $result;
  }
}
