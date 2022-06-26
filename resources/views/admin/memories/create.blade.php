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
                :main-img-change="main_img_buffer.change"
                :main-Img="main_img_buffer.base64"
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.js" integrity="sha512-9pGiHYK23sqK5Zm0oF45sNBAX/JqbZEP7bSDHyt+nT3GddF+VFIcYNqREt0GDpmFVZI3LZ17Zu9nMMc9iktkCw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.css" integrity="sha512-w+u2vZqMNUVngx+0GVZYM21Qm093kAexjueWOv9e9nIeYJb1iEfiHC7Y+VvmP/tviQyA5IR32mwN/5hTEJx6Ng==" crossorigin="anonymous" />

<script src="{{ mix('js/admin/app-memories.js') }}"></script>
@endpush
