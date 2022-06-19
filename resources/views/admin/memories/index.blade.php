@extends('layouts.admin-template')
@section('title', 'Reseñas')
@section('windowName', 'HOMENAJES / BIOGRAFÍAS ')


@section('content')
<div class="container-fluid" id="appMemories">        
        <a href="{{route('memories.create.admin')}}">+ Nuevo</a>
        <div class="row mb-2">
                <memory-summary v-for="(e,index) of items" :key="index" :item-data="e">
                </memory-summary>
        </div>
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-memories.js') }}"></script>
@endpush
