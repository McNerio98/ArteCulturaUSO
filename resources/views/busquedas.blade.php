@extends('layouts.general-template')
@section('title', 'Inicio')

@section('content')
<script>
    window.params_search = {!! json_encode($parametros) !!};
</script>
<main role="main" class="flex-shrink-0" id="app-search">
    <div class="container bg-white">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <div class="row mb-2">
            <div class="col-12">
                <div class="SectionWelcome">
                    <h1 style="text-align: center; color:rgb(104, 104, 104);; font-size:25px; margin-top: 50px">¿BUSCAS ALGÚN TALENTO/ARTISTA? </h1>            
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8 offset-md-2">
                <form action="simple-results.html">
                    <div class="input-group">
                        <input type="search" class="form-control form-control-lg"
                            placeholder="Ejem. Grupo de música, payasos, casa de la cultura, etc.">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-lg btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row" v-if="loading_page">
            <div class="col-12 text-center">
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>         
                <p style="font-style: italic;color: #0bbb0b;">Realizando búsqueda …</p>
            </div>
         </div>
        <!--Resultados de perfiles-->


         <div class="row">
         <div style="width: 100%;max-width: 600px;margin: auto;">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Artistas / Talentos</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">

                    <profile v-for="e of profiles" :user="e" :path-redirect=" '../perfil/'+e.id"></profile>
                    </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="javascript:void(0)" class="uppercase">Ver todos</a>
                </div>
                <!-- /.card-footer -->
                </div>                
            </div>         
         </div>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/app-search.js') }}"></script>
@endpush
