@extends('layouts.general-template')
@section('title', 'Homenajes')

@section('content')
<main role="main" class="flex-shrink-0" id="app_memories_client">
    <div class="container">
        <building-page page="Homenajes"></building-page>
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/app-memories.js') }}"></script>
@endpush
