@extends('layouts.admin-template')
@section('title', 'Recursos')
@section('windowName', 'Lista de recursos')

@section('content')
<main role="main" class="flex-shrink-0" id="appResourcesAdminIndex">
    <div class="container">
        @can('crear-recursos')
        <a href="{{route('recursos.create.admin')}}">+Nuevo</a>
        @endcan
        <div class="row mb-2">
            <no-records v-if="items.length == 0" icon="box.svg" page="Recursos"></no-records>
            <resouce-summary v-else v-for="(e) in items" :pdata="e" :key="e.id" @on-read="onReadResource"/>
        </div>
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/admin/app-resources.js') }}"></script>
@endpush
