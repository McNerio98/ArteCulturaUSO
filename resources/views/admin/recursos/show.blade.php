@extends('layouts.admin-template')
@section('title', 'Detalle del recurso')
@section('windowName', 'Detalle del recurso')

@section('content')
<main role="main" class="flex-shrink-0" id="appResourcesAdminShow">
    <input type="hidden" id="idresource" value="{{request('id')}}">
    <div class="container">
        <resource v-for="(e,index) in modelo" :pdata="e" :key="e.id" 
        @deleted="onDeletedResource"
        @edit="onEditResource"/>        
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/admin/app-resources.js') }}"></script>
@endpush
