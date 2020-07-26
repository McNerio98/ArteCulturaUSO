<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') | Observatorio Cultural</title>
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
</head>

<body>
    @include('layouts.navbar')
    <div id="app" class="container">
        @yield('content')
    </div>
    <script src="{{asset('js/app.js')}}"></script>
</body>

</html>