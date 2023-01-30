@extends('layouts.general-template')
@section('title', 'Mostrar elemento')

@section('content')
<main role="main" class="flex-shrink-0" id="appShowPostFront">
    <div class="container bg-white">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <input type="hidden" id="idpostevent" value="{{$postid}}">
        <spinner1 v-if="isLoading" label="Cargando elemento …"></spinner1>
        <postevent-show
            v-for="(e,index) of items_postevents"  
            @edit-item="onUpdatePostEvent" 
            @delete-item="onDeletePost" 
            @on-promo="onPromo"
            @source-files="onSources" 
            :key="'pes'+e.post.id"
            :pdata="e" >
        </postevent-show>  
        <div class="pb-2"></div>

        <media-viewer ref="mediaviewer"></media-viewer>    

        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-showedit.js') }}"></script>
@endpush
