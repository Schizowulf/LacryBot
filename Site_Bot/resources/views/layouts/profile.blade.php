<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ URL::asset('js/app.bundle.js') }}" defer></script>
    <script src="{{ URL::asset('js/apiSettings.bundle.js') }}" defer></script>
    <script>
        var baseUrl = "{{ App::make('url')->to('/') }}"
        var currentUserId = "{{ Auth::id() }}"
    </script>
    <title>Profile</title>
</head>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-2 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start text-nowrap fs-4" id="menu">
                    <li class="nav-item">
                        <a href="/my-orders" class="nav-link align-middle px-0">
                            <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">My orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/api-settings" class="nav-link align-middle px-0">
                            <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">API</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col py-3">
            @yield('content')
        </div>
    </div>
</div>