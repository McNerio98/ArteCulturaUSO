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
                <label class="btn btn-default text-center" v-for="(e,index) in ABC" @click="onSelectFilter(e)" :class="{'abc-active': (filterSelected == e)}">
                    <span class="text-xl">@{{e}}</span>
                </label>
            </div>        
        </div>

        <div class="row">
            <no-records v-if="items.length == 0" icon="box.svg" page="Biografías/Homenajes"></no-records>
            <memory-summary v-else v-for="(e,index) of items" :key="e.memory.id" :pdata="e" @on-read="onReadMemory"/>
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
