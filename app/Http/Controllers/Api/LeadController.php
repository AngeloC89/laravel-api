<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewContact;
use Google\Client;
use Google\Service\Gmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        // Validazione del form
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Dati dal form
        $name = $validated['name'];
        $email = $validated['email'];
        $message = $validated['message'];

        // Usa il Mailable per generare l'HTML
        $mailable = new NewContact($name, $email, $message);
        $htmlBody = $mailable->renderHtml();

        // Configura il client Gmail
        $client = $this->configureGmailClient();

        // Leggi e aggiorna il token se necessario
        $this->handleTokens($client);

        // Crea e invia l'email
        return $this->sendGmail($client, $htmlBody);
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
        $client->setAccessType('offline');
        $client->addScope('https://www.googleapis.com/auth/gmail.send');

        return $client;
    }

    /**
     * Gestisce la lettura e il rinnovo dei token di accesso.
     */
    private function handleTokens(Client $client)
    {
        // Leggi il token di accesso salvato sul DB
        $tokenPath = PersonalAccessToken::where('tokenable_id', Auth::id())
            ->where('name', 'Gmail API Token')
            ->first();

        if ($tokenPath) {
            $accessToken = Crypt::decryptString($tokenPath->token);
            // Usa il token con il client Gmail
            $client->setAccessToken($accessToken);
        }

        // Aggiorna il token se necessario
        $refreshTokenRecord = PersonalAccessToken::where('tokenable_id', Auth::id())
            ->where('name', 'Gmail Refresh Token')
            ->first();

        if ($refreshTokenRecord) {
            $refreshToken = Crypt::decryptString($refreshTokenRecord->token);
            $newAccessToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);

            // Se il nuovo access token è valido, aggiorna il DB
            if (isset($newAccessToken['access_token'])) {
                // Salva il nuovo access token nel DB
                PersonalAccessToken::where('tokenable_id', Auth::id())
                    ->where('name', 'Gmail API Token')
                    ->update([
                        'token' => Crypt::encryptString($newAccessToken['access_token']),
                        'expires_at' => now()->addHours(1), // Nuova scadenza
                    ]);
            }
        }
    }

    /**
     * Crea e invia un'email tramite le API di Gmail.
     */
    private function sendGmail(Client $client, $htmlBody)
    {
        try {
            // Crea il servizio Gmail
            $gmail = new Gmail($client);

            // Crea il messaggio raw
            $rawMessage = $this->createRawMessage($htmlBody);

            // Codifica il messaggio
            $encodedMessage = base64_encode($rawMessage);
            $encodedMessage = str_replace(['+', '/', '='], ['-', '_', ''], $encodedMessage);

            // Prepara l'oggetto di invio
            $message = new \Google\Service\Gmail\Message();
            $message->setRaw($encodedMessage);

            // Invia l'email
            $gmail->users_messages->send('me', $message);

            return response()->json(['message' => 'Email inviata con successo!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Crea il messaggio raw in formato RFC822.
     */
    private function createRawMessage($htmlBody)
    {
        $rawMessage = "To: angelociulla89@gmail.com\r\n";
        $rawMessage .= "Subject: Nuovo Contatto dal Portfolio\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $rawMessage .= $htmlBody;

        return $rawMessage;
    }

}
;