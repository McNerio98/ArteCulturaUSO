@extends('layouts.admin-template')
@section('title', 'Recursos')
@section('windowName', 'Lista de recursos')

@section('content')
<main role="main" class="flex-shrink-0" id="appResourcesAdminIndex">
    <div class="container">
        <a href="{{route('recursos.create.admin')}}">+Nuevo</a>
        <div class="row mb-2">
            <resouce-summary v-for="(e) in items" :pdata="e" :key="e.id"/>
        </div>
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/admin/app-resources.js') }}"></script>
@endpush
