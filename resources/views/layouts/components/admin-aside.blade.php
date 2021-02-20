<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-2 bg-hpolis">
    <!-- Logo -->
    <a href="index3.html" class="brand-link" style="border-bottom: 1px solid #515151 !important;">
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
                <!--
                    <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                -->
            </div>
            <div class="info">
                <a href="#" class="d-block text-hpolis">Alexander Pierce</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link text-hpolis">
                        <i class="nav-icon fas fa-columns"></i>
                        <p>
                            Panel Principal
                        </p>
                    </a>
                </li>

                <!--Falta ponerle el can-->
                <li class="nav-item">
                    <a href="{{route('tags')}}" class="nav-link text-hpolis">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Homenajes</p>
                    </a>
                </li>

                <!--Falta ponerle el can-->
                <li class="nav-item">
                    <a href="{{route('tags')}}" class="nav-link text-hpolis">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Mi Contenido</p>
                    </a>
                </li>

                @can('ver-usuarios')
                <li class="nav-item">
                    <a href="{{route('users')}}" class="nav-link text-hpolis">
                        <i class="nav-icon  fas fa-users"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
                @endcan

                @can('ver-categorias')                
                <li class="nav-item">
                    <a href="{{route('tags')}}" class="nav-link text-hpolis">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Categorias</p>
                    </a>
                </li>
                @endcan

                <li class="nav-item">
                    <a href="{{route('rubros')}}" class="nav-link text-hpolis">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Rubros</p>
                    </a>
                </li>

                <!--Falta ponerle el can-->
                <li class="nav-item">
                    <a href="{{route('tags')}}" class="nav-link text-hpolis">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Roles y Permisos</p>
                    </a>
                </li>

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