<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Models\DataCurrency;

class FrontendController extends Controller
{
  public function files()
  {
    $cifpCurrent = DataCurrency::where('data_id', '=', 'CIFP')->where('edition', '=', 'CURRENT')->first();
    $cifpNext = DataCurrency::where('data_id', '=', 'CIFP')->where('edition', '=', 'NEXT')->first();
    $nasrCurrent = DataCurrency::where('data_id', '=', 'NASR')->where('edition', '=', 'CURRENT')->first();
    $nasrNext = DataCurrency::where('data_id', '=', 'NASR')->where('edition', '=', 'NEXT')->first();
    return Inertia::render('Files', ['cifpCurrent' => $cifpCurrent, 'cifpNext' => $cifpNext, 'nasrCurrent' => $nasrCurrent, 'nasrNext' => $nasrNext]);
  }
}
