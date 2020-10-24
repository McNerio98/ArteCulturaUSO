<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
        'permissions' => Auth::user()->getPermissionsViaRoles()
    ]) !!};

    window.has_cap = function(cap){
        let status_cap = false;
        if(Laravel.permissions === undefined){
            return false;
        }
        
        for(let val of Laravel.permissions){
            if(val.name === cap){
                return !status_cap;
            }
        }
        return status_cap;
    }
    </script>

    <title>AdminLTE 3 | Dashboard 2</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('customStyles')
    <style>
        .bg-hpolis{background-color: #2c3749 !important;}
        .text-hpolis{color: #49b591 !important;}
        .text-hpolis:hover{
            color: white !important;
        }
    </style>  
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        @include('layouts.components.admin-navbar')
        @include('layouts.components.admin-aside')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Dashboard v2</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard v2</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                  @yield('content')
                </div>
                <!--/. container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            Hello wordl
        </aside>
        <!-- /.control-sidebar -->
        <!-- Main Footer -->
        @include('layouts.components.admin-footer')
    </div>
    <!-- ./wrapper -->
    <script src="{{asset('js/app.js')}}"></script>
    @stack('customScript')  
</body>

</html>
