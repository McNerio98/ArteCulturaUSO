<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
        <!-- Fonts -->
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
          <h1>Aristas, talentos, músicos, centros de enseñanzas</h2>
          <h5>Sonsonate tiene mucho talento que los puedes encontrar e  <a href="{{route('inicio')}}">nuestro sitio</a></h5>        
    </div>
  </div>  
  <div class="_logPanel2" style="background-image: url({{asset('images/bground1.jpg')}});">
    <div class="_wrappenPnl">
      <div class="_acCntBrand">
          <img src="{{asset('images/logo.png')}}" alt="">
      </div>
      <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Iniciar sesión</p>      
            <form method="POST" action="{{ route('login') }}">
            @csrf
              <div class="input-group mb-3">
                <input name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Nombre de Usuario" required autocomplete="username" autofocus>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
              </div>  
              @error('username')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror                
              <div class="input-group mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror               
              <div class="row">
                  <div class="col-12">
                      <button type="submit" class="btn btn-primary btn-block">Iniciar</button>                    
                  </div>
                  <div class="col-12">
                    <p class="mt-2 mt-md-3">¿No tiene una cuenta? <a href="{{url('/').'?register=true'}}">Cree una</a></p>
                  </div>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>    
</body>
</html>
