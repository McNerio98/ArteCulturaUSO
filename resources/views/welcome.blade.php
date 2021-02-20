@extends('layouts.public-template')
@section('title', 'Inicio')
@section('content')
<div class="row" id="app_inicio">
    <div class="col-7 margin-no">
        <!--Seccion del slider-->
        @include("layouts.components.slider")
    </div>
    <div class="col-5 margin-no">
        @include("layouts.components.bannerSolicitud")
    </div>
    <br /><br />
    <div style="margin-top: 30px; background: white; padding: 15px;" class="container">
        <div class="row">
            <div class="col-12">
                <div class="SectionWelcome">
                    <h1 style="text-align: center; color:rgb(104, 104, 104);; font-size:25px; margin-top: 50px">¿BUSCAS ALGÚN TALENTO/ARTISTA? </h1>
                    <div class="row featurette">
                        <div style="display: flex; justify-content: center;" class="col-12">
                            <p class="lead"
                                style="text-align: center; font-size: 18px; color:#212529;width: 700px">
                                En Sonsonate hay muchos tipos de artistas,
                                que tienen un talento muy asombroso, puedes conocerlos a través de su perfil.
                            </p>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                <div class="col-md-8 offset-md-2">
                    <form action="simple-results.html">
                        <div class="input-group">
                            <input type="search" class="form-control form-control-lg" placeholder="Ejem. Grupo de música, payasos, casa de la cultura, etc.">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(count($some_categories)>0)
                    <div class="row pt-2 pt-md-3">
                        @foreach($some_categories as $c)
                            <div class="col-6 col-md-3">
                                <div class="unique-section">
                                    <img class="avatarArt" src="{{asset($c->img_presentation)}}">
                                    <span class="text-section">{{$c->name}}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-center h4"><a style="text-decoration:underline;" href="#">Ver todas las categorías</a></p>
            @else
                    <p class="text-center text-primary h3">Actualmente no hay categorías disponibles</p>
            @endif

                    <hr />
                    <p style="margin-top: 30px; margin-bottom:0px;font-size:4rem;" class="text-center text-muted"><i class="fas fa-broom"></i></p>
                    <div class="text-center">
                        <h3 class="featurette-heading">Centros de Enseñanza y Academias <span class="text-muted">en Sonsonate.</span></h3>
                        <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula
                                porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl
                                consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
                                <div style="display: flex; justify-content: center;" class="col-12">
                                    <a class="btn btn-primary" href="#" role="button">Ver todos los centros</a>
                                </div>                                                        
                    </div>

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
                            <p class="lead"
                                style="text-align: center; font-size: 18px; color:#212529; margin-top: 30px;width: 700px">
                                Descubre nuestra biblioteca virtual, donde puedes encontrar recursos informativos,
                                revistas, libros, audios y videos.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div style="display: flex; justify-content: center;" class="col-12">
                            <a class="btn btn-primary" href="#" role="button">Ver todos los recursos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
<script src="{{asset('js/app-request.js')}}"></script>
-->
@endsection

@Push('customScript')
    <script src="{{ asset('js/app-inicio.js') }}"></script>
@endpush
