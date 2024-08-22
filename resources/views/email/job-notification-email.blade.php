<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Correo Electrónico de Notificación de Empleo</title>
</head>

<body>
    <h1>Hola {{ $mailData['employer']->name }}</h1>

    <p>Título de Trabajo: {{ $mailData['job']->title }}</p>

    <p>Detalles de Empleado:</p>

    <p>Nombre: {{ $mailData['user']->name }}</p>
    <p>Correo Electrónico: {{ $mailData['user']->email }}</p>
    <p>Teléfono: {{ $mailData['user']->mobile }}</p>

</body>

</html>
