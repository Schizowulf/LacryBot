<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Document</title>
</head>
<body>
    <ul class="nav w-25 m-auto">
        @auth
        <li class="nav-item"><a href="/profile" class="nav-link">Profile</a></li>
        @endauth
        <li class="nav-item"><a href="/" class="nav-link">Trade</a></li>
    </ul>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>