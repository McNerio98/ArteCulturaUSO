@Push('styles')
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/post/post.css') }}" rel="stylesheet">
<link href="{{ asset('css/post/media.css') }}" rel="stylesheet">
@endpush
@extends('layouts.public-template')

@section('content')
<br/>
<post-component></post-component>
@endsection
