
@extends('layouts.admin-template')

@section('content')
<div class="container-fluid" id="users">
		<pnl-pagination></pnl-pagination>
</div>
<!--/. container-fluid -->  
@endsection


@Push('customScript')
    <script src="{{ asset('js/app-users.js') }}"></script>
@endpush
