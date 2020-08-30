@Push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
@endpush
    
<link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
@extends('layouts.public-template')
@section('title', 'Inicio')
@section('content')
                    <a href="{{ route('logout') }}" class="nav-link text-hpolis" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-guitar"></i>
                        <p>Cerrar Session</p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>                        
                    </a>
@endsection