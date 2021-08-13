@extends('layouts.general-template')
@section('title', 'Eventos')


@section('content')
<style>
    .jumbotron {
    padding-top: 3rem;
    padding-bottom: 3rem;
    margin-bottom: 0;
    background-color: #fff;
    }
    @media (min-width: 768px) {
    .jumbotron {
        padding-top: 6rem;
        padding-bottom: 6rem;
    }
    }

    .jumbotron p:last-child {
    margin-bottom: 0;
    }

    .jumbotron h1 {
    font-weight: 300;
    }

    .jumbotron .container {
    max-width: 40rem;
    }

    footer {
    padding-top: 3rem;
    padding-bottom: 3rem;
    }

    footer p {
    margin-bottom: .25rem;
    }    
</style>
<main role="main" class="flex-shrink-0" id="app_events">
    <section class="jumbotron text-center">
        <div class="container">
            <h1>Tablero de Eventos</h1>
            <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
            <p>
                <a href="#" class="btn btn-primary my-2">Main call to action</a>
                <a href="#" class="btn btn-secondary my-2">Secondary action</a>
            </p>
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
            <div class="row" v-if="!spinners.S1">
                <summary-item v-for="event of events" :model="event"></summary-item>                
            </div> 
        </div>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>        
</main>
@endsection
@Push('customScript')
    <script src="{{ mix('js/front/app-events-table.js') }}"></script>
@endpush