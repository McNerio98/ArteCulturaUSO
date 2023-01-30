@extends('layouts.admin-template')
@section('title', 'Promociones')
@section('windowName', 'PROMOCIONES')

@section('PanelTitle', 'PROMOCIONES')
@section('PanelSubtitle', 'REGISTROS')

@section('content')
<div class="container-fluid" id="appPromoIndex">        
        <div class="container">
                @can('crear-promociones')
                <a class="btn  bg-gradient-success mb-3" href="{{route('promociones.create.admin')}}">
                        <i class="fas fa-plus"></i>
                        Crear nueva Promoción
                </a>
                @endcan
                <div class="row mb-2" v-if="!isGettingResources">
                        <no-records v-if="items.length == 0" icon="box.svg" page="Promociones"></no-records>
                        <promo-summary v-else 
                                v-for="(e,index) in items" 
                                :pdata="e" 
                                @on-read="onRead"
                                @on-preview="onPreview"
                                :key="e.id" ></promo-summary>
                </div>
                <div class="row" v-else>
                        <div class="col-12">
                                <spinner1 label="Cargando promociones …"></spinner1>
                        </div>
                </div>                        
        </div>

</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-promociones.js') }}"></script>
@endpush
