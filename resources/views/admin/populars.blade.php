@extends('layouts.admin-template')
@section('title', 'Destacados')
@section('windowName', 'ELEMENTOS DESTACADOS')

@section('content')
<div class="container-fluid" id="appContent">
    <div class="row">
            @{{saludo}}
    </div>
</div>
@endsection

@Push('customScript')
    <script src="{{ mix('js/admin/app-populars.js') }}"></script>
@endpush
