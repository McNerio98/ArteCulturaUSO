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

<body style="padding-top: 4.5rem; background-color: #f4f6f9 !important;">
    @include('layouts.components.navbar')
    <div class="content-wrapper" style="margin-left: 0px !important;">
        <section class="content">
            <div class="container">
                @yield('content')
            </div>
        </section> 
    </div>
    <script src="{{asset('js/app.js')}}"></script>
    
</body>
</html>