<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TechnologyController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\api\MailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/user/{id}', [ProfileController::class, 'index']);
Route::get('projects', [ProjectController::class, 'index']);
Route::get('projects/{slug}', [ProjectController::class, 'show']);
Route::get('technologies', [TechnologyController::class, 'index']);
Route::post('/send-email', [MailController::class, 'sendEmail']);

