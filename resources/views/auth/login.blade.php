<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
        <!-- Fonts -->
    <link rel="icon" type="image/x-icon" href="{{asset('images/icono.ico')}}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/observatorio_styles.css') }}" rel="stylesheet">
</head>

<body class="_acScreenLogin">
  <div class="_logPanel1">
    <div class="_wrpBrand">
        <img src="{{asset('images/Banner1.1.png')}}" alt="">
          <h1>Sonsonate tiene mucho que ofrecer cultural y artísticamente</h2>
          <h5>Artistas, Grupos, Promotores, Academias, Sitios, Fiestas, Actividades, conoce más en <a href="{{route('inicio')}}">nuestro sitio</a></h5>        
    </div>
  </div>  
  <div class="_logPanel2" style="background-image: url({{asset('images/bground1.jpg')}});" id="appLogin">
    <div class="_wrappenPnl">
      <div class="_acCntBrand">
          <img src="{{asset('images/logo.png')}}" alt="">
      </div>
      <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Iniciar sesión</p>      
            <p>¿No tiene una cuenta? <a href="{{url('/').'?register=true'}}">Cree una</a></p>
            <form method="POST" action="{{ route('login') }}" @submit="onSubmit">
            @csrf
              <div class="input-group mb-3">
                <input name="username" type="text" class="form-control @error('no_match') is-invalid @enderror" value="{{ old('username') }}" placeholder="Nombre de Usuario" required autocomplete="username" autofocus>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
              </div>              
              <div class="input-group mb-3">
                <input type="password" class="form-control @error('no_match') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              @error('no_match')
                <span class="invalid-feedback d-block m-0 mb-3" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror               
              <div class="row">
                  <div class="col-12">
                      <button type="submit" 
                      :disabled="isSendData"
                      class="btn btn-primary btn-block">
                      <i v-if="!isSendData" class="fas fa-sign-in-alt"></i>
                      <span v-if="isSendData" class="spinner-border spinner-border-sm"  role="status" aria-hidden="true"></span>
                          <template v-if="!isSendData">Iniciar</template>
                          <template v-else>Verificando …</template>                        
                      </button> 
                      <a :disabled="isSendData" class="btn btn-success btn-block" href="{{route('inicio')}}">
                        <i class="fas fa-home"></i>
                        Regresa al Inicio
                      </a>                
                  </div>
                  <div class="col-12">
                    <p class="mt-2 mt-md-3"><a href="{{url('/soporte')}}">Solicitar recuperación de contraseña</a></p>
                  </div>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>    
  
  <script src="{{ asset('js/front/app-login.js')}}" defer></script>
</body>
</html>
