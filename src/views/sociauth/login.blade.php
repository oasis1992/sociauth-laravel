<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <link rel="stylesheet" href="">
    </head>
    <body>
        <a href="{{ $url_provider }}">Login</a>
        @unless (Auth::check())
            You are not signed in.
        @endunless


        <p>{{ $message or '' }}</p>
    </body>
</html>