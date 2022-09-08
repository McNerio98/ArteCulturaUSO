@extends('layouts.admin-template')
@section('title', 'Mostrar')
@section('windowName', 'MOSTRAR ELEMENTO')

@section('content')
<div class="container-fluid" id="appAdminShowPost">
    <input type="hidden" id="temp_iden_edit" value="{{$id_elem_edit}}">

    <div class="row">
        <div class="col-12">
            <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;background-color: #fff;padding: 10px;border-left: 1px solid #d8d5d5;border-right: 1px solid #d8d5d5;">
                <spinner1 v-if="isLoading" label="Cargando elemento …"></spinner1>
                <postevent-show
                    v-for="(e,index) of items_postevents"  
                    @edit-item="onUpdatePostEvent" 
                    @delete-item="onDeletePost" 
                    @source-files="onSources" 
                    :key="'pes'+e.post.id"
                    :pdata="e" >
                </postevent-show>  
            </div>            
        </div>
    </div>

</div>
@endsection

@Push('customScript')
<script src="{{ mix('js/admin/app-item-update.js') }}"></script>
@endpush