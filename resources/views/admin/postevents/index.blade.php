@extends('layouts.admin-template')
@section('title', 'Contenido')
@section('windowName', 'MI CONTENIDO')

@section('content')
<div class="container-fluid" id="appContent">
    <div class="row">
        <div class="col-12">
            <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;padding: 10px;">
            
                <div class="text-center _acNoCnt" v-if="items_postevents.length == 0 && !isCreating">
                    <img src="{{asset('images/no-task.svg')}}" alt="" style="width: 80%;max-width: 100px;">
                    <h2>No hay contenido que mostrar</h2>
                    <p class="lead">crea contenido de forma fácil y rápida.</p>
                </div>            
                <!--SOLO SI EL USUARIO ESTA LOGEADO-->
                @auth
                    <div class="row pb-1 pb-md-3" v-if="!isCreating && items_postevents.length == 0">
                        <div class="col-6 m-auto">
                            <button @click="onCreate('event')" class="makePosting"> <img class="makeItemPosting" src="{{asset('images/create_event.svg')}}" alt=""> CREAR EVENTO</button>
                        </div>
                        <!--
                        <div class="col-6">
                                <button @click="onCreate('post')"   class="makePosting"><img class="makeItemPosting" src="{{asset('images/create_post.svg')}}" alt=""> CREAR POST</button>
                        </div>    
                        -->                                
                    </div>

                    <postevent-create v-for="e of modelo_create" 
                        :pdata="e"
                        :key="'id' + (new Date()).getTime()"
                        @saved="PostEventCreated" 
                        v-if="isCreating">
                    </postevent-create>

                @endauth
                <postevent-show
                    v-for="(e,index) of items_postevents"  
                    @edit-item="onUpdatePostEvent" 
                    @delete-item="onDeletePost(index)" 
                    @source-files="onSources" 
                    :key="'pes'+e.post.id"
                    :pdata="e" >
                </postevent-show>  

                <pagination-component  
                v-if="!no_data_postevents"
                @source-items="itemLoaded" route="{{'/postsevents/'.Auth::user()->id}}" :per_page="15"></pagination-component>            

            </div>            
        </div>
    </div>


    <media-viewer 
    ref="mediaviewer"
     :items="media_view.items">
    </media-viewer>    
</div>
@endsection

@Push('customScript')
    <script src="{{ mix('js/admin/app-content.js') }}"></script>
@endpush
