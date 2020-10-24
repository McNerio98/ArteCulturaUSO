@Push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endpush
<<<<<<< HEAD

=======

>>>>>>> 98620b21c87f06fb70743405dcf008aa94f7a761
<link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
@extends('layouts.public-template')
@section('title', 'Inicio')
@section('content')
<div class="row">
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
                    <h1 style="text-align: center; color:rgb(104, 104, 104);; font-size:25px; margin-top: 50px">SECCION
                        DE ARTISTAS DESTACADOS</h1>
                    <hr />
                    <div class="row">
                        <div class="col-3">
                            <div class="unique-section">
                                <img class="avatarArt" src="{{asset('images/music.jpg')}}">
                                <span class="text-section">Musicos</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="unique-section">
                                <img class="avatarArt" src="{{asset('images/cine.jpg')}}">
                                <span class="text-section">Cineastas</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="unique-section">
                                <img class="avatarArt" src="{{asset('images/escritor.jpg')}}">
                                <span class="text-section">Escritores</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="unique-section">
                                <img class="avatarArt" src="{{asset('images/danza.jpg')}}">
                                <span class="text-section">Bailarines</span>
                            </div>
                        </div>
                    </div>
                    <div class="row featurette">
                        <div style="display: flex; justify-content: center;" class="col-12">
                            <p class="lead"
                                style="text-align: center; font-size: 18px; color:#212529; margin-top: 30px;width: 700px">
                                En Sonsonate hay muchos tipos de artistas,
                                que tienen un talento muy asombroso, puedes conocerlos a través de su perfil.
                            </p>
                        </div>
                    </div>
                    <hr />
                    <div class="row featurette">
                        <div class="col-md-7"
                            style="display: flex; flex-direction: column; justify-content: center; align-items: center">
                            <h1 class="featurette-heading">Centros de Enseñanza y Academias. <span class="text-muted">En Sonsonate.</span></h1>
                            <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula
                                porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl
                                consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
                            <div class="row">
                                <div style="display: flex; justify-content: center;" class="col-12">
                                    <a class="btn btn-primary" href="#" role="button">Ver todos los centros</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                        <img style="object-fit: cover" width="100%" height="500px" src="{{asset('images/school.jpg')}}" alt="">
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
<script src="{{asset('js/app-request.js')}}"></script>
@endsection
