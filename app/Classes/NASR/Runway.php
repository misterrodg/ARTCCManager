<?php

namespace App\Classes\NASR;

class Runway
{
  public $airportFacId;
  public $runwayId;
  public $length;
  public $width;
  public $surface;
  public $surfaceCond;
  public $baseId;
  public $baseLat;
  public $baseLon;
  public $baseTrue;
  public $baseElev;
  public $baseTch;
  public $baseGpa;
  public $baseDThDist;
  public $baseTdze;
  public $baseVgsi;
  public $baseProc;
  public $recipId;
  public $recipLat;
  public $recipLon;
  public $recipTrue;
  public $recipElev;
  public $recipTch;
  public $recipGpa;
  public $recipDThDist;
  public $recipTdze;
  public $recipVgsi;
  public $recipProc;

  public function __construct()
  {
    $this->airportFacId = null;
    $this->runwayId = null;
    $this->length = null;
    $this->width = null;
    $this->surface = null;
    $this->surfaceCond = null;
    $this->baseId = null;
    $this->baseLat = null;
    $this->baseLon = null;
    $this->baseTrue = null;
    $this->baseElev = null;
    $this->baseTch = null;
    $this->baseGpa = null;
    $this->baseDThDist = null;
    $this->baseTdze = null;
    $this->baseVgsi = null;
    $this->baseProc = null;
    $this->recipId = null;
    $this->recipLat = null;
    $this->recipLon = null;
    $this->recipTrue = null;
    $this->recipElev = null;
    $this->recipTch = null;
    $this->recipGpa = null;
    $this->recipDThDist = null;
    $this->recipTdze = null;
    $this->recipVgsi = null;
    $this->recipProc = null;
  }

