@extends('layouts.admin-template')
@section('title', 'Promociones')
@section('windowName', 'PROMOCIONES')

@section('PanelTitle', 'PROMOCIONES')
@section('PanelSubtitle', 'REGISTROS')

@section('content')
<div class="container-fluid" id="appPromoIndex">        

        @can('crear-promociones')
        <a href="{{route('promociones.create.admin')}}">+ Nuevo</a>
        @endcan

        <div class="container">
                <div class="row mb-2">
                        <no-records v-if="items.length == 0" icon="box.svg" page="Promociones"></no-records>
                        <promo-summary v-else 
                                v-for="(e,index) in items" 
                                :pdata="e" 
                                @on-read="onRead"
                                @on-preview="onPreview"
                                :key="e.id" ></promo-summary>
                </div>
        </div>

</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-promociones.js') }}"></script>
@endpush
