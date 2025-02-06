<?php

namespace App\Http\Controllers\Api;

use Google\Client;
use Google\Service\Gmail;
use App\Services\GoogleTokenService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Google_Service_Gmail_Message;

class MailController extends Controller
{
    private $tokenService;

    public function __construct(GoogleTokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Configura il client Gmail.
     */
    private function configureGmailClient()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Gmail::MAIL_GOOGLE_COM);
        $client->setAccessType('offline');

        // Autenticazione tramite il GoogleTokenService
        $this->tokenService->authenticate($client);

        return $client;

    }

    /**
     * Invia una mail in formato HTML.
     */
    public function sendEmail(Request $request)
    {
        // Validazione dei dati inviati tramite la richiesta API
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Parametri del destinatario e del contenuto
        $to = 'angelociulla89@gmail.com';
        $subject = 'Nuovo Messaggio di Contatto!';

        // Rendering del template Blade (mails.new-contact)
        $htmlContent = view('mails.new-contact', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
        ])->render();

        // Crea il corpo del messaggio HTML
        $rawMessage = "To: {$to}\r\n";
        $rawMessage .= "Subject: =?utf-8?B?" . base64_encode($subject) . "?=\r\n";
        $rawMessage .= "MIME-Version: 1.0\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $rawMessage .= $htmlContent; // Corpo dell'email

        // Codifica il messaggio in base64 (URL-safe)
        $rawMessage = base64_encode($rawMessage);
        $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage); // Codifica URL-safe

        // Crea il messaggio Gmail
        $gmailMessage = new Google_Service_Gmail_Message();
        $gmailMessage->setRaw($rawMessage);

        // Configura il servizio Gmail
        $service = new \Google_Service_Gmail($this->configureGmailClient());

        try {
            // Invia l'email tramite Gmail API
            $service->users_messages->send('me', $gmailMessage);
            return response()->json(['message' => 'HTML email sent successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
;