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
    return $response->toJson();
  }

  public function getDownload(Request $request)
  {
    $nasr = new NASR($request->editionName, $request->editionDate, $request->editionNumber, $request->editionUrl, $request->airacId);
    $response = $nasr->download();
    return $response->toJson();
  }

  public function decompressDownload(Request $request)
  {
    $nasr = new NASR($request->editionName, $request->editionDate, $request->editionNumber, $request->editionUrl, $request->airacId);
    $response = $nasr->decompress();
    return $response->toJson();
  }
}
