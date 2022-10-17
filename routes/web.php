<?php

use App\Http\Controllers\CIFPController;
use App\Http\Controllers\NASRController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public Routes
Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'artccId' => env('ARTCC_ID'),
  ]);
});

//Auth Routes
Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
  })->name('dashboard');

  Route::get('/facilities', [FrontendController::class, 'facilities'])->name('facilities');
  Route::get('/files', [FrontendController::class, 'files'])->name('files');

  //Server Work Routes
  ////CIFP
  Route::get('/cifp/info/{editionName}', [CIFPController::class, 'getInfo']);
  Route::post('/cifp/download', [CIFPController::class, 'getDownload']);
  Route::post('/cifp/decompress', [CIFPController::class, 'decompressDownload']);
  //////CIFP Imports
  Route::post('/cifp/import/controlled', [CIFPController::class, 'processControlled']);
  Route::post('/cifp/import/restrictive', [CIFPController::class, 'processRestrictive']);
  Route::post('/cifp/import/procedures', [CIFPController::class, 'processProcedures']);
  Route::post('/cifp/import/finalize', [CIFPController::class, 'finalize']);

  ////NASR
  Route::get('/nasr/info/{editionName}', [NASRController::class, 'getInfo']);
  Route::post('/nasr/download', [NASRController::class, 'getDownload']);
  Route::post('/nasr/decompress', [NASRController::class, 'decompressDownload']);
  //////NASR Imports
  Route::post('/nasr/airports', [NASRController::class, 'processAirports']);
  Route::post('/nasr/airways', [NASRController::class, 'processAirways']);
  Route::post('/nasr/airwaysats', [NASRController::class, 'processAirwaysAts']);
  Route::post('/nasr/awos', [NASRController::class, 'processAwos']);
  Route::post('/nasr/boundaries', [NASRController::class, 'processBoundaries']);
  Route::post('/nasr/codedroutes', [NASRController::class, 'processCodedRoutes']);
  Route::post('/nasr/ils', [NASRController::class, 'processILS']);
  Route::post('/nasr/fixes', [NASRController::class, 'processFixes']);
  Route::post('/nasr/navaids', [NASRController::class, 'processNavaids']);
  Route::post('/nasr/preferredroutes', [NASRController::class, 'processPreferredRoutes']);
  Route::post('/nasr/finalize', [NASRController::class, 'finalize']);
});

require __DIR__ . '/auth.php';