  public function fromString($line)
  {
    //$recordType = trim(substr($line,0,3)); // RWY // IGNORED;
    $airportFacId = trim(substr($line, 3, 11)); // FAA Landing Facility Site Number (Example: 04508.*A)
    //$assocStateAbbrev = trim(substr($line,14,2)); // Associated State Post Office Code // IGNORED
    $runwayId = trim(substr($line, 16, 7)); // Runway ID
    $length = intval(trim(substr($line, 23, 5))); // Runway length (nearest foot)
    $width = intval(trim(substr($line, 28, 4))); // Runway width (nearest foot)
    $surface = trim(substr($line, 32, 12)); // Surface type and condition
    $surfaceType = NULL;
    $surfArray = explode('-', $surface);
    $hardArray = array('CONC', 'ASPH', 'MATS');
    $softArray = array('SNOW', 'ICE', 'TREATED', 'GRVL', 'GRAVEL', 'TURF', 'DIRT');
    $condArray = array('E', 'G', 'F', 'P', 'L');
    foreach ($surfArray as $sa) {
      //Process Surfaces
      if (is_null($surfaceType)) {
        if ($sa == 'WATER') {
          $surfaceType = 'W';
        } else if (in_array($sa, $softArray)) {
          $surfaceType = 'S';
        } else if (in_array($sa, $hardArray)) {
          $surfaceType = 'H';
        } else {
          $surfaceType = 'U';
        }
      }
      //Process Conditions
      if (in_array($sa, $condArray)) {
        $surfaceCond = $sa;
      } else {
        $surfaceCond = 'U';
      }
    }
    //CONC - CONCRETE, ASPH - ASPHALT OR BITUMINOUS CONCRETE, SNOW - SNOW, ICE - ICE, MATS - PIERCED STEEL PLANKING, TREATED - OILED
    //GRAVEL - GRAVEL/CINDERS/CRUSHED ROCK/CORAL/SHELLS/SLAG, TURF - GRASS/SOD, DIRT - NATURAL SOIL, WATER - WATER
    //E - EXCELLENT, G - GOOD, F  - FAIR, P - POOR, L - FAILED
    //(EX. ASPH-CONC TURF-GRVL ASPH-F ASPH-CONC-G)
    //$surfaceTreat = trim(substr($line,44,5)); // Surface treatment // IGNORED
    //GRVD - SAW-CUT OR PLASTIC GROOVED, PFC - POROUS FRICTION COURSE, AFSC - AGGREGATE FRICTION SEAL COAT
    //RFSC - RUBBERIZED FRICTION SEAL COAT, WC - WIRE COMB OR WIRE TINE, NONE - NO SPECIAL SURFACE TREATMENT
    //$pcn = trim(substr($line,49,11)); // Pavement classification number (see FAA A/C 150/5335-5 for definitions) // IGNORED
    //$lightIntensity = trim(substr($line,60,5)); // Runway lights edge intensity (HIGH, MED, LOW, NSTD, NONE) // IGNORED
    //
    // BASE DATA
    //
    $baseId = trim(substr($line, 65, 3)); // Base end ID
    $baseHeading = intval(trim(substr($line, 68, 3))); // Base end heading (nearest degree TRUE)
    $baseProcedure = trim(substr($line, 71, 10)); // Associated instrument procedure
    //$baseRHP = trim(substr($line,81,1)); // Right pattern (Y/N) // IGNORED
    //$baseMarkings = trim(substr($line,82,5)); // Base marking type // IGNORED
    //PIR - PRECISION, NPI - NONPRECISION, BSC - BASIC, NRS - NUMBERS ONLY, NSTD - NONSTANDARD, BUOY - BUOYS, STOL - STOL, NONE - NONE
    //$baseMarkingsCondition = trim(substr($line,87,1)); // Base marking conditions (G - GOOD, F - FAIR, P - POOR) // IGNORED
    //$baseLatDMS = trim(substr($line,88,15)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    $baseLatSec = trim(substr($line, 103, 12)); //  SSSSSS.SSSSH (Where H is N/S)
    //$baseLonDMS = trim(substr($line,115,15)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    $baseLonSec = trim(substr($line, 130, 12)); //   SSSSSS.SSSSH (Where H is E/W)
    $baseElevation = round(floatval(trim(substr($line, 142, 7)))); // Base Elevation (nearest tenth of a foot MSL)
    $baseTch = intval(trim(substr($line, 149, 3))); // Treshold crossing height (feet AGL)
    $baseGpa = floatval(trim(substr($line, 152, 4))); // Visual glide path angle (degrees to hundredths)
    //$baseDThLatDMS = trim(substr($line,156,15)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    //$baseDThLatSec = trim(substr($line,171,12)); //  SSSSSS.SSSSH (Where H is N/S) // IGNORED
    //$baseDThLonDMS = trim(substr($line,183,15)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    //$baseDThLonSec = trim(substr($line,198,12)); //   SSSSSS.SSSSH (Where H is E/W) // IGNORED
    //$baseDThElevation = round(floatval(trim(substr($line,210,7)))); // Threshold Elevation (nearest tenth of a foot MSL) // IGNORED
    $baseDThDist = intval(trim(substr($line, 217, 4))); // Threshold distance (feet)
    $baseTdze = intval(trim(substr($line, 221, 7))); // Elevation at TDZ (nearest tenth of a foot MSL)
    $baseVgsi = trim(substr($line, 228, 5)); // Visual glide slope indicator
    //SAVASI - SIMPLIFIED ABBREVIATED VASI, VASI - VISUAL APPROACH SLOPE INDICATOR, PAPI - PRECISION APPROACH PATH INDICATOR
    //TRI - TRI-COLOR VASI, PSI - PULSATING/STEADY VASI, PNI - PANELS
    //S2L - 2-BOX SAVASI LEFT, S2R - 2-BOX SAVASI RIGHT
    //V2L - 2-BOX VASI ON LEFT SIDE OF RUNWAY, V2R - 2-BOX VASI ON RIGHT SIDE OF RUNWAY
    //V4L - 4-BOX VASI ON LEFT SIDE OF RUNWAY, V4R - 4-BOX VASI ON RIGHT SIDE OF RUNWAY
    //V6L - 6-BOX VASI ON LEFT SIDE OF RUNWAY, V6R - 6-BOX VASI ON RIGHT SIDE OF RUNWAY
    //V12 - 12-BOX VASI ON BOTH SIDES OF RUNWAY, V16 - 16-BOX VASI ON BOTH SIDES OF RUNWAY
    //P2L - 2-LGT PAPI ON LEFT SIDE OF RUNWAY, P2R - 2-LGT PAPI ON RIGHT SIDE OF RUNWAY
    //P4L - 4-LGT PAPI ON LEFT SIDE OF RUNWAY, P4R - 4-LGT PAPI ON RIGHT SIDE OF RUNWAY
    //NSTD - NONSTANDARD VASI SYSTEM, PVT - PRIVATELY OWNED/USE
    //VAS - NON-SPECIFIC VASI SYSTEM, NONE/N - NONE
    //TRIL - TRI-COLOR VASI, TRIR - TRI-COLOR VASI RIGHT
    //PSIL - PULSATING/STEADY VASI LEFT, PSIR - PULSATING/STEADY VASI RIGHT
    //PNIL - SYSTEM OF PANELS LEFT. PNIR - SYSTEM OF PANELS RIGHT
    //$baseRvrEquip = trim(substr($line,233,3)); // RVR Equipment Location(s): T - TOUCHDOWN, M - MIDFIELD, R - ROLLOUT, N - NO RVR AVAILABLE // IGNORED
    //$baseRvvEquip = trim(substr($line,236,1)); // RVV Equipment Present (Y/N) // IGNORED
    //$baseAls = trim(substr($line,237,8)); // Approach light system // IGNORED
    //ALSAF - 3,000 FOOT HIGH INTENSITY APPROACH LIGHTING SYSTEM WITH CENTERLINE SEQUENCE FLASHERS.
    //ALSF1 - STANDARD 2,400 FOOT HIGH INTENSITY APPROACH LIGHTING SYSTEM WITH SEQUENCED FLASHERS, CATEGORY I CONFIG.
    //ALSF2 - STANDARD 2,400 FOOT HIGH INTENSITY APPROACH LIGHTING SYSTEM WITH SEQUENCED FLASHERS, CATEGORY II OR III CONFIGURATION
    //MALS  - 1,400 FOOT MEDIUM INTENSITY APPROACH LIGHTING SYSTEM
    //MALSF - 1,400 FOOT MEDIUM INTENSITY APPROACH LIGHTING SYSTEM WITH SEQUENCED FLASHERS
    //MALSR - 1,400 FOOT MEDIUM INTENSITY APPROACH LIGHTING SYSTEM WITH RUNWAY ALIGNMENT INDICATOR LIGHTS
    //SSALS - SIMPLIFIED SHORT APPROACH LIGHTING SYSTEM
    //SSALF - SIMPLIFIED SHORT APPROACH LIGHTING SYSTEM WITH SEQUENCED FLASHERS
    //SSALR - SIMPLIFIED SHORT APPROACH LIGHTING SYSTEM WITH RUWNAY ALIGNMENT INDICATOR LIGHTS
    //NEON  - NEON LADDER SYSTEM
    //ODALS - OMNIDIRECTIONAL APPROACH LIGHTING SYSTEM
    //RLLS  - RUNWAY LEAD-IN LIGHT SYSTEM
    //MIL OVRN - MILITARY OVERRUN
    //NSTD  - ALL OTHERS
    //NONE  - NO APPROACH LIGHTING IS AVAILABLE
    //$baseReils = trim(substr($line,245,1)); // REIL present (Y/N) // IGNORED
    //$baseClineLights = trim(substr($line,246,1)); // Centerline lights present (Y/N) // IGNORED
    //$baseTdzeLights = trim(substr($line,247,1)); // TDZE lights present (Y/N) // IGNORED
    //$baseContObj = trim(substr($line,248,11)); // Controlling object description (EX. TREES,BLDG,PLINE,FENCE,NONE) // IGNORED
    //$baseContObjMark = trim(substr($line,259,4)); // Controlling object marked/lighted (EX. M - Marked, L - Lighted, ML - Both, NONE) // IGNORED
    //$basePart77Def = trim(substr($line,263,5)); // Part 77 definition (EX. TREES,BLDG,PLINE,FENCE,NONE) // IGNORED
    //A(V)  - UTILITY RUNWAY WITH A VISUAL APPROACH
    //B(V) - OTHER THAN UTILITY RUNWAY WITH A VISUAL APPROACH
    //A(NP) - UTILITY RUNWAY WITH A NONPRECISION APPROACH
    //C - OTHER THAN UTILITY RUNWAY WITH A NONPRECISION APPROACH HAVING VISIBILITY MINIMUMS GREATER THAN 3/4 MILE
    //PIR - PRECISION INSTRUMENT RUNWAY
    //$baseContObjSlope = intval(trim(substr($line,268,2))); // Controlling object clearance slope (Ratio N:1) // IGNORED
    //$baseContObjHeight = intval(trim(substr($line,270,5))); // Controlling object height above runway (feet AGL) // IGNORED
    //$baseContObjDist = intval(trim(substr($line,275,5))); // Controlling object distance from runway end (feet) // IGNORED
    //$baseContObjOff = intval(trim(substr($line,281,7))); // Controlling object offset from runway centerline (feet plus L/R indicator) // IGNORED
    //
    // RECIP DATA
    //
    $recipId = trim(substr($line, 287, 3)); // Recip end ID
    $recipHeading = intval(trim(substr($line, 290, 3))); // Recip end heading (nearest degree TRUE)
    $recipProcedure = trim(substr($line, 293, 10)); // Associated instrument procedure
    //$recipRHP = trim(substr($line,303,1)); // Right pattern (Y/N) // IGNORED
    //$recipMarkings = trim(substr($line,304,5)); // Recip marking type // IGNORED
    //PIR - PRECISION, NPI - NONPRECISION, BSC - BASIC, NRS - NUMBERS ONLY, NSTD - NONSTANDARD, BUOY - BUOYS, STOL - STOL, NONE - NONE
    //$recipMarkingsCondition = trim(substr($line,309,1)); // Recip marking conditions (G - GOOD, F - FAIR, P - POOR) // IGNORED
    //$recipLatDMS = trim(substr($line,310,15)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    $recipLatSec = trim(substr($line, 325, 12)); //  SSSSSS.SSSSH (Where H is N/S)
    //$recipLonDMS = trim(substr($line,337,15)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    $recipLonSec = trim(substr($line, 352, 12)); //   SSSSSS.SSSSH (Where H is E/W)
    $recipElevation = round(floatval(trim(substr($line, 364, 7)))); // Recip Elevation (nearest tenth of a foot MSL)
    $recipTch = intval(trim(substr($line, 371, 3))); // Treshold crossing height (feet AGL)
    $recipGpa = floatval(trim(substr($line, 374, 4))); // Visual glide path angle (degrees to hundredths)
    //$recipDThLatDMS = trim(substr($line,378,15)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    //$recipDThLatSec = trim(substr($line,393,12)); //  SSSSSS.SSSSH (Where H is N/S) // IGNORED
    //$recipDThLonDMS = trim(substr($line,405,15)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    //$recipDThLonSec = trim(substr($line,420,12)); //   SSSSSS.SSSSH (Where H is E/W) // IGNORED
    //$recipDThElevation = round(floatval(trim(substr($line,432,7)))); // Threshold Elevation (nearest tenth of a foot MSL) // IGNORED
    $recipDThDist = intval(trim(substr($line, 439, 4))); // Threshold distance (feet)
    $recipTdze = intval(trim(substr($line, 443, 7))); // Elevation at TDZ (nearest tenth of a foot MSL)
    $recipVgsi = trim(substr($line, 450, 5)); // Visual glide slope indicator
    //SAVASI - SIMPLIFIED ABBREVIATED VASI, VASI - VISUAL APPROACH SLOPE INDICATOR, PAPI - PRECISION APPROACH PATH INDICATOR
    //TRI - TRI-COLOR VASI, PSI - PULSATING/STEADY VASI, PNI - PANELS
    //S2L - 2-BOX SAVASI LEFT, S2R - 2-BOX SAVASI RIGHT
    //V2L - 2-BOX VASI ON LEFT SIDE OF RUNWAY, V2R - 2-BOX VASI ON RIGHT SIDE OF RUNWAY
    //V4L - 4-BOX VASI ON LEFT SIDE OF RUNWAY, V4R - 4-BOX VASI ON RIGHT SIDE OF RUNWAY
    //V6L - 6-BOX VASI ON LEFT SIDE OF RUNWAY, V6R - 6-BOX VASI ON RIGHT SIDE OF RUNWAY
    //V12 - 12-BOX VASI ON BOTH SIDES OF RUNWAY, V16 - 16-BOX VASI ON BOTH SIDES OF RUNWAY
    //P2L - 2-LGT PAPI ON LEFT SIDE OF RUNWAY, P2R - 2-LGT PAPI ON RIGHT SIDE OF RUNWAY
    //P4L - 4-LGT PAPI ON LEFT SIDE OF RUNWAY, P4R - 4-LGT PAPI ON RIGHT SIDE OF RUNWAY
    //NSTD - NONSTANDARD VASI SYSTEM, PVT - PRIVATELY OWNED/USE
    //VAS - NON-SPECIFIC VASI SYSTEM, NONE/N - NONE
    //TRIL - TRI-COLOR VASI, TRIR - TRI-COLOR VASI RIGHT
    //PSIL - PULSATING/STEADY VASI LEFT, PSIR - PULSATING/STEADY VASI RIGHT
    //PNIL - SYSTEM OF PANELS LEFT. PNIR - SYSTEM OF PANELS RIGHT
    //$recipRvrEquip = trim(substr($line,455,3)); // RVR Equipment Location(s): T - TOUCHDOWN, M - MIDFIELD, R - ROLLOUT, N - NO RVR AVAILABLE // IGNORED
    //$recipRvvEquip = trim(substr($line,458,1)); // RVV Equipment Present (Y/N) // IGNORED
    //$recipAls = trim(substr($line,459,8)); // Approach light system // IGNORED
    //ALSAF - 3,000 FOOT HIGH INTENSITY APPROACH LIGHTING SYSTEM WITH CENTERLINE SEQUENCE FLASHERS.
    //ALSF1 - STANDARD 2,400 FOOT HIGH INTENSITY APPROACH LIGHTING SYSTEM WITH SEQUENCED FLASHERS, CATEGORY I CONFIG.
    //ALSF2 - STANDARD 2,400 FOOT HIGH INTENSITY APPROACH LIGHTING SYSTEM WITH SEQUENCED FLASHERS, CATEGORY II OR III CONFIGURATION
    //MALS  - 1,400 FOOT MEDIUM INTENSITY APPROACH LIGHTING SYSTEM
    //MALSF - 1,400 FOOT MEDIUM INTENSITY APPROACH LIGHTING SYSTEM WITH SEQUENCED FLASHERS
    //MALSR - 1,400 FOOT MEDIUM INTENSITY APPROACH LIGHTING SYSTEM WITH RUNWAY ALIGNMENT INDICATOR LIGHTS
    //SSALS - SIMPLIFIED SHORT APPROACH LIGHTING SYSTEM
    //SSALF - SIMPLIFIED SHORT APPROACH LIGHTING SYSTEM WITH SEQUENCED FLASHERS
    //SSALR - SIMPLIFIED SHORT APPROACH LIGHTING SYSTEM WITH RUWNAY ALIGNMENT INDICATOR LIGHTS
    //NEON  - NEON LADDER SYSTEM
    //ODALS - OMNIDIRECTIONAL APPROACH LIGHTING SYSTEM
    //RLLS  - RUNWAY LEAD-IN LIGHT SYSTEM
    //MIL OVRN - MILITARY OVERRUN
    //NSTD  - ALL OTHERS
    //NONE  - NO APPROACH LIGHTING IS AVAILABLE
    //$recipReils = trim(substr($line,467,1)); // REIL present (Y/N) // IGNORED
    //$recipClineLights = trim(substr($line,468,1)); // Centerline lights present (Y/N) // IGNORED
    //$recipTdzeLights = trim(substr($line,469,1)); // TDZE lights present (Y/N) // IGNORED
    //$recipContObj = trim(substr($line,470,11)); // Controlling object description (EX. TREES,BLDG,PLINE,FENCE,NONE) // IGNORED
    //$recipContObjMark = trim(substr($line,481,4)); // Controlling object marked/lighted (EX. M - Marked, L - Lighted, ML - Both, NONE) // IGNORED
    //$recipPart77Def = trim(substr($line,485,5)); // Part 77 definition (EX. TREES,BLDG,PLINE,FENCE,NONE) // IGNORED
    //A(V)  - UTILITY RUNWAY WITH A VISUAL APPROACH
    //B(V) - OTHER THAN UTILITY RUNWAY WITH A VISUAL APPROACH
    //A(NP) - UTILITY RUNWAY WITH A NONPRECISION APPROACH
    //C - OTHER THAN UTILITY RUNWAY WITH A NONPRECISION APPROACH HAVING VISIBILITY MINIMUMS GREATER THAN 3/4 MILE
    //PIR - PRECISION INSTRUMENT RUNWAY
    //$recipContObjSlope = intval(trim(substr($line,490,2))); // Controlling object clearance slope (Ratio N:1) // IGNORED
    //$recipContObjHeight = intval(trim(substr($line,492,5))); // Controlling object height above runway (feet AGL) // IGNORED
    //$recipContObjDist = intval(trim(substr($line,497,5))); // Controlling object distance from runway end (feet) // IGNORED
    //$recipContObjOff = intval(trim(substr($line,502,7))); // Controlling object offset from runway centerline (feet plus L/R indicator) // IGNORED
    //
    // OTHER DATA
    //
    //$lengthSource = trim(substr($line,509,16)); // Runway length source // IGNORED
    //$lengthSourceDate = trim(substr($line,525,10)); // Runway length source date (MM/DD/YYYY) // IGNORED
    //$weightCapacitySingle = trim(substr($line,535,6)); // Weight bearing capacity single wheel // IGNORED
    //$weightCapacityDouble = trim(substr($line,541,6)); // Weight bearing capacity double wheel // IGNORED
    //$weightCapacityQuad = trim(substr($line,547,6)); // Weight bearing capacity quad wheel // IGNORED
    //$weightCapacityBody = trim(substr($line,553,6)); // Weight bearing capacity body gear // IGNORED
    //
    // ADDITIONAL BASE DATA
    //
    //$baseGradient = trim(substr($line,559,5)); // Gradient // IGNORED
    //$baseGradientDir = trim(substr($line,564,4)); // Gradient up or down // IGNORED
    //$basePositionSource = trim(substr($line,568,16)); // Base position source // IGNORED
    //$basePositionSourceDate = trim(substr($line,584,10)); // Base position source date (MM/DD/YYYY)// IGNORED
    //$baseElevationSource = trim(substr($line,594,16)); // Base elevation source // IGNORED
    //$baseElevationSourceDate = trim(substr($line,610,10)); // Base elevation source date (MM/DD/YYYY)// IGNORED
    //$baseDThPositionSource = trim(substr($line,620,16)); // Base displaced threshold position source // IGNORED
    //$baseDthPositionSourceDate = trim(substr($line,636,10)); // Base displaced threshold position source date (MM/DD/YYYY)// IGNORED
    //$baseDThElevationSource = trim(substr($line,646,16)); // Base displaced threshold elevation source // IGNORED
    //$baseDthElevationSourceDate = trim(substr($line,662,10)); // Base displaced threshold elevation source date (MM/DD/YYYY)// IGNORED
    //$baseTdzeSource = trim(substr($line,672,16)); // Base TDZE source // IGNORED
    //$baseTdzeSourceDate = trim(substr($line,688,10)); // Base TDZE source date (MM/DD/YYYY)// IGNORED
    //$baseTora = intval(trim(substr($line,698,5))); // Base TORA (feet) // IGNORED
    //$baseToda = intval(trim(substr($line,703,5))); // Base TODA (feet) // IGNORED
    //$baseAsda = intval(trim(substr($line,708,5))); // Accel/Stop Distance Avail (feet) // IGNORED
    //$baseLda = intval(trim(substr($line,713,5))); // Landing Distance Avail (feet) // IGNORED
    //$baseLdaHs = intval(trim(substr($line,718,5))); // Landing Distance Avail for Hold Short (feet) // IGNORED
    //$baseIntersectingId = trim(substr($line,723,7)); // ID of intersecting runway for the HS point // IGNORED
    //$baseIntersectingDesc = trim(substr($line,730,40)); // Description of HS point cause, if not runway // IGNORED
    //$baseHsPointLatDMS = trim(substr($line,770,15)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    //$baseHsPointLatSec = trim(substr($line,785,12)); //  SSSSSS.SSSSH (Where H is N/S) // IGNORED
    //$baseHsPointLonDMS = trim(substr($line,797,15)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    //$baseHsPointLonSec = trim(substr($line,812,12)); //   SSSSSS.SSSSH (Where H is E/W) // IGNORED
    //$baseHsPointSource = trim(substr($line,824,16)); // HS Point Source // IGNORED
    //$baseHsPointSourceDate = trim(substr($line,840,10)); // HS Point Source Date (MM/DD/YYYY) // IGNORED
    //
    // ADDITIONAL RECIP DATA
    //
    //$recipGradient = trim(substr($line,850,5)); // Gradient // IGNORED
    //$recipGradientDir = trim(substr($line,855,4)); // Gradient up or down // IGNORED
    //$recipPositionSource = trim(substr($line,859,16)); // Recip position source // IGNORED
    //$recipPositionSourceDate = trim(substr($line,875,10)); // Recip position source date (MM/DD/YYYY)// IGNORED
    //$recipElevationSource = trim(substr($line,885,16)); // Recip elevation source // IGNORED
    //$recipElevationSourceDate = trim(substr($line,901,10)); // Recip elevation source date (MM/DD/YYYY)// IGNORED
    //$recipDThPositionSource = trim(substr($line,911,16)); // Recip displaced threshold position source // IGNORED
    //$recipDthPositionSourceDate = trim(substr($line,927,10)); // Recip displaced threshold position source date (MM/DD/YYYY)// IGNORED
    //$recipDThElevationSource = trim(substr($line,937,16)); // Recip displaced threshold elevation source // IGNORED
    //$recipDthElevationSourceDate = trim(substr($line,953,10)); // Recip displaced threshold elevation source date (MM/DD/YYYY)// IGNORED
    //$recipTdzeSource = trim(substr($line,963,16)); // Recip TDZE source // IGNORED
    //$recipTdzeSourceDate = trim(substr($line,979,10)); // Recip TDZE source date (MM/DD/YYYY)// IGNORED
    //$recipTora = intval(trim(substr($line,989,5))); // Recip TORA (feet) // IGNORED
    //$recipToda = intval(trim(substr($line,994,5))); // Recip TODA (feet) // IGNORED
    //$recipAsda = intval(trim(substr($line,999,5))); // Accel/Stop Distance Avail (feet) // IGNORED
    //$recipLda = intval(trim(substr($line,1004,5))); // Landing Distance Avail (feet) // IGNORED
    //$recipLdaHs = intval(trim(substr($line,1009,5))); // Landing Distance Avail for Hold Short (feet) // IGNORED
    //$recipIntersectingId = trim(substr($line,1014,7)); // ID of intersecting runway for the HS point // IGNORED
    //$recipIntersectingDesc = trim(substr($line,1021,40)); // Description of HS point cause, if not runway // IGNORED
    //$recipHsPointLatDMS = trim(substr($line,1061,15)); // LATITUDE DD-MM-SS.SSSSH (Where H is N/S) // IGNORED
    //$recipHsPointLatSec = trim(substr($line,1076,12)); //  SSSSSS.SSSSH (Where H is N/S) // IGNORED
    //$recipHsPointLonDMS = trim(substr($line,1088,15)); // LONGITUDE DDD-MM-SS.SSSSH (Where H is E/W) // IGNORED
    //$recipHsPointLonSec = trim(substr($line,1103,12)); //   SSSSSS.SSSSH (Where H is E/W) // IGNORED
    //$recipHsPointSource = trim(substr($line,1115,16)); // HS Point Source // IGNORED
    //$recipHsPointSourceDate = trim(substr($line,1131,10)); // HS Point Source Date (MM/DD/YYYY) // IGNORED
    // RECORD SPACING FROM 1141 for 390

    $this->airportFacId = $airportFacId;
    $this->runwayId = $runwayId;
    $this->length = $length;
    $this->width = $width;
    $this->surface = $surfaceType;
    $this->surfaceCond = $surfaceCond;
    $this->baseId = $baseId;
    $baseLatDdB = (substr($baseLatSec, -1) == 'N') ? (floatval(substr($baseLatSec, 0, -1)) / 3600) : - (floatval(substr($baseLatSec, 0, -1)) / 3600); // Convert SEC to DD
    $baseLonDdB = (substr($baseLonSec, -1) == 'E') ? (floatval(substr($baseLonSec, 0, -1)) / 3600) : - (floatval(substr($baseLonSec, 0, -1)) / 3600); // Convert SEC to DD
    $this->baseLat = $baseLatDdB;
    $this->baseLon = $baseLonDdB;
    $this->baseTrue = $baseHeading;
    $this->baseElev = $baseElevation;
    $this->baseTch = $baseTch;
    $this->baseGpa = $baseGpa;
    $this->baseDThDist = $baseDThDist;
    $this->baseTdze = $baseTdze;
    $this->baseVgsi = $baseVgsi;
    $this->baseProc = $baseProcedure;
    $this->recipId = $recipId;
    $recipLatDdB = (substr($recipLatSec, -1) == 'N') ? (floatval(substr($recipLatSec, 0, -1)) / 3600) : - (floatval(substr($recipLatSec, 0, -1)) / 3600); // Convert SEC to DD
    $recipLonDdB = (substr($recipLonSec, -1) == 'E') ? (floatval(substr($recipLonSec, 0, -1)) / 3600) : - (floatval(substr($recipLonSec, 0, -1)) / 3600); // Convert SEC to DD
    $this->recipLat = $recipLatDdB;
    $this->recipLon = $recipLonDdB;
    $this->recipTrue = $recipHeading;
    $this->recipElev = $recipElevation;
    $this->recipTch = $recipTch;
    $this->recipGpa = $recipGpa;
    $this->recipDThDist = $recipDThDist;
    $this->recipTdze = $recipTdze;
    $this->recipVgsi = $recipVgsi;
    $this->recipProc = $recipProcedure;
  }

