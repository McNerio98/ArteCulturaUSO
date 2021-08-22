@extends('layouts.general-template')
@section('title', 'Acerca de')
<main role="main" class="flex-shrink-0" id="app-about">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-7 col-lg-6">
                <div class="_acPlaqueAbout">
                    <img src="{{asset('images/plaque.png')}}" alt="">
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-6">
                    <h1 style="color: #20c997;">Acerca de</h1>
                    <h3>Nuestro sitio web surge de la necesidad de compartir el talento y la cultura de nuestro departamento, facilitando el acercamiento entre la población general con todos los artistas de todos los rubros, así como las actividades que estos realizan en determinadas fechas, creemos en el valor de las personas que comienzan a incurrir en el mundo artístico, así como las personas que desde hace mucho fabrican sueños e inspiraciones para otras personas.</h3>
            </div>            
        </div>
    </div>
</main>
@section('content')
@endsection