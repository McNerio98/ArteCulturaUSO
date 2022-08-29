<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" style="border-bottom: 4px solid #00b05c;">
        <a href="/">
            <img src="{{asset('images/logo.png')}}" width="130px" height="40px" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item ml-1 ml-md-3 mt-2">
                    <a class="" style="color:#20B7EB" href="{{route('inicio')}}"> <i class="fas fa-book"></i>
                        Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item ml-1 ml-md-3 mt-2">
                    <a class="" style="color:#20B7EB" href="{{url('/').'/search?id_filter=0&label=&type_search=all'}}"> <i
                            class="fas fa-guitar"></i> Artistas</a>
                </li>
                <li class="nav-item ml-1 ml-md-3 mt-2">
                    <a class="" style="color:#20B7EB" href="{{url('/').'/search?id_filter=9&label=Promotores&type_search=cat'}}"> <i
                            class="fas fa-compress-arrows-alt"></i> Promotores</a>
                </li>
                <li class="nav-item ml-1 ml-md-3 mt-2">
                    <a class="" style="color:#20B7EB" href="{{url('/').'/search?id_filter=10&label=Escuelas&type_search=cat'}}"> <i
                            class="fas fa-book-open"></i> Escuelas</a>
                </li>
                <li class="nav-item ml-1 ml-md-3 mt-2">
                    <a class="" style="color:#20B7EB" href="{{route('recursos')}}"> <i
                            class="fas fa-box-open"></i> Recursos</a>
                </li>
                <li class="nav-item ml-1 ml-md-3 mt-2">
                    <a class="" style="color:#20B7EB" href="{{route('biografias')}}"> <i
                            class="fas fa-book-reader"></i> Biografías</a>
                </li>

                <!-- <li class="nav-item" style="margin-left:20px">
                    <a class="nav-link" style="color:#20B7EB" href="{{route('homenajes')}}"> <i
                            class="fas fa-hand-holding-heart"></i> Homenajes </a>
                </li> -->

                <li class="nav-item ml-1 ml-md-3 mt-2">
                    <a class="" style="color:#20B7EB" href="{{route('acercade')}}"> <i
                            class="fas fa-exclamation"></i> Acerca de</a>
                </li>

                @auth
                <li class="nav-item" style="margin-left:20px">
                    <div class="dropdown">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            style="color: #20B7EB !important; background-color: transparent !important; border: 0px !important;"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            {{ session()->get('name_cur_user_short') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @hasrole('Invitado')
                                <a class="dropdown-item" href="{{route('profile.show',Auth::user()->id)}}">{{ session()->get('name_cur_user') }}</a>
                            @else
                                <a class="dropdown-item" href="{{route('dashboard')}}">{{ session()->get('name_cur_user') }}</a>
                            @endhasrole
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar
                                Session</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
                @endauth

                @guest
                <li class="nav-item" style="margin-left:20px">
                    <a class="nav-link" style="color:#20B7EB" href="{{route('login')}}"> <i
                            class="fas fa-sign-in-alt"></i> Ingresar</a>
                </li>
                @endguest
            </ul>
        </div>
    </nav>   
</header>