  public function fromModel(object $dbObject)
  {
    $this->airportFacId = $dbObject->fac_id;
    $this->runwayId = $dbObject->rwy_id;
    $this->length = $dbObject->length;
    $this->width = $dbObject->width;
    $this->surface = $dbObject->sfc;
    $this->surfaceCond = $dbObject->sfc_cond;
    $this->baseId = $dbObject->base_id;
    $this->baseLat = $dbObject->base_lat;
    $this->baseLon = $dbObject->base_lon;
    $this->baseTrue = $dbObject->base_true;
    $this->baseElev = $dbObject->base_elev;
    $this->baseTch = $dbObject->base_tch;
    $this->baseGpa = $dbObject->base_gpa;
    $this->baseDThDist = $dbObject->base_dthdist;
    $this->baseTdze = $dbObject->base_tdze;
    $this->baseVgsi = $dbObject->base_vgsi;
    $this->baseProc = $dbObject->base_proc;
    $this->recipId = $dbObject->recip_id;
    $this->recipLat = $dbObject->recip_lat;
    $this->recipLon = $dbObject->recip_lon;
    $this->recipTrue = $dbObject->recip_true;
    $this->recipElev = $dbObject->recip_elev;
    $this->recipTch = $dbObject->recip_tch;
    $this->recipGpa = $dbObject->recip_gpa;
    $this->recipDThDist = $dbObject->recip_dthdist;
    $this->recipTdze = $dbObject->recip_tdze;
    $this->recipVgsi = $dbObject->recip_vgsi;
    $this->recipProc = $dbObject->recip_proc;
  }

