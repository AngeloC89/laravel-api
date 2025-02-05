<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Crypt;
use App\Models\GoogleToken;
use Illuminate\Support\Facades\Auth;

class GoogleTokenService
{
    /**
     * Recupera e imposta il token per il client Gmail.
     */
    public function authenticate(Client $client)
    {
        $tokenRecord = GoogleToken::first();

        if ($tokenRecord) {
            $accessToken = Crypt::decryptString($tokenRecord->access_token);
            $client->setAccessToken($accessToken);

            if ($client->isAccessTokenExpired()) {
                $this->refreshToken($client, $tokenRecord);
            }
        }
    }

    /**
     * Aggiorna il token di accesso usando il Refresh Token.
     */
    private function refreshToken(Client $client, GoogleToken $tokenRecord)
    {
        $refreshToken = Crypt::decryptString($tokenRecord->refresh_token);
        $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);

        if (isset($newToken['access_token'])) {
            $tokenRecord->update([
                'access_token' => Crypt::encryptString($newToken['access_token']),
                'expires_at' => now()->addSeconds($newToken['expires_in']),
            ]);
        }
    }
}