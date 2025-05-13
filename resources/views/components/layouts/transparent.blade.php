<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transparent Window</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            background: transparent;
        }
    </style>
</head>
<body>
@yield('content')
</body>
</html>
