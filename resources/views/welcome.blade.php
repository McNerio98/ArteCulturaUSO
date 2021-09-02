@extends('layouts.general-template')
@section('title', 'Inicio')

@section('content')
<main role="main" class="flex-shrink-0" id="app_inicio">
    <input type="hidden" id="openRegister" value="{{app('request')->input('register')}}">
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
        <div class="_acScrollmenu mb-1 mb-md-2">
            @foreach($some_categories as $c)
            <div class="_acCatItem m-1 m-md-2">
                    <a class="_uniqueSection" style="padding: 10px;" href="{{url('/').'/search?id_filter='.$c->id.'&label='.$c->name.'&type_search=cat'}}"> 
                        <img  src="{{asset('/files/categories/'.$c->img_presentation)}}" alt="" class="avatarArt">
                        <span class="text-section">{{$c->name}}</span>
                    </a>
            </div>
            @endforeach            
        </div>
        <p class="text-center h4"><a style="text-decoration:underline;" href="{{route('search')}}">Ver todas las categorías</a></p>
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
        <p class="text-center h4 mb-1 mb-md-5"><a style="text-decoration:underline;" href="{{route('events')}}">Ver todos los eventos</a></p>
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
        <p class="text-center h4 mb-1 mb-md-5"><a style="text-decoration:underline;" href="{{url('/').'/search?id_filter=10&label=Escuelas&type_search=cat'}}">Ver todos</a></p>
        <!--END CENTROS DE ENSEÑANZAS Y ACADEMIAS-->

        <hr />
        <div class="row">
            <img src="{{asset('images/biblioteca_virtual.png')}}" alt="" style="width: 100%;">
        </div>
        <div class="row">
            <div style="display: flex; justify-content: center;" class="col-12">
                <p class="lead" style="text-align: center; font-size: 18px; color:#212529; margin-top: 30px;width: 700px">
                    Descubre nuestra biblioteca virtual, donde puedes encontrar recursos informativos,
                    revistas, libros, audios y videos.
                </p>
            </div>
        </div>            
        <div class="row mb-1 mb-md-5">
            <div style="display: flex; justify-content: center;" class="col-12">
                <a class="btn btn-primary " href="{{route('recursos')}}" role="button">Ver todos los recursos</a>
            </div>
        </div>    
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ mix('js/front/app-inicio.js') }}"></script>
@endpush
