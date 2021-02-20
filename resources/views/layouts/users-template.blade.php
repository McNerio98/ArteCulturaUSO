<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Observatorio Cultural</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">    
    <link href="{{ asset('css/observatorio_styles.css') }}" rel="stylesheet">       
    @stack('styles')
</head>

<body style="padding-top: 4.5rem; background-color: #f4f6f9 !important;">
    @include('layouts.components.navbar')
    <!--Tag hidden Token-->
    <input type="hidden" value="{{Auth::user()->api_token}}" id="current_save_token_generate" />
    <input type="hidden" value="{{Auth::user()->id}}" id="current_id_user_log"/>
    <!--End Tag Hidden Token-->    
    <div class="content-wrapper" style="margin-left: 0px !important;">
        <section class="content">
            <div class="container">
                @yield('content')
            </div>
        </section> 
    </div>
    <script src="{{asset('js/app.js')}}"></script>
    @stack('customScript')
</body>
</html>