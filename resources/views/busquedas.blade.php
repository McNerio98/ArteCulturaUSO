@extends('layouts.general-template')
@section('title', 'Inicio')

@section('content')
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
            <div class="col-12">
                @if($filter_search == null)
                    <search-component @generated-filter="exeSeach"></search-component>
                @else
                    <search-component @generated-filter="exeSeach" :prev-search="{{json_encode($filter_search)}}"></search-component>
                @endif                
            </div>
        </div>

        <div class="row" v-if="spinners.S1">
            <div class="col-12">
                    <spinner1 label="Cargando perfiles …"></spinner1>
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
                    <!-- <a href="javascript:void(0)" class="uppercase">Ver todos</a> -->
                    <!--PAGINATION-->
                    <ul class="pagination justify-content-center">
                        <li v-bind:class="{'disabled' : ! (pagination1.current_page > 1)}" class="page-item">
                            <a @click.prevent="changePage(pagination1.current_page - 1)" class="page-link"
                                href="javascript.void(0);">Anterior</a>
                        </li>
                        <li v-for="page in pagesNumber1" v-bind:key="page" v-bind:class="[page == isActive1? 'active':'']"
                            class="page-item">
                            <a @click.prevent="changePage(page)" class="page-link" href="javascript:void(0);">@{{page}}</a>
                        </li>
                        <li v-bind:class="{'disabled' : ! (this.pagination1.current_page < this.pagination1.last_page)}" class="page-item">
                            <a @click.prevent="changePage(pagination1.current_page + 1)" class="page-link"
                                href="javascript:void(0);">Siguiente</a>
                        </li>
                    </ul>                    
                </div>
                <!--END PAGINATION-->
                </div>               
                <!-- /.card-footer --> 
            </div>         
         </div>
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/app-search.js') }}"></script>
@endpush
