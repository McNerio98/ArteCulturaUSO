@extends('layouts.admin-template')
@section('title', 'Reseñas')
@section('windowName', 'HOMENAJES / BIOGRAFÍAS ')

@section('PanelTitle', 'HOMENAJES / BIOGRAFÍAS ')
@section('PanelSubtitle', 'MOSTRAR')


@section('content')
<div class="container-fluid" id="appMemoryShow">        
        <input type="hidden" id="idmemory" value="{{request('id')}}">
        <div class="container">
           <div>
                <memory 
                        v-for="(e,index) of modelo"
                        :pdata="e"
                        @source-files="onSources" 
                        @deleted="onDeletedMemory"
                        @on-promo="onPromo"
                        @edit="onEditMemory">
                </memory>
           </div>
        </div>
        
        <media-viewer ref="mediaviewer"/>
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-memories.js') }}"></script>
@endpush
