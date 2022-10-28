@extends('layouts.admin-template')
@section('title', 'Reseñas')
@section('windowName', 'HOMENAJES / BIOGRAFÍAS ')


@section('content')
<div class="container-fluid" id="appMemoryCreateUpdate">        
        <input type="hidden" id="idmemory" value="{{app('request')->input('idm')}}">
        <div class="container">
           <div>
          
        <memory-create
                v-for="(e,index) of modelo"
                ref="acVmCompMemory"
                :pdata="e"
                @trim-principal-img="openTrimPrincipalPic"
                >
        </memory-create>
           </div>
        </div>

        <control-trim
        ref="acVmCompCropper"
        @base64-generated="principalPicCropped"
        :aspect-ratio="trim_buffer.aspec_ratio">
        </control-trim>
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
        <link href="{{ asset('css/cropper.min.css') }}" rel="stylesheet">
        <script src="{{ asset('js/cropper.min.js') }}"></script>
        <script src="{{ mix('js/admin/app-memories.js') }}"></script>
@endpush
