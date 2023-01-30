@extends('layouts.admin-template')
@section('title', 'Promociones')
@section('windowName', 'PROMOCIONES ')


@section('PanelTitle', 'PROMOCIONES')
@section('PanelSubtitle', 'MOSTRAR ELEMENTO')



@section('content')
<div class="container-fluid" id="appPromoShow">        
        <input type="hidden" id="idpromo" value="{{request('id')}}">
        <div class="container">
           <div>
                <promocion v-for="(e,index) in modelo" 
                        :pdata="e" :key="e.id" 
                        @deleted="onDeletedPromo"
                        @on-edit="onEditPromo"/>
           </div>
        </div>

</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ mix('js/admin/app-promociones.js') }}"></script>
@endpush
