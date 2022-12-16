@extends('layouts.admin-template')
@section('title', 'Promociones')
@section('windowName', 'PROMOCIONES')

@section('PanelTitle', 'PROMOCIONES')
@section('PanelSubtitle', 'CREAR')


@section('content')
<div class="container-fluid" id="appPromoCreateUpdate">        
        <input type="hidden" id="idmemory" value="{{app('request')->input('idm')}}">
        <input type="hidden" id="tarid" value="{{app('request')->input('tarid')}}">
        <input type="hidden" id="tartype" value="{{app('request')->input('tartype')}}">
        <div class="container">
           <div>
                <promo-create
                        v-for="(e,index) in modelo"
                        :pdata="e"
                        :key="index"
                        @on-created="onCreatePromo">
                </promo-create>
           </div>
        </div>
</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-promociones.js') }}"></script>
@endpush
