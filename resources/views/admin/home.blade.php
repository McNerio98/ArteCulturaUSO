@extends('layouts.admin-template')
@section('title', 'Inicio')
@section('windowName', 'PANEL PRINCIPAL')

@section('content')
<div class="container" id="appHome">
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

    <!--HERE ALL CONTENT-->
    <!--///////////////////////////////////////////////////////////////////////////////-->
    <h5 id="ancla-title1">Agregados recientemente</h5>

    <!--NO CONTENT-->
    <div class="flex-shrink text-center p-md-3" style="max-width: 42em; margin:auto;" v-if="recientes.length < 1">
        <img src="{{asset('images/no-task.svg')}}" alt="" style="width: 80px;">
        <h2 class="text-success">Ningún elemento agregado</h2>
        <p class="lead">Actualmente ningún evento se ha creado</p>
        <a href="{{route('content')}}" class="">Crear contenido</a></p>
    </div>
    <!--END NO CONTENT-->

    <!--SPINNER LOADER-->
    <div class="p-md-5 d-flex justify-content-center align-items-center flex-column" v-if="isLoading">
        <div class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <h4 style="color: #38c172;">Cargando contenido...</h4>
    </div>
    <!--END SPINNER LOADER-->
    <div class="mt-3" v-else>
            <div class="row">
                <postevent-card 
                v-for="(e) in recientes" 
                :pdata="e"
                @selected="onSelected"></postevent-card>
            </div>
    </div>

    <!--END HERE ALL CONTENT-->        
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
    <script src="{{ mix('js/admin/app-admin.js') }}"></script>
@endpush


