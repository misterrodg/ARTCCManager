<?php

namespace App\Classes\Alias;

use App\Classes\ResponseMessage;
use App\Models\Airport;
use App\Models\Alias;
use App\Models\AWOS;
use App\Models\DataCurrency;
use Illuminate\Support\Facades\Storage;

class AliasFile
{
  public $artccId;
  public $artccName;
  public $contact;
  public $edition;
  public $airacId;
  public $supArray;
  public $aliasFileName;
  public $aliasFileArray;

  public function __construct(?bool $next = false, ?bool $includeSup = false)
  {
    $this->artccId = env("ARTCC_ID");
    $this->artccName = env("ARTCC_Name");
    $this->contact = env("ARTCC_Contact");
    $this->edition = ($next) ? "NEXT" : "CURRENT";
    $this->airacId = DataCurrency::where('data_id', '=', 'NASR')->where('edition', '=', $this->edition)->select('cycle_id')->first();
    if ($includeSup) {
      $supFileString = "_SUP";
      $this->supArray = [0, 1];
    } else {
      $supFileString = "";
      $this->supArray = [0];
    }
    $this->aliasFileName = "facfiles/" . $this->artccId . "_Alias" . $supFileString . ".txt";
    $this->aliasFileArray = array();
  }

  public function fromUpload($fileData)
  {
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    $tempAliasPath = "temp/aliasFile.txt";
    if (Storage::exists($tempAliasPath)) {
      Storage::delete($tempAliasPath);
    }
    $fileData->storeAs("temp/", "aliasFile.txt");
    if (Storage::exists($tempAliasPath)) {
      $file = Storage::path($tempAliasPath);
      $fileHandle = fopen($file, "r");
      while (($line = fgets($fileHandle)) !== FALSE) {
        if (substr($line, 0, 1) == ".") {
          $lineArray = explode(" ", $line, 2);
          $dotCommand = substr($lineArray[0], 1);
          $replaceWith = preg_replace("/\r|\n/", "", $lineArray[1]);
          Alias::insertOrIgnore(
            ["dot_command" => $dotCommand, "replace_with" => $replaceWith]
          );
        }
      }
    }
    $aliases = Alias::all();
    $response->update(200, "OK", TRUE, $aliases);
    $result = $response->toJson();
    return $result;
  }

  public function toFile()
  {
    Storage::put($this->aliasFileName, "");
    if (Storage::exists($this->aliasFileName)) {
      $file = Storage::path($this->aliasFileName);
      file_put_contents($file, $this->aliasFileArray);
    }
  }

  public function buildPreamble()
  {
    $string =
      '; ////// Virtual ' . $this->artccName . ' ARTCC (' . $this->artccId . ') Alias File for VRC \\\\\\' . "\r\n" .
      '; AIRAC ' . $this->airacId . "\r\n" .
      '; Last Updated: ' . date(sprintf('Y-m-d\TH:i:s%sP', substr(microtime(), 1, 8))) . "\r\n" .
      '; Contact: ' . $this->contact . "\r\n" .
      '; This file may only be distributed by the Virtual ' . $this->artccName . ' ARTCC and is provided as is, and any modification is at your risk.' . "\r\n" .
      '; ' . "\r\n" .
      '; Controllers are encouraged to develop their own aliases to personalize their text-based communication.' . "\r\n" .
      '; The following variables are available in VRC, vSTARS, and vERAM:' . "\r\n" .
      '; $squawk        squawk code assigned to the radioselected aircraft' . "\r\n" .
      '; $arr           arrival airport of the radioselected aircraft' . "\r\n" .
      '; $dep           departure airport of the radioselected aircraft' . "\r\n" .
      '; $cruise        assigned cruise altitude of the radioselected aircraft' . "\r\n" .
      '; $temp          assigned temporary altitude of the radioselected aircraft' . "\r\n" .
      '; $alt           assigned temporary altitude, if it exists, otherwise returns the assigned cruise altitude of the radioselected aircraft' . "\r\n" .
      '; $calt          current altitude of the radioselected aircraft' . "\r\n" .
      '; $callsign      controller callsign' . "\r\n" .
      '; $com1          primary radio frequency of the controller' . "\r\n" .
      '; $myrealname    real name of the controller' . "\r\n" .
      '; $winds         wind at the destination airport if airspeed >= 30 KTS, and departure airport if <30KTS' . "\r\n" .
      '; $myrw          complete voice server and frequency' . "\r\n" .
      '; $mypvtrw       complete voice server and frequency, but hides it from serveinfo' . "\r\n" .
      '; $metar(ICAO)   metar at the ICAO airport in the parentheses' . "\r\n" .
      '; $altim(ICAO)   altimeter setting at the ICAO airport in parentheses' . "\r\n" .
      '; $wind(ICAO)    wind at the ICAO airport in parentheses' . "\r\n" .
      '; $dist(POINT)   distance to the POINT (airport, VOR, NDB, or FIX in parentheses)' . "\r\n" .
      '; $oclock(POINT) clock position of the POINT (airport, VOR, NDB or FIX in parentheses)' . "\r\n" .
      '; $bear(POINT)   cardinal compass direction from the POINT (airport, VOR, NDB, or FIX in parentheses)' . "\r\n" .
      '; $radioname(CC) full spoken radio name of controller CC, yours if empty parentheses' . "\r\n" .
      '; $freq(CC)      radio frequency of controller CC, yours if empty parentheses' . "\r\n" .
      '; $metar($arr)   METAR of the arrival airport for the selected aircraft' . "\r\n";
    array_push($this->aliasFileArray, $string);
  }

