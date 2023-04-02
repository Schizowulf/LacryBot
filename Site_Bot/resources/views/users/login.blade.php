@extends('layouts.main')
@section('content')
<div class="row justify-content-center">
	<form class="w-50" method="POST" action="/login-user">
		@csrf
		<label for="email" class="form-label">Email</label>
		<input type="email" class="form-control mb-3" name="email" value="{{old('email')}}" />
		@error('email')
			<p class="">{{$message}}</p>
		@enderror
		<label for="password" class="form-label">Password</label>
		<input type="password" class="form-control mb-3" name="password" value="{{old('password')}}" />
		@error('password')
			<p class="">{{$message}}</p>
		@enderror
		<button type="submit" class="btn btn-primary w-100">Sign In</button>
	</form>
</div>
@endsection