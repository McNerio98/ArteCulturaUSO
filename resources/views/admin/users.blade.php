@extends('layouts.admin-template')
@section('title', 'Usuarios')
@section('windowName', 'USUARIOS REGISTRADOS ')

@section('content')
<div class="container-fluid" id="users">
		<pnl-pagination :paths="paths"></pnl-pagination>
</div>
<!--/. container-fluid -->  
@endsection


@Push('customScript')
    <script src="{{ asset('js/admin/app-users.js') }}"></script>
@endpush
