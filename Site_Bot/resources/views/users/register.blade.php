@extends('layouts.main')
@section('content')
<div class="row justify-content-center">
	<form class="w-50" method="POST" action="/register-user">
		@csrf
		<label for="name" class="form-label">Name</label>
		<input type="text" class="form-control mb-3" name="name" value="{{old('name')}}" />
		@error('name')
			<p class="">{{$message}}</p>
		@enderror
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
		<label for="password2" class="form-label">Confirm Password</label>
		<input type="password" class="form-control mb-3" name="password_confirmation" value="{{old('password_confirmation')}}" />
		@error('password_confirmation')
			<p class="">{{$message}}</p>
		@enderror
		<button type="submit" class="btn btn-primary mb-3 mt-3 w-100">Sign Up</button>
		<p>Already have an account?
			<a href="/login" class="btn btn-primary">Login</a>
		</p>
	</form>
</div>
@endsection