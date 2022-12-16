@extends('layouts.admin-template')
@section('title', 'Recursos')
@section('windowName', 'Lista de recursos')

@section('PanelTitle', 'RECURSOS')
@section('PanelSubtitle', 'REGISTROS')

@section('content')
<main role="main" class="flex-shrink-0" id="appResourcesAdminIndex">
    <div class="container">
        @can('crear-recursos')
        <a href="{{route('recursos.create.admin')}}">+Nuevo</a>
        @endcan

        <div style="overflow-x: auto;padding: 5px;" class="mb-3">
            <div class="btn-group btn-group-toggle">
                <label class="btn btn-default text-center" @click="onSelectFilter('ALL')" :class="{'abc-active': (filterSelected == 'ALL')}">
                    <span class="text-xl">Todos</span>
                </label>
                <label class="btn btn-default text-center" v-for="(e,index) in recursoTypes" @click="onSelectFilter(e.id)" :class="{'abc-active': (filterSelected == e.id)}">
                    <span class="text-xl">@{{e.option}}</span>
                </label>
            </div>        
        </div>        

        <div class="row mb-2">
            <no-records v-if="items.length == 0" icon="box.svg" page="Recursos"></no-records>
            <resouce-summary v-else v-for="(e) in items" :pdata="e" :key="e.id" @on-read="onReadResource"/>
        </div>

        <div class="pb-3 pt-3" v-if="showPagination">
            <pagination v-if="routeDynamic != null" :route="routeDynamic" @source-items="onLoadData" :key="componentPagKey"></pagination>
        </div>

    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/admin/app-resources.js') }}"></script>
@endpush
