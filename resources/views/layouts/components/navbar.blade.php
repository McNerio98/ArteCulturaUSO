<div class="fixed-top">
  <nav class="navbar   navbar-expand-lg navbar-dark" style="background-color: #31343E;">
  <a href="/">
    <img src="{{asset('images/logo.png')}}" width="130px" height="40px" alt="">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse " id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
                    <li class="nav-item active" style="margin-left:20px">
                        <a class="nav-link" style="color:#20B7EB" href="#"> <i class="fas fa-book"></i> Inicio <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item" style="margin-left:20px">
                        <a class="nav-link" style="color:#20B7EB" href="#"> <i class="fas fa-guitar"></i> Artistas</a>
                    </li>
                    <li class="nav-item" style="margin-left:20px">
                        <a class="nav-link" style="color:#20B7EB" href="#"> <i class="fas fa-compress-arrows-alt"></i> Promotores</a>
                    </li>
                    <li class="nav-item" style="margin-left:20px">
                        <a class="nav-link" style="color:#20B7EB" href="#"> <i class="fas fa-book-open"></i> Escuelas</a>
                    </li>
                    <li class="nav-item" style="margin-left:20px">
                        <a class="nav-link" style="color:#20B7EB" href="#"> <i class="fas fa-box-open"></i> Recursos</a>
                    </li>
                    <li class="nav-item" style="margin-left:20px">
                        <a class="nav-link" style="color:#20B7EB" href="#"> <i class="fas fa-exclamation"></i> Acerca de</a>
                    </li>
                    @auth
                    <li class="nav-item" style="margin-left:20px">



                    <div class="dropdown">
                      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                      style="color: #20B7EB !important; background-color: transparent !important; border: 0px !important;" 
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-user"></i> 
                      MCNerio
                      </a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="#">Mario Nerio</a>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Session</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>                         
                      </div>
                    </div>
                    </li>
                    @endauth

                    @guest
                    <li class="nav-item" style="margin-left:20px">
                        <a class="nav-link" style="color:#20B7EB" href="{{route('login')}}"> <i class="fas fa-sign-in-alt"></i> Ingresar</a>
                    </li>                    
                    @endguest
    </ul>
  </div>
</nav>
<div style="width: 100%; height:8px; background:#20B7EB; clear:both;"></div>
</div>