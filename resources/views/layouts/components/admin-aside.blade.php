<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-2 bg-hpolis">
    <!-- Logo -->
    <a href="{{route('inicio')}}" class="brand-link" style="border-bottom: 1px solid #515151 !important;">
        <!--
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        -->
         <img src="{{asset('images/Logo-brand.png')}}" alt="Observatorio" class="brand-image elevation-2" style="opacity: .8">
            <span class="text-white font-weight-light">Observatorio</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('files/profiles/'.session()->get('media_profile_user'))}}" class="elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{route('user.info', Auth::user()->id)}}" class="d-block text-hpolis">{{ session()->get('name_cur_user') }}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link text-hpolis {{ $ac_option == 'home' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-circle-notch"></i>
                        <p>
                            Panel Principal
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('content')}}" class="nav-link text-hpolis  {{ $ac_option == 'content' ? 'active' : ''}} ">
                        <i class="nav-icon fas fa-photo-video"></i>
                        <p>Mi contenido</p>
                    </a>
                </li>

                <!-- Agregar can cuando ya este-->
                <li class="nav-item">
                    <a href="{{route('promociones.admin')}}" class="nav-link text-hpolis  {{ $ac_option == 'promociones' ? 'active' : ''}} ">
                        <i class="nav-icon fas fa-ad"></i>
                        <p>Promociones</p>
                    </a>
                </li>

                <!-- Agregar can cuando ya este-->
                <li class="nav-item">
                    <a href="{{route('procesos.admin')}}" class="nav-link text-hpolis  {{ $ac_option == 'procesos' ? 'active' : ''}} ">
                        <i class="nav-icon fas fa-server"></i>
                        <p>Procesos</p>
                    </a>
                </li>                

                @can('ver-reseñas')
                <li class="nav-item">
                    <a href="{{route('memories.index.admin')}}" class="nav-link text-hpolis  {{ $ac_option == 'memories' ? 'active' : ''}} ">
                        <i class="nav-icon fas fa-star-half-alt"></i>
                        <p>Homenajes</p>
                    </a>
                </li>
                @endcan

                @can('ver-usuarios')
                <li class="nav-item">
                    <a href="{{route('users')}}" class="nav-link text-hpolis  {{ $ac_option == 'usuarios' ? 'active' : ''}}">
                        <i class="nav-icon  fas fa-users"></i>
                        <p>Usuarios
                            @if(count($request_users) > 0)
                                <span class="right badge badge-danger">
                                {{count($request_users)}}
                                @if(count($request_users) < 2)
                                    Solicitud
                                @else
                                    Solicitudes
                                @endif
                                </span>
                            @endif
                        </p>
                    </a>
                </li>
                @endcan

                @can('ver-rubros')                
                    <li class="nav-item">
                        <a href="{{route('rubros')}}" class="nav-link text-hpolis  {{ $ac_option == 'rubros' ? 'active' : ''}}">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Rubros</p>
                        </a>
                    </li>
                @endcan

                @can('ver-recursos')                
                <li class="nav-item">
                    <a href="{{route('recursos.index.admin')}}" class="nav-link text-hpolis  {{ $ac_option == 'resources' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Recursos</p>
                    </a>
                </li>
                @endcan

                @can('ver-roles')                
                <li class="nav-item">
                    <a href="{{route('roles')}}" class="nav-link text-hpolis  {{ $ac_option == 'roles' ? 'active' : ''}}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Roles y Permisos</p>
                    </a>
                </li>
                @endcan

               

                <li class="nav-item">
                    <a href="#" class="nav-link text-hpolis" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Cerrar Session</p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>                        
                    </a>
                </li>                                                
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>