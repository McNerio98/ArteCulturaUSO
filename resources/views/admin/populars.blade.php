@extends('layouts.admin-template')
@section('title', 'Destacados')
@section('windowName', 'ELEMENTOS DESTACADOS')

@section('content')
<div class="container-fluid" id="appPopulars">
    <!--NO CONTENT-->
    <div class="flex-shrink text-center p-md-3" style="max-width: 42em; margin:auto;" v-if="items_postevents.length == 0 && !spinners.S1">
        <img src="{{asset('images/no-task.svg')}}" alt="" style="width: 80px;">
        <h2 class="text-success">Ningún elemento que aprobar</h2>
        <p class="lead">Actualmente ninguna publicación o evento se encuentra esperando tu aprobación</p>
        <a href="{{route('inicio')}}" class="">Crear contenido para aprobación aquí</a></p>
    </div>    
    <!--END NO CONTENT-->

    <!--SPINNER LOADER-->
    <div class="p-md-5 d-flex justify-content-center align-items-center flex-column" v-if="spinners.S1">
        <div class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <h4 style="color: #38c172;">Cargando contenido...</h4>
    </div>
    <!--END SPINNER LOADER-->
    <div v-if="postevent_selected != undefined">
        <post-general @source-files="onSources" v-bind:model="postevent_selected"></post-general>                                        
    </div>
    
    <div class="row" v-if="items_postevents.length > 0 && !spinners.S1">
        <summary-item @selected-item="getDataItem" v-for="app of items_postevents" :model="app"></summary-item>
    </div>

    <media-viewer 
    :media-profile="false"  
    :target="media_view.target"
    :logged.number='{{Auth::user() == null ? 0 : Auth::user()->id}}'
    :owner="media_view.owner"
    :items="media_view.items">
    </media-viewer>         
</div>
@endsection

@Push('customScript')
    <script src="{{ mix('js/admin/app-populars.js') }}"></script>
@endpush
