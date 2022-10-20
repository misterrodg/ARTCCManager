<?php

namespace App\Classes\NASR;

use App\Classes\Helpers\TextHelper;

class Boundary
{
  public $artccId;
  public $boundaryId;
  public $altitudeStructure;
  public $boundarySeq;
  public $lat;
  public $lon;
  public $boundaryDes;
  public $isDescOnly;
  public $artccName;

  public function __construct()
  {
    $this->artccId = null;
    $this->boundaryId = null;
    $this->altitudeStructure = null;
    $this->boundarySeq = null;
    $this->lat = null;
    $this->lon = null;
    $this->boundaryDes = null;
    $this->isDescOnly = null;
    $this->artccName = null;
  }

  public function fromString(string $line)
  {
    $texthelper = new TextHelper;
    /*
      This function is a mess, but the commented lines
      have been left in to show the FAA file definition
      in case they should be useful to anyone.
    */
    $artccId = trim(substr($line, 0, 4)); // NOT PART OF OFFICIAL DEF - Added for granularity as a subset of boundaryId
    $boundaryId = trim(substr($line, 0, 12)); // Boundary Record Identifier (ARTCC ID, Alt Structure Code, 5 Char ARTCC Boundary Point)
    $artccName = trim(substr($line, 12, 40)); // Center Name
    $altitudeStructure = trim(substr($line, 52, 10)); // Altitude structure decode name
    $boundaryDes = trim(substr($line, 90, 300)); // Description of boundary line connecting points on boundary
    $boundarySeq = intval(trim(substr($line, 390, 6))); // Sequence identifier (NNNNNN)
    $isDescOnly = trim(substr($line, 396, 1)); // Point used only in the NAS Description and not Legal Description ('X' for true)
    $isDescOnly = ($isDescOnly == 'X') ? 1 : 0;
    $lat = trim(substr($line, 62, 14)); // Fix Lat (NN-NN-NN.NNNA)
    $lon = trim(substr($line, 76, 14)); // Fix Lat (NNN-NN-NN.NNNA)
    if ($lat == '') {
      $lat = null;
      $lon = null;
    } else {
      $coord = $texthelper->handleDMSFormatted($lat, 'DD-MM-SS.SA', $lon, "DDD-MM-SS.SA");
      $lat = $coord->lat;
      $lon = $coord->lon;
    }
    // ASSIGNMENTS TO BOUNDARY OBJECT
    $this->artccId = $artccId;
    $this->boundaryId = $boundaryId;
    $this->altitudeStructure = $altitudeStructure;
    $this->boundarySeq = $boundarySeq;
    $this->lat = $lat;
    $this->lon = $lon;
    $this->boundaryDes = $boundaryDes;
    $this->isDescOnly = $isDescOnly;
    $this->artccName = $artccName;
  }

  public function fromModel(object $dbObject)
  {
    $this->artccId = $dbObject->artcc_id;
    $this->boundaryId = $dbObject->bound_id;
    $this->altitudeStructure = $dbObject->alt_struct;
    $this->boundarySeq = $dbObject->bound_seq;
    $this->lat = $dbObject->bound_lat;
    $this->lon = $dbObject->bound_lon;
    $this->boundaryDes = $dbObject->bound_des;
    $this->isDescOnly = $dbObject->is_desc;
    $this->artccName = $dbObject->artcc_name;
    return $this;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'artcc_id'   => $this->artccId,
      'bound_id'   => $this->boundaryId,
      'alt_struct' => $this->altitudeStructure,
      'bound_seq'  => $this->boundarySeq,
      'bound_lat'  => $this->lat,
      'bound_lon'  => $this->lon,
      'bound_des'  => $this->boundaryDes,
      'is_desc'    => $this->isDescOnly,
      'artcc_name' => $this->artccName,
      'cycle_id'   => $airacId,
      'next'       => $next
    );
    return $result;
  }
}
