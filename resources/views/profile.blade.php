@extends('layouts.users-template')
@section('title', 'Inicio')
@Push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .profile-userimg-hover{
            cursor: pointer;
            border-radius: 50%;
            width: 100px;
            max-width: 100%;
            height: 100px;
            color: transparent;
            background-color: red;
            margin: 0 auto;
        }

        .profile-pic {
            height: 100px !important;
            width: 100px !important;
            background-size: cover;
            background-position: center;
            background-blend-mode: multiply;
            color: transparent;
            transition: all .3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
         }

         .profile-pic:hover{
             cursor: pointer;
            background-color: rgba(0,0,0,.5);
            z-index: 10000;
            color: rgba(250,250,250,1);
            transition: all .3s ease;
         }

    </style>
@endpush
    
<link href="{{ asset('css/welcome.css') }}" rel="stylesheet">


@section('content')
<div class="row" id="appProfile">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">

                <div class="text-center">

                    <div style="background-image: url('{{asset('content/profiles_images/default_img_profile.png')}}')" class="profile-pic profile-user-img img-fluid img-circle">
                        <i class="fas fa-camera"></i>
                    </div>



                </div>

                <h3 class="profile-username text-center">Sinfonia Sonsonateca</h3>
                <p class="text-muted text-center">Musica,Grupos de música</p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Publicaciones</b> <a class="float-right">0</a>
                    </li>
                    <li class="list-group-item">
                        <b>Proximos Eventos</b> <a class="float-right">0</a>
                    </li>
                </ul>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Acerca de</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Otros Nombres</strong> <i class="fas fa-pen ac-edit-about"
                    data-toggle="tooltip" data-placement="top" title="Editar"></i>
                <p class="text-muted">
                    Sinsoteca Band
                </p>
                <hr>
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong> <i
                    class="fas fa-pen ac-edit-about"></i>
                <p class="text-muted">Col 14, Sonsonate.</p>
                <hr>
                <strong><i class="fas fa-pencil-alt mr-1"></i> Correo Contacto</strong> <i
                    class="fas fa-pen ac-edit-about"></i>
                <p class="text-muted">sinsoteca.band50@music.sv</p>
                <hr>
                <strong><i class="fas fa-pencil-alt mr-1"></i> Numero Contacto</strong> <i
                    class="fas fa-pen ac-edit-about"></i>
                <p class="text-muted">+ 503 7058-7814 / +503 2450-4789</p>
                <hr>
                <strong><i class="far fa-file-alt mr-1"></i> Notas</strong> <i class="fas fa-pen ac-edit-about"></i>
                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                    fermentum enim neque.</p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active rd-none" href="#biografia"
                            data-toggle="tab">Contenido</a></li>
                    <li class="nav-item"><a class="nav-link rd-none" href="#timeline" data-toggle="tab">Eventos</a>
                    </li>
                    <li class="nav-item"><a class="nav-link rd-none" href="#settings"
                            data-toggle="tab">Publicaciones</a>
                    </li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="biografia">
                        <div class="post">
                                <a href="">Here for edit</a>
                        </div>
                        <!-- Post -->
                        <div class="post">
                            <h4 class="text-primary mb-4">Fotografias destacadas</h4>
                        </div>
                        <!-- /.post -->
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">
                        <post-component eventType="true"></post-component>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="settings">
                        <post-component eventType="false"></post-component>
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>

@endsection


@Push('customScript')

    <script>
        var globalTokenApi = '{{$current_user->api_token}}';
    </script> 
    <script src="{{asset('js/app-profile.js')}}"></script> 
@endpush
