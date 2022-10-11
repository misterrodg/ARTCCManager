<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Classes\CIFP;
use App\Classes\ResponseMessage;

class CIFPController extends Controller
{
  public function getInfo(?string $editionName = "current")
  {
    $cifp = new CIFP($editionName);
    $cifpData = $cifp->getData();

    $success = (intval($cifp->faaResponse->status->code) == 200) ? TRUE : FALSE;
    $response = new ResponseMessage($cifp->faaResponse->status->code, $cifp->faaResponse->status->message, $success, $cifpData);
    $result = $response->toJson();
    return $result;
  }

  public function getDownload(Request $request)
  {
    $cifp = new CIFP($request->editionName, $request->editionDate, $request->editionNumber, $request->editionUrl, $request->airacId);
    $result = $cifp->download("FAACIFP.zip");
    return $result;
  }

  public function decompressDownload(Request $request)
  {
    $cifp = new CIFP($request->editionName, $request->editionDate, $request->editionNumber, $request->editionUrl, $request->airacId);
    $result = $cifp->decompress();
    return $result;
  }

  public function processControlled(Request $request)
  {
    $cifp = new CIFP;
    $cifp->fromLocalFile($request->editionName);
    $result = $cifp->processControlled();
    return $result;
  }

  public function processRestrictive(Request $request)
  {
    $cifp = new CIFP;
    $cifp->fromLocalFile($request->editionName);
    $result = $cifp->processRestrictive();
    return $result;
  }

  public function processProcedures(Request $request)
  {
    $cifp = new CIFP;
    $cifp->fromLocalFile($request->editionName);
    $result = $cifp->processProcedures($request->procedureType);
    return $result;
  }

  public function finalize(Request $request)
  {
    $cifp = new CIFP;
    $cifp->fromLocalFile($request->editionName);
    $result = $cifp->finalize();
    return $result;
  }
}
