@extends('layouts.admin-template')
@section('title', 'Crear recurso')
@section('windowName', 'Creacion de nuevo recurso')

@section('content')
<main role="main" class="flex-shrink-0" id="appResourcesAdminCreate">
<input type="hidden" id="idresource" value="{{app('request')->input('idr')}}">
    <div class="container">
        <resource-create
            v-for="e in modelo"
            :pdata="e"
            ref="acVmCompResource"
            @trim-principal-img="openTrimPrincipalPic"
            @on-created="onCreateResource"
        />
    </div>
    <control-trim
        ref="acVmCompCropper"
        @base64-generated="principalPicCropped"
        :aspect-ratio="trim_buffer.aspec_ratio">
        </control-trim>    
</main>
@endsection

@Push('customScript')
    <link href="{{ asset('css/cropper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/cropper.min.js') }}"></script>
    <script src="{{ asset('js/admin/app-resources.js') }}"></script>
@endpush