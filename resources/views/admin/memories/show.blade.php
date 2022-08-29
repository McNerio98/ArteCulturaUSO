@extends('layouts.admin-template')
@section('title', 'Reseñas')
@section('windowName', 'HOMENAJES / BIOGRAFÍAS ')


@section('content')
<div class="container-fluid" id="appMemoryShow">        
        <input type="hidden" id="idmemory" value="{{request('id')}}">
        <div class="container">
           <div>
                <memory 
                        v-for="(e,index) of modelo"
                        :pdata="e"
                        @deleted="onDeletedMemory"
                        @edit="onEditMemory">
                </memory>
           </div>
        </div>

</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-memories.js') }}"></script>
@endpush
