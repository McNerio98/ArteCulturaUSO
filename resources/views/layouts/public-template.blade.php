<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') | Observatorio Cultural</title>
    @stack('styles')
    <style>
        .backGround{
            background: #dddddd;
        }
    </style>
</head>

<body class="backGround" style="padding-top: 60px;">
    @include('layouts.components.navbar')
    <div id="app" class="container-fluid">
        @yield('content')
    </div>
    @include('layouts.components.footer')
    <script src="{{asset('js/app.js')}}"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>