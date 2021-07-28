@extends('layouts.general-template')
@section('title', 'Recursos')

@section('content')
<main role="main" class="flex-shrink-0" id="app_resources_client">
    <div class="container">
        <building-page page="Recursos/Biblioteca"></building-page>
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/app-resources.js') }}"></script>
@endpush
