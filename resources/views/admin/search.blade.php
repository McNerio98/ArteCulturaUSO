@extends('layouts.admin-template')
@section('title', 'Búsqueda')
@section('windowName', 'BUSCADOR AVANZADO')


@section('content')
<div class="container" id="appSearch">
        <building-page page="Búsquedas avanzadas"></building-page>
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-search.js') }}"></script>
@endpush
