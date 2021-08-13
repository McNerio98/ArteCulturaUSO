@extends('layouts.admin-template')
@section('title', 'Contenido')
@section('windowName', 'MI CONTENIDO')

@section('content')
<div class="container-fluid" id="appContent">
    <div class="row">
        <div class="col-12">
            <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;padding: 10px;">
            
                <div class="text-center _acNoCnt" v-if="items_postevents.length == 0">
                    <img src="{{asset('images/no-task.svg')}}" alt="" style="width: 80%;max-width: 100px;">
                    <h2>No hay contenido que mostrar</h2>
                    <p class="lead">crea contenido de forma fácil y rápida.</p>
                </div>            
                <!--SOLO SI EL USUARIO ESTA LOGEADO-->
                @auth
                    <div class="row pb-1 pb-md-3">
                        <div class="col-6">
                            <button @click="flag_create.type = 'event'; flag_create.creating = true;" class="makePosting"> <img class="makeItemPosting" src="{{asset('images/create_event.svg')}}" alt=""> CREAR EVENTO</button>
                        </div>
                        <div class="col-6">
                                <button @click="flag_create.type = 'post'; flag_create.creating = true;"   class="makePosting"><img class="makeItemPosting" src="{{asset('images/create_post.svg')}}" alt=""> CREAR POST</button>
                        </div>                                    
                    </div>
                    <content-create v-if="flag_create.creating == true" :user-info="current_user" :post-type="flag_create.type"></content-create>
                @endauth
                <post-general @edit-item="onItemEdit" @delete-item="onItemDelete" @source-files="onSources" v-for="e of items_postevents"  :model="e"></post-general>            
                <pagination-component  @source-items="itemLoaded" route="{{'/postsevents/'.Auth::user()->id}}" :per_page="15"></pagination-component>            

            </div>            
        </div>
    </div>


    <media-viewer 
    :media-profile="is_mdprofiles"  
    :target="media_view.target"
    :logged.number='{{Auth::user() == null ? 0 : Auth::user()->id}}'
    :owner="media_view.owner"
     :items="media_view.items">
    </media-viewer>    
</div>
@endsection

@Push('customScript')
    <script src="{{ mix('js/admin/app-content.js') }}"></script>
@endpush