  public function buildAliasesFromDB()
  {
    $aliases = Alias::whereIn('is_sup_only', $this->supArray)->orderBy('type')->orderBy('dot_command')->get();
    $currentSection = "";
    foreach ($aliases as $a) {
      if ($currentSection != $a->type) {
        $string =
          ";-----------------------------" . "\r\n" .
          ";" . $a->type . " Aliases" . "\r\n" .
          ";-----------------------------" . "\r\n";
        array_push($this->aliasFileArray, $string);
      }
      array_push($this->aliasFileArray, $a->dot_command . ' ' . $a->replace_with . "\r\n");
      $currentSection = $a->type;
    }
  }

  public function buildCIFPAliases()
  {
    $string =
      ";=============================" . "\r\n" .
      ";-----------------------------" . "\r\n" .
      ";CIFP-GENERATED ALIAS COMMANDS" . "\r\n" .
      ";-----------------------------" . "\r\n" .
      ";=============================" . "\r\n";
    array_push($this->aliasFileArray, $string);
    $this->buildAltimeterSettings();
    $this->buildPreferredRoutes();
  }

  private function buildAltimeterSettings()
  {
    $string =
      ";-----------------------------" . "\r\n" .
      ";Altimeter Aliases" . "\r\n" .
      ";-----------------------------" . "\r\n";
    array_push($this->aliasFileArray, $string);
    $stations = AWOS::with('airport')->whereRelation('airport', 'artcc_id', '=', $this->artccId)->get();
    foreach ($stations as $s) {
      array_push($this->aliasFileArray, '.' . strtolower($s->faa_id) . 'alt ' .
        $s->airport->name . ' altimeter $altim(' . $s->awos_id . ')' . "\r\n");
    }
  }

  private function buildPreferredRoutes()
  {
    $string =
      ";-----------------------------" . "\r\n" .
      ";Preferred Route Aliases" . "\r\n" .
      ";-----------------------------" . "\r\n";
    array_push($this->aliasFileArray, $string);
    $airports = Airport::with('preferred_routes')->where('artcc_id', '=', $this->artccId)->get();
    foreach ($airports as $a) {
      $routeArray = array();
      $lastDest = "";
      foreach ($a->preferred_routes as $pk => $pv) {
        $route = $pv->route;
        $hours1 = ($pv->hours1 != '') ? $pv->hours1 : '';
        $hours2 = ($pv->hours2 != '') ? $pv->hours2 : '';
        $hours3 = ($pv->hours3 != '') ? $pv->hours3 : '';
        $hoursString = ($pv->hours1 != '' | $pv->hours2 != '' | $pv->hours3 != '') ? '(' . implode(',', array_filter(array($hours1, $hours2, $hours3))) . ')' : '';
        $type = ($pv->type != '') ? '(' . $pv->type . ')' : '';
        $area = ($pv->area != '') ? '(' . $pv->area . ')' : '';
        $altitude = ($pv->altitude != '') ? '(' . $pv->altitude . ')' : '';
        $aircraft = ($pv->aircraft != '') ? '(' . $pv->aircraft . ')' : '';
        $direction = ($pv->direction != '') ? '(' . $pv->direction . ')' : '';
        $routeString = $route . ' ' . $hoursString . $type . $area . $altitude . $aircraft . $direction;
        if ($lastDest == "" || $lastDest == $pv->dest) {
          array_push($routeArray, $routeString);
        } else {
          array_push($this->aliasFileArray, '.' . strtolower($pv->orig) . strtolower($pv->dest) . ' .msg PREF_RTES :: ' . implode(" | ", $routeArray) . "\r\n");
        }
        $lastDest = $pv->dest;
      }
    }
  }
}
