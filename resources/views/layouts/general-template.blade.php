<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Observatorio Cultural</title>
    <script>
    window.obj_ac_app = {!! json_encode([
        'csrfToken' => csrf_token(), // token 
        'permissions' => Auth::user() == null?null:Auth::user()->getPermissionsViaRoles(), //Los permisos del usuario actual 
        'base_url' => url('/'), //URL BASE 
        'full_url' => url()->full(),
        'current_url' => url()->current()
    ]) !!};

    window.has_cap = function(cap){
        let status_cap = false;
        if(window.obj_ac_app.permissions == undefined){
            return false;
        }
        
        for(let val of window.obj_ac_app.permissions){
            if(val.name === cap){
                return !status_cap;
            }
        }
        return status_cap;
    }
    </script>    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">    
    <link href="{{ asset('css/observatorio_styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general_styles.css')}}" rel="stylesheet">    
    @stack('styles')
</head>
<body class="d-flex flex-column h-100">
    @include('layouts.components.navbar')
    <!-- <main role="main" class="flex-shrink-0"> -->
        @yield('content')
    <!-- </main> -->
    <div class="mb-md-3"></div>
    @include('layouts.components.footer')
    <script src="{{asset('js/app.js')}}"></script>
    @stack('customScript')
</body>

</html>