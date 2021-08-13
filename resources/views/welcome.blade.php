@extends('layouts.general-template')
@section('title', 'Inicio')

@section('content')
<main role="main" class="flex-shrink-0" id="app_inicio">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7 margin-no">
            @include("layouts.components.slider")
            </div>
            <div class="col-md-5 margin-no">
            @include("layouts.components.banner-solicitud")
            </div>
        </div>
    </div>
    <div class="container bg-tenue-ac">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <div class="row">
            <div class="col-12">
                <div class="SectionWelcome">
                    <h4 class="hSectionW text-center">¿BUSCAS ALGÚN TALENTO/ARTISTA?</h4>
                    <div class="row featurette">
                        <div style="display: flex; justify-content: center;" class="col-12">
                            <p class="lead" style="text-align: center; font-size: 18px; color:#212529;width: 700px">
                                En Sonsonate hay muchos tipos de artistas,
                                que tienen un talento muy asombroso, puedes conocerlos a través de su perfil.
                            </p>
                        </div>
                    </div>               
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <search-component @generated-filter="exeSeach"></search-component>
            </div>
        </div>

        @if(count($some_categories)>0)
        <div class="row pt-2 pt-md-3">
            @foreach($some_categories as $c)
            <div class="col-6 col-md-3">
                <a class="unique-section" href="{{url('/')."/search?sp=both&cat=".$c->id}}">
                    <img class="avatarArt" src="{{asset('/images/'.$c->img_presentation)}}">
                    <span class="text-section">{{$c->name}}</span>
                </a>
            </div>
            @endforeach
        </div>
        <p class="text-center h4"><a style="text-decoration:underline;" href="#">Ver todas las categorías</a></p>
        @else
        <p class="text-center text-primary h3">Actualmente no hay categorías disponibles</p>
        @endif

        <hr />

        <!--EVENTS TABLE-->
        <div class="row mt-2 mt-md-5">
            <div class="col-12">
                <div class="SectionWelcome">
                    <h4 class="hSectionW text-center">TABLERO DE EVENTOS</h4>
                    <div class="row featurette">
                        <div style="display: flex; justify-content: center;" class="col-12">
                            <p class="lead" style="text-align: center; font-size: 18px; color:#212529;width: 700px">
                                Descubre todos los eventos a los que puedes asistir en los próximos días.
                            </p>
                        </div>
                    </div>               
                </div>
            </div>
        </div>

        <div class="row mt-2 mt-md-3">
                <summary-item v-for="event of events" :model="event"></summary-item>
        </div>
        <p class="text-center h4"><a style="text-decoration:underline;" href="{{route('events')}}">Ver todos los eventos</a></p>
        <!--END EVENTS TABLE-->
        
    <hr/>
        <!--CENTROS DE ENSEÑANZAS Y ACADEMIAS-->
        <div class="row mt-2 mt-md-5">
            <div class="col-12">
                <div class="SectionWelcome">
                    <h4 class="hSectionW text-center">CENTROS DE ENSEÑANZAS Y ACADEMIAS</h4>
                    <div class="row featurette">
                        <div style="display: flex; justify-content: center;" class="col-12">
                            <p class="lead" style="text-align: center; font-size: 18px; color:#212529;width: 700px">
                                Encuentra lugares que te ofrecer la oportunidad en el maravilloso mundo al arte y cultura.
                            </p>
                        </div>
                    </div>               
                </div>
            </div>
        </div>

        <div class="row mt-2 mt-md-3">
            <div class="col-md-4" style="padding-left:15px !important;padding-right: 15px !important;">
                Contet......
            </div>
        </div>
        <p class="text-center h4"><a style="text-decoration:underline;" href="#">Ver todos</a></p>
        <!--END CENTROS DE ENSEÑANZAS Y ACADEMIAS-->


        <h1 style="text-align: center; color:rgb(104, 104, 104);; font-size:25px;margin-top: 50px">
            RECURSOS VIRTUALES
        </h1>
        <hr />
        <div class="row">
            <div style="display: flex; justify-content: center;" class="col-12">
                <img src="{{asset('images/bibloteca.png')}}" alt="">
            </div>
        </div>
        <div class="row">
            <div style="display: flex; justify-content: center;" class="col-12">
                <p class="lead" style="text-align: center; font-size: 18px; color:#212529; margin-top: 30px;width: 700px">
                    Descubre nuestra biblioteca virtual, donde puedes encontrar recursos informativos,
                    revistas, libros, audios y videos.
                </p>
            </div>
        </div>            
        <div class="row mb-md-3">
            <div style="display: flex; justify-content: center;" class="col-12">
                <a class="btn btn-primary" href="#" role="button">Ver todos los recursos</a>
            </div>
        </div>    
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ mix('js/front/app-inicio.js') }}"></script>
@endpush
