@extends('layouts.general-template')
@section('title', 'Editar elemento')

@section('content')
<main role="main" class="flex-shrink-0" id="appEditPostFront">
    <div class="container bg-white">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <input type="hidden" id="idpostevent" value="{{$postid}}">
        <spinner1 v-if="isLoading" label="Cargando elemento …"></spinner1>
        <postevent-create v-for="e of modelo_create" 
                        :pdata="e"
                        :key="'id' + (new Date()).getTime()"
                        v-if="!isLoading"
                        @saved="postEventCreated">
        </postevent-create>        

        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>
</main>
@endsection

@Push('customScript')
    <script src="{{ asset('js/front/app-showedit.js') }}"></script>
@endpush
