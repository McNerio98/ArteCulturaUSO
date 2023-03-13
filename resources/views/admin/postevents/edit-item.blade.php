@extends('layouts.admin-template')
@section('title', 'Editar')
@section('windowName', 'EDITAR ELEMENTO')


@section('PanelTitle', 'EVENTOS')
@section('PanelSubtitle', 'EDITAR')


@section('content')
<div class="container-fluid" id="appAdminUpdatePost">
    <input type="hidden" id="temp_iden_edit" value="{{$id_elem_edit}}">
    <div class="row">
        <div class="col-12">
            <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;background-color: #fff;padding: 10px;border-left: 1px solid #d8d5d5;border-right: 1px solid #d8d5d5;">
                <spinner1 v-if="isLoading" label="Cargando elemento …"></spinner1>
                <postevent-create v-for="e of modelo_create" 
                        :pdata="e"
                        :key="'id' + (new Date()).getTime()"
                        v-if="!isLoading"
                        @saved="postEventCreated">
                </postevent-create>

            </div>            
        </div>
    </div>

</div>
@endsection

@Push('customScript')
<script src="{{ mix('js/admin/app-item-update.js') }}"></script>
@endpush