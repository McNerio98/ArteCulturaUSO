<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Observatorio Cultural</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">    
    <link href="{{ asset('css/observatorio_styles.css') }}" rel="stylesheet">    
    @stack('styles')
</head>
<body style="padding-top: 60px;background: #dddddd;">
    @include('layouts.components.navbar')
    @yield('content')    
    @include('layouts.components.footer')
    <script src="{{asset('js/app.js')}}"></script>
    @stack('customScript')
</body>
</html>