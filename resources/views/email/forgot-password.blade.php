<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Correo Electrónico de Olvido de Contraseña</title>
</head>

<body>
    <h1>Hola {{ $mailData['user']->name }}</h1>

    <p>Haz click abajo para cambiar tu contraseña</p>

    <a href="{{ route('account.resetPassword', $mailData['token']) }}">Click Aquí</a>

    <p>Gracias</p>

</body>

</html>
