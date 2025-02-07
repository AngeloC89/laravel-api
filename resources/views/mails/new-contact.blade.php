<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo Contatto</title>
</head>

<body style="background-color: #f8f9fa; width: 100%; margin: 0; padding: 0; font-family: Arial, sans-serif;">

    <!-- Sezione Header con Immagine -->
    <table role="presentation" width="100%" style="background-color: #ffffff;">
        <tr>
            <td style="display: flex; justify-content: center; ">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/image2mani.png'))) }}"
                    alt="Header Image">
            </td>
        </tr>
    </table>

    <!-- Contenuto Email -->
    <table role="presentation"
        style="width: 100%; margin: 0 auto; padding: 20px; background-color: #ffffff;">
        <tr>
            <td>
                <h1 style="color: #fb6309; font-size: 24px; text-align: center;">All'attenzione dell'amministratore</h1>
                <hr style="border: 1px solid #ddd;">

                <p style="font-size: 18px; color: #333;"><strong style="color: #ff0000;">Mittente:</strong> {{ $name }}
                </p>
                <p style="font-size: 18px; color: #333;"><strong style="color: #ff0000;">Email:</strong> {{ $email }}
                </p>

                <h4 style="font-size: 22px; color: #000; margin-top: 20px;">Messaggio:</h4>
                <p style="font-size: 18px; color: #333;">{{ $message ?? 'Nessun messaggio fornito' }}</p>
            </td>
        </tr>
    </table>

</body>

</html>