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
}
