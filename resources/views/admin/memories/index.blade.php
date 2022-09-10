@extends('layouts.admin-template')
@section('title', 'Reseñas')
@section('windowName', 'HOMENAJES / BIOGRAFÍAS ')


@section('content')
<div class="container-fluid" id="appMemories">        
        @can('crear-reseñas')
        <a href="{{route('memories.create.admin')}}">+ Nuevo</a>
        @endcan
        <div class="row mb-2">
                <no-records v-if="items.length == 0" icon="box.svg" page="Biografías/Homenajes"></no-records>
                <memory-summary v-else v-for="(e,index) in items" :key="index" :pdata="e" @on-read="onReadMemory">
        </div>
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-memories.js') }}"></script>
@endpush
