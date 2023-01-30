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

        <section class="p-3">
            <div class="container">
                <h1>Tablero de Eventos</h1>
                <p class="lead text-muted">Agenda que se estará llevando a cabo en los siguientes días , donde podrás encontrar a los diversos sectores artísticos promover sus eventos para niños , adultos y familias interesados , ven y disfruta a la ciudad de los cocos , Sonsonate no faltes.</p>
            </div>
        </section>

        <div class="ac_tbl-container">
                <!--No quitar las etiquetas de cierren, si se usa <component/> tiene un comportamiento incongruente-->
                <table-event v-for="(e,index) in events" :pdata="e" :key="e.post.id" @on-show="onSeeMore"></table-event>
                <table-load-more v-if="isEnableMore" @onmore="onLoadMore"></table-load-more>
        </div>        
        <hr/>
        <div class="mt-3 mb-3">
            <h4 class="text-center">Encuentra eventos cerca de ti, esta opción requerirá acceso a tu ubicación actual.</h4>
            <div style="background-image: url('{{asset('images/bg-maps.jpg')}}');" class="ac_bgmaps-nearby">
                <div class="ac_bgmaps-black">
                    <div class="ac_bgmaps-content">
                        <a href="{{route('nearby')}}">
                            <img src="{{asset('images/icons/gps-svgrepo-com.svg')}}" alt="" class="ac_img_nearby">
                            EVENTOS CERCANOS
                        </a>                        
                    </div>
                </div>
            </div>
        </div>    
        <hr/>       


            

        </div>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>        
</main>
@endsection
@Push('customScript')
    <script src="{{ mix('js/front/app-events-table.js') }}"></script>
@endpush