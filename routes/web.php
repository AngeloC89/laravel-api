<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Api\LeadController;
use Illuminate\Support\Facades\Storage;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[HomeController::class, 'index'])->name('home');

Route::middleware('auth')->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/project', ProjectController::class)->parameters(['project' => 'project:slug']);
    Route::resource('/types', TypeController::class)->parameters(['types' => 'type:slug']);
    Route::resource('/technologies', TechnologyController::class)->parameters(['technologies' => 'technology:slug']);
//...
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/auth/google', function () {
    $client = new \Google\Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
    $client->addScope('https://www.googleapis.com/auth/gmail.send');
    $client->setAccessType('offline');

    return redirect($client->createAuthUrl());
});

Route::get('/callback', function (Illuminate\Http\Request $request) {
    $client = new \Google\Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

    $token = $client->fetchAccessTokenWithAuthCode($request->input('code'));

    // Salva il refresh_token (solo alla prima configurazione)
    if (isset($token['refresh_token'])) {
        // Percorso dove salvare il token su S3
        $tokenPath = 'gmail_tokens/google-refresh-token.json';
        
        // Salva il token su S3
        Storage::disk('s3')->put($tokenPath, json_encode($token));
    }


    return 'Autenticazione completata!';
});





require __DIR__.'/auth.php';

Route::fallback(function () {
    return redirect()->route('admin.dashboard');
});
