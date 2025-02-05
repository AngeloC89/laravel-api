<?php

return [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
    'scopes' => [
        'https://www.googleapis.com/auth/gmail.send', // Permessi per inviare email
    ],
    'access_type' => 'offline', // Necessario per ottenere un refresh token
    'approval_prompt' => 'force', // Forza la richiesta del consenso
];