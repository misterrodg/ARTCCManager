<?php

namespace App\Classes;

class CIFPRunway
{
  public $airportId;
  public $runwayId;
  public $length;
  public $bearing;
  public $lat;
  public $lon;
  public $tdze;
  public $dispThreshold;
  public $tch;
  public $width;
  public $tchValueIndicator;
  public $locId;
  public $locClass;
  public $locId2;
  public $locClass2;
  public $icaoRegion;

  public function __construct(string $line)
  {
    $this->airportId = substr($line, 6, 4);
    $this->icaoRegion = substr($line, 10, 2);
    //$subsection_code = substr($line,12,1); // UNUSED
    $this->runwayId = substr($line, 13, 5);
    //Padding/Spacing 18-20
    //$continuation_record_no = substr($line,21,1); // UNUSED
    $this->length = intval(substr($line, 22, 5));
    $this->bearing = intval(substr($line, 27, 4)) / 10;
    //Padding/Spacing 31
    $this->lat = 0;
    $this->lon = 0;
    //$this->gradient = substr($line,51,5); // UNUSED BY FAA
    //Padding/Spacing 57-65
    $this->tdze = intval(substr($line, 66, 6)) / 10;
    $this->dispThreshold = intval(substr($line, 71, 4));
    $this->tch = intval(substr($line, 75, 2));
    $this->width = intval(substr($line, 77, 3));
    $this->tchValueIndicator = substr($line, 80, 1); // I:ILS,R:RNAV,V:VGSI,D:Default(50')
    $this->locId = (trim(substr($line, 81, 4) != '')) ? substr($line, 81, 4) : null;
    $this->locClass = ($this->locId) ? intval(substr($line, 85, 1)) : null;
    //$this->stopway = substr($line,86,4); // UNUSED BY FAA
    $this->locId2 = (trim(substr($line, 90, 4) != '')) ? substr($line, 90, 4) : null;
    $this->locClass2 = ($this->locId2) ? intval(substr($line, 94, 1)) : null;
    //Reserved 95-100
    //$this->runwayDesc = substr($line,101,22); // UNUSED BY FAA
    //$this->fileRecordNo = substr($line,123,5); // UNUSED
    //$this->cycleData = substr($line,128,4); // UNUSED

    $coordinates = new CoordinateHandler;
    $runwayLat = substr($line, 32, 9);
    $runwayLon = substr($line, 41, 10);
    $latLon = $coordinates->dmsToDd($runwayLat, $runwayLon, true);
    $this->lat = $latLon->lat;
    $this->lon = $latLon->lon;
  }

  public function toDBArray(string $airacId)
  {
    $result = array(
      'airport_id'                  => $this->airportId,
      'runway_id'                   => $this->runwayId,
      'runway_length'               => $this->length,
      'runway_width'                => $this->width,
      'runway_bearing'              => $this->bearing,
      'runway_lat'                  => $this->lat,
      'runway_lon'                  => $this->lon,
      'tdze'                        => $this->tdze,
      'disp_thresh'                 => $this->dispThreshold,
      'tch'                         => $this->tch,
      'tch_id'                      => $this->tchValueIndicator,
      'loc_id'                      => $this->locId,
      'loc_class'                   => $this->locClass,
      'loc_id2'                     => $this->locId2,
      'loc_class2'                  => $this->locClass2,
      'region'                      => $this->icaoRegion,
      'AIRAC'                       => $airacId
    );
    return $result;
  }
}
