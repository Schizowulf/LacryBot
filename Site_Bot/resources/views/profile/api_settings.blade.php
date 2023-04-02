@extends('layouts.profile')
@section('content')
<script>
	var csrf = "{{csrf_token()}}"
</script>
<div id="api-credentials">
</div>
@endsection