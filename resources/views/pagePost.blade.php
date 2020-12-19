@Push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/post/post.css') }}" rel="stylesheet">
<link href="{{ asset('css/post/media.css') }}" rel="stylesheet">
@endpush
@extends('layouts.public-template')

@section('content')
<br/>
<div id="post">
    <post-component type="true"></post-component>
</div>
<script src="{{asset('js/app-post.js')}}"></script>
@endsection
