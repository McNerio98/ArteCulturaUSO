@extends('layouts.general-template')
@section('title', 'Biografías')

@section('content')
<main role="main" class="flex-shrink-0" id="app_bios_client">
    <div class="container">
        <building-page page="Biografías"></building-page>
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-biographies.js') }}"></script>
@endpush
