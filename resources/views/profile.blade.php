@extends('layouts.general-template')
@section('title', 'Perfil')


@section('content')
<main role="main" class="flex-shrink-0" id="appProfile">
    <input type="hidden" value="{{$id_user_cur}}" id="current_user_id_request" />
    <div class="container bg-white">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <general-info-profile 
                @medias-view="onPhotosProfiles"
                :auth-id="{{Auth::user() == null ? 0 : Auth::user()->id}}" 
                :target-id="{{$id_user_cur}}"></general-info-profile>
                <!-- /.card -->
                <!-- About Me Box -->
                <about-profile 
                @info-user="loadInfoUser"
                :auth-id="{{Auth::user() == null ? 0 : Auth::user()->id}}" 
                :target-id="{{$id_user_cur}}"></about-profile>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active rd-none" href="#content" data-toggle="tab">Contenido</a></li>
                            <li class="nav-item"><a class="nav-link rd-none" href="#abaout_extra" data-toggle="tab">Descripcion</a></li>
                        </ul>
                    </div>
                    <div class="card-body" style="background-color: #ebeff3 !important;">
                        <div class="tab-content">
                            <div class="active tab-pane" id="content">
                                <!--START CONTENT, EVENTS AND POST-->
                                <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;padding: 10px;">
                                    <!--SOLO SI EL USUARIO ESTA LOGEADO-->
                                <div class="text-center _acNoCnt pb-1 pb-md-3" v-if="items_postevents.length == 0">
                                    <img src="{{asset('images/no-task.svg')}}" alt="" style="width: 80%;max-width: 100px;">
                                    <h2>No hay contenido que mostrar</h2>
                                    <p class="lead">crea contenido de forma fácil y rápida.</p>
                                </div>                                                
                                    @auth
                                        @if(Auth::user()->id == $id_user_cur)
                                            <div class="row pb-1 pb-md-3">
                                                <div class="col-6">
                                                    <button @click="flag_create.type = 'event'; flag_create.creating = true;" class="makePosting"> <img class="makeItemPosting" src="{{asset('images/create_event.svg')}}" alt=""> CREAR EVENTO</button>
                                                </div>
                                                <div class="col-6">
                                                    <button @click="flag_create.type = 'post'; flag_create.creating = true;"   class="makePosting"><img class="makeItemPosting" src="{{asset('images/create_post.svg')}}" alt=""> CREAR POST</button>
                                                </div>                                    
                                            </div>
                                            <content-create @post-created="PostEventCreated" v-if="flag_create.creating == true" :user-info="current_user" :post-type="flag_create.type"></content-create>
                                        @endif
                                    @endauth
                                    <post-general 
                                    @source-files="onSources" 
                                    @edit-item="onItemEdit" 
                                    v-for="e of items_postevents"  
                                    :model="e" 
                                    :auth-id="{{Auth::user() == null ? 0 : Auth::user()->id}}">
                                    </post-general>

                                    <pagination-component  v-if="flags.show_pg1" @source-items="itemLoaded" route="{{'/postsevents/'.Auth::user()->id}}" :per_page="15"></pagination-component>            
                                </div>
                                <!--END START CONTENT, EVENTS AND POST-->
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="abaout_extra">
                                <div class="post">
                                    <div class='text-right mb-1'>
                                        <button v-if="!data_config.description.edit_mode" v-on:click="data_config.description.edit_mode = true;data_config.description.bk = data_config.description.value" type="button" class="btn btn-outline-primary btn-flat"><i class="fas fa-pencil-alt"></i> Editar</button>                                
                                        <button v-else class="btn btn-outline-success btn-flat" @click="saveDataConfig('description')"><i class="fas fa-save"></i> Guardar</button>                                
                                        <button  v-if="data_config.description.edit_mode" @click="data_config.description.edit_mode = false;data_config.description.value = data_config.description.bk" class="btn btn-outline-secondary btn-flat"><i class="fas fa-ban"></i> Cancelar</button>    
                                    </div>


                                    <div class='p-2 text-center' v-if="(data_config.description.value == undefined || data_config.description.value.trim().length == 0) && ! data_config.description.edit_mode">
                                        <i class="fas fa-book-open" style='font-size: 3rem;'></i>
                                            <br>
                                            <span>DESCRIPCION VACIA</span>
                                    </div>

                                    <div v-if="!data_config.description.edit_mode">
                                        <p>@{{data_config.description.value}}</p>
                                    </div>

                                    <div v-if="data_config.description.edit_mode">
                                        <textarea placeholder="Introduce una descripcion..." v-model="data_config.description.value" rows="7" class="form-control" style="resize: none;"></textarea>                            
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>


        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>        
    <media-viewer 
    :media-profile="is_mdprofiles"  
    :target="media_view.target"
    :logged.number='{{Auth::user() == null ? 0 : Auth::user()->id}}'
    :owner="media_view.owner"
    @new-profile-media="openTrim"
     :items="media_view.items">
    </media-viewer>    

    <modal-trim-img @base64-generated="filterModalCropper"></modal-trim-img>    
</main>
@endsection

@Push('customScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.js" integrity="sha512-9pGiHYK23sqK5Zm0oF45sNBAX/JqbZEP7bSDHyt+nT3GddF+VFIcYNqREt0GDpmFVZI3LZ17Zu9nMMc9iktkCw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.css" integrity="sha512-w+u2vZqMNUVngx+0GVZYM21Qm093kAexjueWOv9e9nIeYJb1iEfiHC7Y+VvmP/tviQyA5IR32mwN/5hTEJx6Ng==" crossorigin="anonymous" />

<script src="{{ mix('js/front/app-profile.js') }}"></script>
@endpush
