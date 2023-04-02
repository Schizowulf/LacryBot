@extends('layouts.main')
@section('content')

<div class="row">
    <div class="col">
        <a class="btn btn-primary" href="/register">Sign up</a>
    </div>
    <div class="col">
        <a class="btn btn-primary" href="/login">Sign in</a>
    </div>
</div>
@auth
<div class="row">
    <div class="col">
        <form method="POST" action="/logout">
            @csrf
            <button class="btn btn-warning" type="submit">
              <i class="fa-solid fa-door-closed"></i> Logout
            </button>
        </form>
    </div>
</div>
@endauth
@endsection