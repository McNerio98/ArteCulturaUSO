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
<main role="main" class="flex-shrink-0" id="appEventsTable">
    <input type="hidden" id="targetOpenItem" value="{{app('request')->input('target')}}">

    <section v-if="postevent_selected == undefined" class="jumbotron text-center" style="background-color: rgb(233, 236, 239);">
        <div class="container">
            <h1>Tablero de Eventos</h1>
            <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
        </div>
    </section>

    <div class="container bg-white">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <div class="container pt-2">
            <div class="row" v-if="spinners.S1">
                <div class="col-12 text-center">
                    <spinner1 label="Cargando eventos …"></spinner1>
                </div>
            </div>

            

        </div>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>        
</main>
@endsection
@Push('customScript')
    <script src="{{ mix('js/front/app-events-table.js') }}"></script>
@endpush