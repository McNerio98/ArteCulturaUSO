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
                  10
                  <small>%</small>
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
                <span class="info-box-number">41,410</span>
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
                <span class="info-box-text">Revision</span>
                <span class="info-box-number">760</span>
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
                <span class="info-box-number">2,000</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <h5 class="mt-4 mb-2">ELEMENTOS DESTACADOS</h5>
        <div class="row">
          <!--HERE ALL CONTENT-->
          <!--///////////////////////////////////////////////////////////////////////////////-->
          <div class="col-md-6">
              <div class="card" style="height: 100%;">
                  <div class="card-body p-0">
                      <ul v-if="popular_post.length > 0" class="products-list product-list-in-card pl-2 pr-2">

                          <post-preview-mini-desing 
                        v-for="e of popular_post" 
                        :key="e.id"
                        v-on:show-panel-info="onShowPanelPostData" 
                        :info-obj="e"></post-preview-mini-desing>

                      </ul>

                      <div v-else class="d-flex flex-column justify-content-center align-items-center text-muted" style="height: 100%;">
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
                  <button v-on:click="changePanel1(panel1_index - 1)"  type="button" class="btn btn-tool">
                    <i class="fas fa-arrow-left"></i> Volver
                  </button>
                </div>                
              </div>
              <div class="card-body"  style="">

              

                <div v-if="panel1_index == 1" class="d-flex flex-column justify-content-center align-items-center " style="height: 100%;">
                  <p class="text-center h5"><b>Selecciona un elemento para pre visualizar su contenido.</b></p>
                  <p class="text-center text-muted"> <i>Los elementos destacados son publicaciones o eventos marcados como relevantes. <span style="color: #e83e8c !important;">Puede crear o buscar eventos o publicaciones en las siguientes opciones.</span></i> </p>
                  <div>
                    <button v-on:click="changePanel1(4)"  type="button" class="btn  d-inline btn-outline-secondary btn-flat"><i class="fas fa-plus"></i> Crear</button>                    
                    <button v-on:click="changePanel1(2)" type="button" class="btn  d-inline btn-outline-secondary btn-flat"><i class="fas fa-search"></i> Buscar</button>
                  
                  
                  
                    <div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Dropdown button
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="#">Action</a>
    <a class="dropdown-item" href="#">Another action</a>
    <a class="dropdown-item" href="#">Something else here</a>
  </div>
</div>
                  
                  </div>                 
                </div>

                <!--Create new post-->
                <div v-if="panel1_index == 4">
                <post-component username=`mcnerio` type="true"></post-component>
                </div>
                <!--End Create new post-->

                <!--Busqueda-->
                <div v-if="panel1_index == 2" class="">
                  <form class="form-inline" v-on:submit.prevent="runFindPostEvent">
                      <div class="input-group input-group-sm" style="width: 100%;">
                          <input v-model="desc_to_search"  class="form-control form-control-navbar" style="background-color: #f2f4f6;" type="search" placeholder="Search" aria-label="Search">
                          <div class="input-group-append">
                              <button class="btn btn-navbar" type="submit" style="background-color: #f2f4f6; border: 1px solid #ced4da; border-left-width: 0;">
                                  <i class="fas fa-search"></i>
                              </button>
                          </div>
                      </div>
                  </form>
                  <!--Resultados de busquedas-->
                  <div v-if="shearch_panel1_state == 2" style="overflow-y: auto; max-height: 550px;" >
                      <ul class="products-list product-list-in-card pl-2 pr-2">

                        <post-preview-mini-desing 
                        v-for="e of result_post_search" 
                        :key="e.id"
                        v-on:show-panel-info="onShowPanelPostData" 
                        :info-obj="e"></post-preview-mini-desing>
                        
                      </ul>                    
                  </div>
                </div>
                <!--Preview de post o evento-->
                <div v-if="panel1_index == 3">
                  <!--options-->
                  <div>
                    <button type="button" class="btn  btn-block" v-bind:class="[node_selected.info.is_popular == true?'btn-primary':'btn-outline-primary']" v-on:click="setPostPopular(node_selected.info.id)"><i class="fa fa-bell"></i> Elemento destacado</button>
                  </div>
                  <!--Contenido-->
                  <div>
                  @{{node_selected.info.title}}
                    <p>@{{node_selected.info.content}}</p>
                  </div>
                </div>
              </div>

            </div>
          </div>


          <!--///////////////////////////////////////////////////////////////////////////////-->
           <!--END HERE ALL CONTENT-->
        </div>
</div>
<!--/. container-fluid -->                
@endsection


@Push('customScript')
    <script src="{{ asset('js/app-admin.js') }}"></script>
@endpush


