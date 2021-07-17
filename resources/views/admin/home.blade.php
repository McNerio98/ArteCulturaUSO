@extends('layouts.admin-template')

@section('content')
<div class="container-fluid" id="appHome">
    <!--Tag hidden Token-->
    <input type="hidden" value="{{Auth::user()->api_token}}" id="current_save_token_generate" />
    <!--End Tag Hidden Token-->

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Publicaciones</span>
                    <span class="info-box-number">
                        @{{notifiers_data.posts === -1 ? '' : notifiers_data.posts}}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Eventos</span>
                    <span class="info-box-number">
                        @{{notifiers_data.events === -1 ? '' : notifiers_data.events}}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Solicitudes</span>
                    <span class="info-box-number">
                        @{{notifiers_data.requests === -1 ? '' : notifiers_data.requests}}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Usuarios</span>
                    <span class="info-box-number">
                        @{{notifiers_data.users === -1 ? '' : notifiers_data.users}}
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>


    <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">ELEMENTOS POR APROBAR </div>
                    <div class="card-body">
                        <div class="flex-shrink text-center p-md-3" style="max-width: 42em; margin:auto;" v-if="approval_items.length === 0">
                                <img src="{{asset('images/no-task.svg')}}" alt="" style="width: 80px;">
                            <h2 class="text-success">Ningún elemento que aprobar</h2>
                            <p class="lead">Actualmente ninguna publicación o evento se encuentra esperando tu aprobación</p>
                            <a href="{{route('inicio')}}" class="">Crear contenido para aprobación aquí</a></p>
                        </div>
                        <div>
                            <!--SPINNER LOADER-->
                            <div class="p-md-5 d-flex justify-content-center align-items-center flex-column" v-if="spinner_approval">
                                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>             
                                <h4 style="color: #38c172;">Cargando contenido...</h4>               
                            </div>
                            <!--END SPINNER LOADER-->

                            <div v-if="postevent_selected !== null" class="p-3" style="background-color: #eaecee !important;">
                                <post-general @source-files="onSources" v-bind:model="postevent_selected" @change-popular="setPostPopular"></post-general>                                
                            </div>

                            <span style="border: 1px solid gray;padding: 5px;display: inline-block;margin: 5px;" v-for="appr of approval_items" @click="getApprovalEl(appr.id)">@{{appr.title}}</span>   

                             
                            <nav aria-label="Navegacion elementos en aprobación">
                                <ul class="pagination justify-content-center">
                                    <li v-bind:class="{'disabled' : ! (paginate_approval.current_page > 1)}" class="page-item">
                                        <a @click.prevent="changePage(paginate_approval.current_page - 1)" class="page-link" href="#">Anterior</a>
                                    </li>
                                    <li v-for="page in pagesNumber" v-bind:key="page" v-bind:class="[page == isActive? 'active':'']" class="page-item">
                                        <a @click.prevent="changePage(page)" class="page-link" href="#">@{{page}}</a>
                                    </li>
                                    <li v-bind:class="{'disabled' : ! (this.paginate_approval.current_page < this.paginate_approval.last_page)}" class="page-item">
                                        <a @click.prevent="changePage(paginate_approval.current_page + 1)" class="page-link" href="#">Siguiente</a>
                                    </li>
                                </ul>
                            </nav>                            
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <h5 class="mt-4 mb-2">ELEMENTOS DESTACADOS</h5>

    <div class="row">
        <!--HERE ALL CONTENT-->
        <!--///////////////////////////////////////////////////////////////////////////////-->
        <div class="col-md-6">
            <div class="card" style="height: 100%;">
                <div class="card-body p-0">
                    <ul v-if="popular_post.length > 0" class="products-list product-list-in-card pl-2 pr-2">

                        <post-preview-mini-desing v-for="e of popular_post" :key="e.id"
                            @click.native="ShowPanelPostData(e)" :info-obj="e"></post-preview-mini-desing>

                    </ul>

                    <div v-else class="d-flex flex-column justify-content-center align-items-center text-muted"
                        style="height: 100%;">
                        <span class="h3"><i class="fas fa-folder-open"></i></span>
                        <p>No hay elementos que mostrar</p>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="card" style="height: 100%;">
                <div v-if="panel1_index != 1" class="card-header" style="border-bottom: 0px; padding-bottom: 0px;">
                    <div class="card-tools">
                        <button v-on:click="changePanel1(panel1_index - 1)" type="button" class="btn btn-tool">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="panel1_index == 1" class="d-flex flex-column justify-content-center align-items-center "
                        style="height: 100%;">
                        <p class="text-center h5"><b>Selecciona un elemento para pre visualizar su contenido.</b></p>
                        <p class="text-center text-muted"> <i>Los elementos destacados son publicaciones o eventos
                                marcados
                                como relevantes. <span style="color: #e83e8c !important;">Puede crear o buscar eventos o
                                    publicaciones en las siguientes opciones.</span></i> </p>
                        <div>
                            <div class="dropdown d-inline">
                                <button class="btn  btn-outline-secondary btn-flat" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fas fa-plus"></i> Crear
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" v-on:click.prevent="createNewPostEvent('post',4)"
                                        href="#">Publicación</a>
                                    <a class="dropdown-item" v-on:click.prevent="createNewPostEvent('event',4)"
                                        href="#">Evento</a>
                                </div>
                            </div>
                            <button v-on:click="changePanel1(2)" type="button"
                                class="btn  d-inline btn-outline-secondary btn-flat"><i class="fas fa-search"></i>
                                Buscar</button>
                        </div>

                    </div>

                    <!--Create new post-->
                    <div v-if="panel1_index == 4">
                        <post-event :post-type="post_to_create" @post-created="loadPostById"></post-event>
                    </div>
                    <!--End Create new post-->

                    <!--Busqueda-->
                    <div v-if="panel1_index == 2" class="">
                        <form class="form-inline" v-on:submit.prevent="runFindPostEvent">
                            <div class="input-group input-group-sm" style="width: 100%;">
                                <input v-model="desc_to_search" class="form-control form-control-navbar"
                                    style="background-color: #f2f4f6;" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit"
                                        style="background-color: #f2f4f6; border: 1px solid #ced4da; border-left-width: 0;">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!--Resultados de busquedas-->
                        <div v-if="shearch_panel1_state == 2" style="overflow-y: auto; max-height: 550px;">
                            <ul class="products-list product-list-in-card pl-2 pr-2">

                                <post-preview-mini-desing v-for="e of result_post_search" :key="e.id"
                                    @click.native="ShowPanelPostData(e)" :info-obj="e"></post-preview-mini-desing>
                            </ul>
                        </div>
                    </div>
                    <!--Preview de post o evento-->
                    <div v-if="panel1_index == 3">
                        <post-general v-bind:model="post_selected" @change-popular="setPostPopular"></post-general>
                    </div>
                </div>

            </div>
        </div>

        <media-viewer 
        :media-profile="false"  
        :target="media_view.target"
        :logged.number='{{Auth::user() == null ? 0 : Auth::user()->id}}'
        :owner="media_view.owner"
        :items="media_view.items">
        </media-viewer>    

        <!--///////////////////////////////////////////////////////////////////////////////-->
        <!--END HERE ALL CONTENT-->
    </div>
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
    <script src="{{ asset('js/app-admin.js') }}"></script>
@endpush


