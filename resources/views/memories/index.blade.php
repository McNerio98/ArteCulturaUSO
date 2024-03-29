@extends('layouts.general-template')
@section('title', 'Homenajes')

@section('content')
<main role="main" class="flex-shrink-0" id="appMemoryIndex">
    <div class="container bg-white">

        <div style="overflow-x: auto;padding: 5px;" class="mb-3">
            <div class="btn-group btn-group-toggle">
                <label class="btn btn-default text-center" @click="onSelectFilter('ALL')" :class="{'abc-active': (filterSelected == 'ALL')}">
                    <span class="text-xl">Todos</span>
                </label>
                <label class="btn btn-default text-center" style="padding-top: 15px;" v-for="(e,index) in ABC" @click="onSelectFilter(e)" :class="{'abc-active': (filterSelected == e)}">
                    <img style="width: 25px;" :src="'{{asset('images/ABC/Letter')}}' + e + '.png'" alt="">
                </label>
            </div>        
        </div>

        <div class="row" v-if="!isGettingResources">
            <no-records-found v-if="items.length == 0" icon="box.svg" product="Biografías/Homenajes"></no-records-found>
            <memory-summary v-else v-for="(e,index) of items" :key="e.memory.id" :pdata="e" @on-read="onReadMemory"/>
        </div>

        <div class="row" v-if="isGettingResources">
            <div class="col-12">
                    <spinner1 label="Cargando Biografías/Homenajes …"></spinner1>
            </div>
         </div>        
        
        <div class="pb-3 pt-3" v-if="showPagination">
            <pagination v-if="routeDynamic != null" :route="routeDynamic" @source-items="onLoadData" :key="componentPagKey"></pagination>
        </div>
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-memories.js') }}"></script>
@endpush