  public function toDBArray(string $airacId, ?bool $next = false)
  {
    $result = array(
      'fac_id'          => $this->airportFacId,
      'rwy_id'          => $this->runwayId,
      'length'          => $this->length,
      'width'           => $this->width,
      'sfc'             => $this->surface,
      'sfc_cond'        => $this->surfaceCond,
      'base_id'         => $this->baseId,
      'base_lat'        => $this->baseLat,
      'base_lon'        => $this->baseLon,
      'base_true'       => $this->baseTrue,
      'base_elev'       => $this->baseElev,
      'base_tch'        => $this->baseTch,
      'base_gpa'        => $this->baseGpa,
      'base_dthdist'    => $this->baseDThDist,
      'base_tdze'       => $this->baseTdze,
      'base_vgsi'       => $this->baseVgsi,
      'base_proc'       => $this->baseProc,
      'recip_id'        => $this->recipId,
      'recip_lat'       => $this->recipLat,
      'recip_lon'       => $this->recipLon,
      'recip_true'      => $this->recipTrue,
      'recip_elev'      => $this->recipElev,
      'recip_tch'       => $this->recipTch,
      'recip_gpa'       => $this->recipGpa,
      'recip_dthdist'   => $this->recipDThDist,
      'recip_tdze'      => $this->recipTdze,
      'recip_vgsi'      => $this->recipVgsi,
      'recip_proc'      => $this->recipProc,
      'cycle_id'        => $airacId,
      'next'            => $next
    );
    return $result;
  }
}
