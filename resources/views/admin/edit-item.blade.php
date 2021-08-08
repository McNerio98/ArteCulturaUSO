@extends('layouts.admin-template')
@section('title', 'Editar')
@section('windowName', 'EDITAR ELEMENTO')

@section('content')
<div class="container-fluid" id="appUpdateItem">
    <input type="hidden" id="temp_iden_edit" value="{{$id_elem_edit}}">
    <div class="row">
        <div class="col-12">
            <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;background-color: #fff;padding: 10px;border-left: 1px solid #d8d5d5;border-right: 1px solid #d8d5d5;">
                <spinner1 v-if="spinners.S1" label="Cargando elemento …"></spinner1>
                <content-create v-if="buffer.edit_mode" :edit-mode="buffer.edit_mode" :source-edit="buffer.source"></content-create>
            </div>            
        </div>
    </div>

</div>
@endsection

@Push('customScript')
<script src="{{ mix('js/admin/app-item-update.js') }}"></script>
@endpush