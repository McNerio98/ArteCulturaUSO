<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-danger navbar-badge">{{count($request_users)}}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                @foreach($request_users as $e)
                    <a href="{{route('user.info',$e->id)}}" class="dropdown-item">
                        <div class="media">
                            <img src="{{asset('files/profiles/'.$e->profile_img->path_file)}}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    {{$e->name}}
                                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">{{$e->email}}</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i>{{AcHelper::getDatetimeInterval($e->created_at)}}</p>
                            </div>
                        </div>
                    </a>                    
                    <div class="dropdown-divider"></div>
                @endforeach

                <div class="dropdown-divider"></div>
                <!--Aqui se debe mandar un filtro al panel usuario, filtro aun no creado-->
                <a href="{{url('/admin/users').'?filter=request'}}" class="dropdown-item dropdown-footer">Todas las solicitudes</a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
