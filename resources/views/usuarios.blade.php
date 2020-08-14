
@extends('layouts.admin-template')

@section('content')
	<div id="users">
		<h1>Hola Mundo</h1>	
		<contact></contact>
	</div>
@endsection


@Push('customScript')
    <script src="{{ asset('js/app-admin.js') }}"></script>
@endpush
