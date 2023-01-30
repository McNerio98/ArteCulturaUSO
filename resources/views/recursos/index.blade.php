@extends('layouts.general-template')
@section('title', 'Homenajes')

@section('content')
<main role="main" class="flex-shrink-0" id="appResourcesIndex">
    <div class="container bg-white pt-3 pb-3">
        
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

        <div class="row" v-if="!isGettingResources">
            <no-records-found v-if="items.length == 0" icon="box.svg" product="Recursos"></no-records-found>
            <resource-summary v-else v-for="(e) in items" :pdata="e" :key="e.resource.id" @on-read="onReadResource"/>
        </div>

        <div class="row" v-if="isGettingResources">
            <div class="col-12">
                    <spinner1 label="Cargando recursos …"></spinner1>
            </div>
         </div>        

        <div class="pb-3 pt-3" v-if="showPagination">
            <pagination v-if="routeDynamic != null" :route="routeDynamic" @source-items="onLoadData" :key="componentPagKey"></pagination>
        </div>
                
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-resources.js') }}"></script>
@endpush
