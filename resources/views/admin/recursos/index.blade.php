@extends('layouts.admin-template')
@section('title', 'Recursos')
@section('windowName', 'Lista de recursos')

@section('PanelTitle', 'RECURSOS')
@section('PanelSubtitle', 'REGISTROS')

@section('content')
<div class="container-fluid" id="appResourcesAdminIndex">
    <div class="container">

        @can('crear-recursos')
            <a class="btn  bg-gradient-success mb-3" href="{{route('recursos.create.admin')}}">
                <i class="fas fa-plus"></i>
                Crear nuevo recurso
            </a>
        @endcan        

        <div style="overflow-x: auto;padding: 5px;" class="mb-3">
            <div class="btn-group btn-group-toggle">
                <label class="btn btn-default text-center" @click="onSelectFilter('ALL')" :class="{'abc-active': (filterSelected == 'ALL')}">
                    <span class="text-xl">Todos</span>
                </label>
                <label class="btn btn-default text-center" v-for="(e,index) in recursoTypes" @click="onSelectFilter(e.id)" :class="{'abc-active': (filterSelected == e.id)}">
                    <span class="text-xl">@{{e.name}}</span>
                </label>
            </div>        
        </div>        

        <div class="row mb-2" v-if="!isGettingResources">
            <no-records-found v-if="items.length == 0" icon="box.svg" product="Recursos"></no-records-found>
            <resouce-summary v-else v-for="(e) in items" :pdata="e" :key="e.resource.id" @on-read="onReadResource"/>
        </div>
        <div class="row" v-else>
            <div class="col-12">
                    <spinner1 label="Cargando recursos …"></spinner1>
            </div>
         </div>      

        <div class="pb-3 pt-3" v-if="showPagination">
            <pagination v-if="routeDynamic != null" :route="routeDynamic" @source-items="onLoadData" :key="componentPagKey"></pagination>
        </div>

    </div>
</div>
@endsection

@Push('customScript')
    <script src="{{ asset('js/admin/app-resources.js') }}"></script>
@endpush
