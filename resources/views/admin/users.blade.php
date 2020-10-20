
@extends('layouts.admin-template')

@section('content')
	<div id="users">
		<input type="hidden" value="{{url('/')}}" id="url_server" name="url">
		<h1>Hola Mundo</h1>	
		<pnl-pagination></pnl-pagination>
	</div>
@endsection


@Push('customScript')
    <script src="{{ asset('js/app-users.js') }}"></script>
@endpush
