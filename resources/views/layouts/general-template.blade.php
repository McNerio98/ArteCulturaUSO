<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Observatorio Cultural</title>
    <script>
        
        @auth //Usuario logeado
            window.obj_ac_app = {!! json_encode([
                'csrfToken' => csrf_token(), // token 
                'permissions' => Auth::user()->caps, 
                'base_url' => url('/'), //URL BASE 
                'storage_url' => url('/'), //URL BASE 
                'full_url' => url()->full(),
                'current_url' => url()->current(),
                "current_user" => [
                    "id" => Auth::user()->id,
                    "nickname" => Auth::user()->artistic_name,
                    "fullname" => Auth::user()->name,
                    "email" => Auth::user()->email,
                    "presentation_img" => [
                        "id" => Auth::user()->profile_img->id,
                        "name" =>Auth::user()->profile_img->path_file
                    ]
                ]
            ]) !!};
        @endauth

        @guest //Usuario no logeado
            window.obj_ac_app = {!! json_encode([
                'csrfToken' => csrf_token(), // token 
                'permissions' => null,
                'base_url' => url('/'), //URL BASE 
                'storage_url' => url('/'), //URL BASE 
                'full_url' => url()->full(),
                'current_url' => url()->current(),
                "current_user" => [
                    "id" => null,
                    "nickname" => null,
                    "fullname" => null,
                    "email" => null,
                    "presentation_img" => [
                        "id" => null,
                        "name" => null
                    ]
                ]
            ]) !!};        
        @endguest
        

    window.has_cap = function(cap){
        let status_cap = false;
        if(window.obj_ac_app.permissions == undefined){
            return false;
        }
        
        for(let val of window.obj_ac_app.permissions){
            if(val === cap){
                return !status_cap;
            }
        }
        return status_cap;
    }
    </script>    
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">    
    <link href="{{ asset(mix('css/observatorio_styles.css')) }}" rel="stylesheet">
    <link href="{{ asset('css/general_styles.css')}}" rel="stylesheet">    
    @stack('styles')
</head>
<body class="d-flex flex-column h-100  wrapper dark-mode">
    @include('layouts.components.navbar')
    <!-- <main role="main" class="flex-shrink-0"> -->
        @yield('content')
    <!-- </main> -->
    <div class="mb-md-3"></div>
    @include('layouts.components.footer')
    <script src="{{ asset(mix('js/app.js'))}}"></script>
    @stack('customScript')
</body>

</html>