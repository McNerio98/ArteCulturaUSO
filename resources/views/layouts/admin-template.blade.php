<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Dashboard</title>
    <link rel="icon" type="image/x-icon" href="{{asset('images/icono.ico')}}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/observatorio_styles.css') }}" rel="stylesheet">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
                ],
                "params" => [
                    "NEARBY_ENABLE" => AcHelper::getOption('NEARBY_ENABLE','D'),
                    "FILE_SIZE" => AcHelper::getOption('FILE_SIZE','20'),
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
                ],
                "params" => [
                    "NEARBY_ENABLE" => AcHelper::getOption('NEARBY_ENABLE','D'),
                    "FILE_SIZE" => AcHelper::getOption('FILE_SIZE','20'),
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

        window.get_param = function(param_key , defvalue){
            let param_value = null;
            if(window.obj_ac_app.params == undefined){
                return defvalue;
            }    

            return obj_ac_app.params[param_key] != undefined ? obj_ac_app.params[param_key] : defvalue;
        }


    </script>     
    @stack('customStyles')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed dark-mode">
    <div class="wrapper">
        @include('layouts.components.admin-navbar')
        @include('layouts.components.admin-aside')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">@yield('windowName')</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">@yield('PanelTitle')</a></li>
                                <li class="breadcrumb-item active">@yield('PanelSubtitle')</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
            <input type="hidden" value="{{url('/')}}" id="url_server" name="url">
                  @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('layouts.components.admin-footer')
    </div>
    <!-- ./wrapper -->
    <script src="{{asset('js/app.js')}}"></script>
    @stack('customScript')  
</body>

</html>
