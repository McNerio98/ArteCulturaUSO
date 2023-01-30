@extends('layouts.admin-template')
@section('title', 'Reseñas')
@section('windowName', 'HOMENAJES / BIOGRAFÍAS ')

@section('PanelTitle', 'HOMENAJES / BIOGRAFÍAS')
@section('PanelSubtitle', 'REGISTROS')

@section('content')
<div class="container-fluid" id="appMemories">       
    <div class="container">
        @can('crear-biografias')
            <a class="btn  bg-gradient-success mb-3" href="{{route('memories.create.admin')}}">
                <i class="fas fa-plus"></i>
                Crear nueva Biografía/Homenaje
            </a>
        @endcan

        <div style="overflow-x: auto;padding: 5px;" class="mb-3">
            <div class="btn-group btn-group-toggle">
                <label class="btn btn-default text-center" @click="onSelectFilter('ALL')" :class="{'abc-active': (filterSelected == 'ALL')}">
                    <span class="text-xl">Todos</span>
                </label>
                <label class="btn btn-default text-center" v-for="(e,index) in ABC" @click="onSelectFilter(e)" :class="{'abc-active': (filterSelected == e)}">
                    <span class="text-xl">@{{e}}</span>
                </label>
            </div>        
        </div>

        <div class="row mb-2" v-if="!isGettingResources">
                <no-records-found v-if="items.length == 0" icon="box.svg" product="Biografías/Homenajes"></no-records-found>
                <memory-summary v-else v-for="(e,index) in items" :key="e.memory.id" :pdata="e" @on-read="onReadMemory">
        </div>
        <div class="row" v-else>
            <div class="col-12">
                    <spinner1 label="Cargando Biografías/Homenajes …"></spinner1>
            </div>
         </div>              

        <div class="pb-3 pt-3" v-if="showPagination">
            <pagination v-if="routeDynamic != null" :route="routeDynamic" @source-items="onLoadData" :key="componentPagKey"></pagination>
        </div> 

    </div>       
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-memories.js') }}"></script>
@endpush
