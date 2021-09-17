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
                    <h2 style="color: #20c997;">Acerca de</h2>
                    <h4 class="text-justify">Observatorio Cultural nace de la importancia que tiene la cultura en el desarrollo integral del hombre, y del compromiso social de la universidad de Sonsonate por la cultura y el arte. En la actualidad, existen muchas expresiones artísticas y culturales en el departamento de Sonsonate (pintura, teatro, música, danza, etc.); así como también diversos eventos de esta naturaleza, y que por una u otra razón la población los desconoce.</h4>
                    <br>
                    <h4>El objeto de este sitio es aglomerar todo tipo de información cultural y artística de manera que: </h4>
                    <h5 class="text-justify">1) Se difunda la cultura y las artes (Desarrollo cultural).</h5>
                    <h5 class="text-justify">2) Se proyecte a los artistas sonsonatecos y de su obra (Desarrollo humano).</h5>
                    <h5 class="text-justify">3) Se vinculen servicios artísticos culturales (Desarrollo económico).</h5>
                    <h5 class="text-justify">4) Se promocionen los eventos artísticos y culturales de los municipios (Desarrollo turístico).</h5>
            </div>            
        </div>
    </div>
</main>
@section('content')
@endsection