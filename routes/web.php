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

  Route::get('/facilities', function () {
    return Inertia::render('Facilities');
  })->name('facilities');

  Route::get('/files', [FrontendController::class, 'files'])->name('files');

  //Server Work Routes
  ////CIFP
  Route::get('/cifp/info/{editionName}', [CIFPController::class, 'getInfo']);
  Route::post('/cifp/download', [CIFPController::class, 'getDownload']);
  Route::post('/cifp/decompress', [CIFPController::class, 'decompressDownload']);
  ////NASR
  Route::get('/nasr/info/{editionName}', [NASRController::class, 'getInfo']);
  Route::post('/nasr/download', [NASRController::class, 'getDownload']);
  Route::post('/nasr/decompress', [NASRController::class, 'decompressDownload']);
});

require __DIR__ . '/auth.php';
