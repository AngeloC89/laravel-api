<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewContact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Google\Client;
use Google\Service\Gmail\Message;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {

        //dd("test di funzionamento");

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
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->addScope('https://www.googleapis.com/auth/gmail.send');

        // Leggi il token di accesso salvato su S3
        $tokenPath = 'gmail_tokens/google-refresh-token.json';
        if (!Storage::disk('s3')->exists($tokenPath)) {
            return response()->json(['error' => 'Token non trovato. Effettua nuovamente l\'autenticazione.'], 500);
        }

        $accessToken = json_decode(Storage::disk('s3')->get($tokenPath), true);
        $client->setAccessToken($accessToken);
        ;

        // Aggiorna il token se necessario
        if ($client->isAccessTokenExpired()) {
            $refreshToken = $client->getRefreshToken();
            $newAccessToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
            file_put_contents($tokenPath, json_encode($newAccessToken));
            $client->setAccessToken($newAccessToken);
        }

        // Crea l'oggetto Gmail
        $gmail = new \Google\Service\Gmail($client);

        // Crea il messaggio
        $rawMessage = "To: angelociulla89@gmail.com\r\n";
        $rawMessage .= "Subject: Nuovo Contatto dal Portfolio\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $rawMessage .= $htmlBody;


        // Codifica il messaggio
        $encodedMessage = base64_encode($rawMessage);
        $encodedMessage = str_replace(['+', '/', '='], ['-', '_', ''], $encodedMessage);

        // Prepara l'oggetto di invio
        $message = new Message();
        $message->setRaw($encodedMessage);

        // Invia l'email
        try {
            $gmail->users_messages->send('me', $message);
            return response()->json(['message' => 'Email inviata con successo!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
;