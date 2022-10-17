<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

use App\Models\Alias;
use App\Models\DataCurrency;

class FrontendController extends Controller
{
  public function facilities()
  {
    $facilities = null;
    $aliases = Alias::all();
    $positions = null;

    return Inertia::render(
      'Facilities',
      [
        'facilities' => $facilities,
        'aliases' => $aliases,
        'positions' => $positions,
      ]
    );
  }

  public function files()
  {
    $cifpCurrent = DataCurrency::where('data_id', '=', 'CIFP')->where('edition', '=', 'CURRENT')->first();
    $cifpNext = DataCurrency::where('data_id', '=', 'CIFP')->where('edition', '=', 'NEXT')->first();
    $cifpDownloadCurrent = json_decode(Storage::get('cifp/CURRENT/AIRAC.json'));
    $cifpDownloadNext = json_decode(Storage::get('cifp/NEXT/AIRAC.json'));
    $nasrCurrent = DataCurrency::where('data_id', '=', 'NASR')->where('edition', '=', 'CURRENT')->first();
    $nasrNext = DataCurrency::where('data_id', '=', 'NASR')->where('edition', '=', 'NEXT')->first();
    $nasrDownloadCurrent = json_decode(Storage::get('nasr/CURRENT/AIRAC.json'));
    $nasrDownloadNext = json_decode(Storage::get('nasr/NEXT/AIRAC.json'));
    return Inertia::render(
      'Files',
      [
        'cifpCurrent' => $cifpCurrent,
        'cifpNext' => $cifpNext,
        'cifpDownloadCurrent' => $cifpDownloadCurrent,
        'cifpDownloadNext' => $cifpDownloadNext,
        'nasrCurrent' => $nasrCurrent,
        'nasrNext' => $nasrNext,
        'nasrDownloadCurrent' => $nasrDownloadCurrent,
        'nasrDownloadNext' => $nasrDownloadNext,
      ]
    );
  }
}
