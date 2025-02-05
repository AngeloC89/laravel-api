<?php

namespace App\Http\Controllers;

use App\Models\GoogleToken;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class GmailAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setClientId(config('gmail.client_id'));
        $client->setClientSecret(config('gmail.client_secret'));
        $client->setRedirectUri(config('gmail.redirect_uri'));
        $client->addScope('https://www.googleapis.com/auth/gmail.send');
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return redirect($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(config('gmail.client_id'));
        $client->setClientSecret(config('gmail.client_secret'));
        $client->setRedirectUri(config('gmail.redirect_uri'));

        $token = $client->fetchAccessTokenWithAuthCode($request->code);
        //dd($token);
        GoogleToken::updateOrCreate(
            ['user_id' => 1], // Se sei l'unico utente, puoi usare un ID fisso
            [
                'access_token' => Crypt::encryptString($token['access_token']),  // 🛑 Crittografia qui
                'refresh_token' => isset($token['refresh_token']) ? Crypt::encryptString($token['refresh_token']) : null,
                'expires_at' => now()->addSeconds($token['expires_in'])
            ]
        );

        return redirect('/')->with('success', 'Autenticazione completata!');
    }
}
