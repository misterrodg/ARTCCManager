<?php

namespace App\Classes;

use Exception;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ZIP
{
  public $filePath;

  public function __construct(string $filePath)
  {
    $this->filePath = $filePath;
  }

  public function unzip(?array $pluckFileNames = null)
  {
    $fileDir = pathinfo($this->filePath, PATHINFO_DIRNAME);
    //Proper paths for ZIP open/extract/etc.
    $storageDir = Storage::path($fileDir);
    $storageZIP = Storage::path($this->filePath);

    $zip = new ZipArchive();
    $response = new ResultMessage(FALSE, 'Unzip Failed');
    try {
      $fileResult = $zip->open($storageZIP);
      if ($fileResult === TRUE) {
        //Extract to fileDir and close ZIP
        $zip->extractTo($storageDir, $pluckFileNames);
        $zip->close();
        $response->update(TRUE, 'OK', $fileDir);
      }
    } catch (Exception) {
      $response->update(FALSE, 'Unzip try failed.');
    }
    return $response;
  }
}
