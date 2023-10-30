<?php

use App\Http\Controllers\byAreaController;
use App\Http\Controllers\ebayController;
use App\Http\Controllers\ScraperControler;
use App\Http\Controllers\KSLScrapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\users;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});
Route::get('/user', [users::class, "index"]);
Route::get('/scraper', [ScraperControler::class, "index"]);
Route::post('/scraper', [ScraperControler::class, "create"]);
Route::post('/add_listing', [ScraperControler::class, "store"]);
Route::get('/kslscraper', [KSLScrapController::class, "index"]);
Route::post('/kslscraper', [KSLScrapController::class, "create"]);
Route::post('/ksl_add_listing', [KSLScrapController::class, "store"]);
Route::get('/bayarea_scraper', [byAreaController::class, "index"]);
Route::post('/bayarea_scraper', [byAreaController::class, "create"]);
Route::post('/bayarea_add_listing', [byAreaController::class, "store"]);
Route::get('/ebay', [ebayController::class, "index"]);

require __DIR__ . '/auth.php';
