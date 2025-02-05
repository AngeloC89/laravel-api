<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/js/app.js'])
    <title>Nuovo Contatto</title>
</head>

<body style="background-color: #f8f9fa; width: 100%; height: 100%;">
    <div
        style="width: 100%; height: 400px; background-image: url( 'images/image2mani.png'); background-position: center;">

    </div>
    <div style="text-align: left;  width: 50%; margin: 50px 30px">
        <h1 style="font-weight: bold; color: #fb6309">All'attenzione dell'amministratore</h1>
        <br>
       <span style="text-align: left"><h3 style="font-weight: bold; color: #ff0000; display: inline">Mittente:</h3>
       <p style="display: inline; font-size: 40px; margin:0px 10px;">{{ $name }}</p></span> 
       <br>
        <span><h3 style="font-weight: bold; color: #ff0000; display: inline">Email:</h3> <p style="display: inline; font-size: 40px; margin:0px 10px;">{{ $email }}</p></span>
        <h4 style="font-weight: bold; font-size: 40px; margin-top:30px; ">Messaggio:</h4>
        <p style="font-size: 50px">{{ $message ?? 'Nessun messaggio fornito' }}</p>
    </div>
</body>

</html>