@extends('layouts.admin-template')
@section('title', 'Inicio')
@section('windowName', 'PANEL PRINCIPAL')

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

    <!--HERE ALL CONTENT-->
    <!--///////////////////////////////////////////////////////////////////////////////-->
    <h5>ELEMENTOS POR APROBAR</h5>

    <!--NO CONTENT-->
    <div class="flex-shrink text-center p-md-3" style="max-width: 42em; margin:auto;" v-if="approval_items.length === 0 && !spinners.S1">
        <img src="{{asset('images/no-task.svg')}}" alt="" style="width: 80px;">
        <h2 class="text-success">Ningún elemento que aprobar</h2>
        <p class="lead">Actualmente ninguna publicación o evento se encuentra esperando tu aprobación</p>
        <a href="{{route('inicio')}}" class="">Crear contenido para aprobación aquí</a></p>
    </div>
    <!--END NO CONTENT-->

    <!--SPINNER LOADER-->
    <div class="p-md-5 d-flex justify-content-center align-items-center flex-column" v-if="spinners.S">
        <div class="lds-ellipsis">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <h4 style="color: #38c172;">Cargando contenido...</h4>
    </div>
    <!--END SPINNER LOADER-->
    <div  v-if="postevent_selected !== undefined">
        <post-general @source-files="onSources" v-bind:model="postevent_selected"></post-general>                                
    </div>

    <div class="row">
        <summary-item @selected-item="getApprovalEl" v-for="app of approval_items" :model="app"></summary-item>        
    </div>

    <!--PAGINATION-->
    <div>
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
    <!--END PAGINATION-->

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
<!--/. container-fluid -->               
@endsection


@Push('customScript')
    <script src="{{ mix('js/admin/app-admin.js') }}"></script>
@endpush


