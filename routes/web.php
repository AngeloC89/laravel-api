<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\TechnologyController;
use Illuminate\Support\Facades\Storage;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;


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

        $encryptedAccessToken = Crypt::encryptString($token['access_token']);
        $encryptedRefreshToken = Crypt::encryptString($token['refresh_token']);

        $user = Auth::user();
     
        PersonalAccessToken::create([
            'tokenable_type' => get_class($user), // Tipo del modello, es. 'App\Models\User'
            'tokenable_id' => $user->id,           // ID dell'utente
            'name' => 'Gmail API Token',           // Nome del token
            'token' => $encryptedAccessToken,      // Token crittografato
            'abilities' => json_encode(['send_mail']), // Le abilità del token, ad esempio 'send_mail'
            'expires_at' => now()->addHours(1),    // La scadenza del token di accesso
        ]);

        PersonalAccessToken::create([
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->id,
            'name' => 'Gmail Refresh Token',
            'token' => $encryptedRefreshToken,
            'abilities' => json_encode(['refresh_token']),
            'expires_at' => now()->addDays(30),  // La scadenza del refresh token (tipicamente più lunga)
        ]);
    }

    return 'Autenticazione completata con successo Angelo!';
});





require __DIR__.'/auth.php';

Route::fallback(function () {
    return redirect()->route('admin.dashboard');
});
