<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
    <title>Nuovo Contatto</title>
</head>

<body class="container d-flex align-items-center justify-content-center vh-100 bg-light">
    <div id="box" class="card shadow p-4">
        <h1 class="text-danger">All'attenzione dell'amministratore</h1>
        <p class="lead">Hai ricevuto un nuovo messaggio:</p>
        <br>
        <p><strong>Nome:</strong> <span>{{ $name }}</span></p>
        <p><strong>Email:</strong> <span>{{ $email }}</span></p>
        <h4 class="mt-4">Messaggio:</h4>
        <p class="fst-italic text-danger">{{ $message ?? 'Nessun messaggio fornito' }}</p>
    </div>
</body>

</html>