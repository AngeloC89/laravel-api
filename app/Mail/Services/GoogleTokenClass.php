<?php

namespace App\Mail\Services;

use Illuminate\Support\Facades\Storage;
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
        $tokenPath = 'gmail_tokens/google-refresh-token.json';
        if (Storage::disk('s3')->exists($tokenPath)) {
            // Ottieni il contenuto del file token da S3
            $token = json_decode(Storage::disk('s3')->get($tokenPath), true);
            $this->client->setAccessToken($token);
    
            // Se il token è scaduto, rinnovalo
            if ($this->client->isAccessTokenExpired()) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
    
                // Salva il nuovo token su S3
                Storage::disk('s3')->put($tokenPath, json_encode($newToken));
                $this->client->setAccessToken($newToken);
            }
        } else {
            throw new \Exception('Il file del token non esiste su S3. Effettua nuovamente l\'autenticazione.');
        }
    }
}