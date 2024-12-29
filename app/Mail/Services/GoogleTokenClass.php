<?php

namespace App\Mail\Services;

use Google\Client;

class GoogleTokenClass
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->addScope('https://www.googleapis.com/auth/gmail.send');
        $this->client->setAccessType('offline');
    }

    /**
     * Ottieni un'istanza del client Google configurato.
     *
     * @return \Google\Client
     */
    public function getClient()
    {
        $this->checkAndRefreshToken();
        return $this->client;
    }

    /**
     * Controlla se il token è scaduto e lo aggiorna se necessario.
     */
    protected function checkAndRefreshToken()
    {
        $tokenPath = storage_path('google-refresh-token.json');
        if (file_exists($tokenPath)) {
            $token = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($token);

            if ($this->client->isAccessTokenExpired()) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                file_put_contents($tokenPath, json_encode($newToken));
                $this->client->setAccessToken($newToken);
            }
        } else {
            throw new \Exception('Il file del token non esiste. Effettua nuovamente l\'autenticazione.');
        }
    }
}