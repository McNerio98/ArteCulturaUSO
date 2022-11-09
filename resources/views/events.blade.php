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



    <div class="container bg-white">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <div class="container pt-2">

        <section v-if="postevent_selected == undefined" class="p-3">
            <div class="container">
                <h1>Tablero de Eventos</h1>
                <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
            </div>
        </section>

        <div class="ac_tbl-container">
                <!--No quitar las etiquetas de cierren, si se usa <component/> tiene un comportamiento incongruente-->
                <table-event v-for="(k,index2) in [1,2,3,4,5,7,8,9]" :key="index2+'el002'"></table-event>
                <table-load-more></table-load-more>
        </div>        

        <div class="ac_tbl-container">
        
        </div>           


            

        </div>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>        
</main>
@endsection
@Push('customScript')
    <script src="{{ mix('js/front/app-events-table.js') }}"></script>
@endpush