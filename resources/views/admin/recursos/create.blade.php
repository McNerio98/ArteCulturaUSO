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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.js" integrity="sha512-9pGiHYK23sqK5Zm0oF45sNBAX/JqbZEP7bSDHyt+nT3GddF+VFIcYNqREt0GDpmFVZI3LZ17Zu9nMMc9iktkCw==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.css" integrity="sha512-w+u2vZqMNUVngx+0GVZYM21Qm093kAexjueWOv9e9nIeYJb1iEfiHC7Y+VvmP/tviQyA5IR32mwN/5hTEJx6Ng==" crossorigin="anonymous" />
    <script src="{{ asset('js/admin/app-resources.js') }}"></script>
@endpush