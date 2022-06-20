@extends('layouts.general-template')
@section('title', 'Eventos')


@section('content')
<style>
    .jumbotron p:last-child {
    margin-bottom: 0;
    }

    .jumbotron h1 {
    font-weight: 300;
    }

    .jumbotron .container {
    max-width: 40rem;
    }
</style>
<main role="main" class="flex-shrink-0" id="app_events">
    <br>
    <input type="hidden" id="targetOpenItem" value="{{app('request')->input('target')}}">

    <section v-if="postevent_selected == undefined" class="jumbotron text-center eventHeaders" >
        <div class="container">
            <h1>Tablero de Eventos</h1>
            <p class="lead text-muted">Conoce todo los eventos programados.</p>
        </div>
    </section>

    <div class="container bg-tenue-ac">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <div class="container pt-2 bg-tenue-ac">
            <div class="row" v-if="spinners.S1">
                <div class="col-12 text-center">
                    <spinner1 label="Cargando eventos …"></spinner1>
                </div>
            </div>
            <post-general @source-files="onSources" v-if="postevent_selected != undefined" v-bind:model="postevent_selected"></post-general>             
            <div class="row" v-if="!spinners.S1">
                <summary-item v-for="event of events" @selected-item="onClickEvent" :model="event"></summary-item>                
            </div> 
        </div>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>        
</main>
@endsection
@Push('customScript')
    <script src="{{ mix('js/front/app-events-table.js') }}"></script>
@endpush