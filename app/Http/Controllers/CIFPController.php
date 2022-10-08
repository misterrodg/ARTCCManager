<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Classes\CIFP;
use App\Classes\ResponseMessage;

class CIFPController extends Controller
{
  public function getInfo($version = "current")
  {
    $cifp = new CIFP($version);
    $cifpData = $cifp->getData();

    $success = (intval($cifp->faaResponse->status->code) == 200) ? TRUE : FALSE;
    $response = new ResponseMessage($cifp->faaResponse->status->code, $cifp->faaResponse->status->message, $success, $cifpData);
    return $response->toJson();
  }

  public function getDownload(Request $request)
  {
    $cifp = new CIFP($request->version, $request->editionDate, $request->editionNumber, $request->editionUrl, $request->airacId);
    $response = $cifp->download();
    return $response->toJson();
  }

  public function decompressDownload(Request $request)
  {
    $cifp = new CIFP($request->version, $request->editionDate, $request->editionNumber, $request->editionUrl, $request->airacId);
    $response = $cifp->decompress();
    return $response->toJson();
  }
}
