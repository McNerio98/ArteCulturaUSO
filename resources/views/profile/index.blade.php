@extends('layouts.general-template')
@section('title', 'Perfil')


@section('content')
<main role="main" class="flex-shrink-0" id="appProfile">
    <input type="hidden" value="{{$id_user_cur}}" id="currentUserIdRequest" />
    <div class="container bg-white">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <profile-summary
                v-for="(s,index) in profileSummary"
                :pdata="s"
                @imgs-perfil="onPhotosProfiles"
                :auth-id="{{Auth::user() == null ? 0 : Auth::user()->id}}" 
                @on-promo="onPromo"
                :target-id="{{$id_user_cur}}">
                </profile-summary>

                <!-- /.card -->
                <!-- About Me Box -->

                <profile-about
                v-for="(p,index) in profileAbout"
                :pdata="p"
                :auth-id="{{Auth::user() == null ? 0 : Auth::user()->id}}" 
                :target-id="{{$id_user_cur}}">
                </profile-about>
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
                    <div class="card-body p-0" style="background-color: #ebeff3 !important;">
                        <div class="tab-content">
                            <!--TAB de Contenido principal de usuario-->
                            <div class="active tab-pane" id="content">
                                <!--START CONTENT, EVENTS AND POST-->
                                <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;" class="pt-2 pt-md-3">
                                    <!--SOLO SI EL USUARIO ESTA LOGEADO-->
                                <div class="text-center _acNoCnt pb-1 pb-md-3" v-if="items_postevents.length == 0 && !isCreating">
                                    <img src="{{asset('images/no-task.svg')}}" alt="" style="width: 80%;max-width: 100px;">
                                    <h2>No hay contenido que mostrar</h2>
                                    <p class="lead">crea contenido de forma fácil y rápida.</p>
                                </div>                                                
                                    @auth
                                        @if(Auth::user()->id == $id_user_cur)
                                            <div class="row pb-1 pb-md-3" v-if="!isCreating && items_postevents.length == 0">
                                                <div class="col-6 m-auto">
                                                    <button @click="onCreate('event')" class="makePosting"> <img class="makeItemPosting" src="{{asset('images/create_event.svg')}}" alt=""> CREAR EVENTO</button>
                                                </div>

                                                <!-- <div class="col-6">
                                                    <button @click="flag_create.type = 'post'; flag_create.creating = true;"   class="makePosting"><img class="makeItemPosting" src="{{asset('images/create_post.svg')}}" alt=""> CREAR POST</button>
                                                </div>   
                                                -->
                                            </div>
                                            <postevent-create :pdata="modelo_create"
                                                @saved="PostEventCreated" 
                                                v-if="isCreating">
                                            </postevent-create>

                                        @endif
                                    @endauth

                                    <postevent
                                        v-for="(e,index) in items_postevents"  
                                        @edit-item="onUpdatePostEvent" 
                                        @delete-item="onDeletePostEvent(index)" 
                                        @source-files="onSources" 
                                        :key="'pes'+e.post.id"
                                        :pdata="e" >
                                    </postevent>                                      
                                 

                                    <pagination-component  v-if="flags.show_pg1" @source-items="itemLoaded" route="{{'/postsevents/'.$id_user_cur}}" :per_page="15"></pagination-component>            
                                </div>
                                <!--END START CONTENT, EVENTS AND POST-->
                            </div>
                            <!--TAB de Descripcion de perfil-->
                            <div class="tab-pane" id="abaout_extra">
                                <div class="post">
                                    <div class='text-right mb-1'>
                                        <button v-if="showBtnEdit" v-on:click="onEditDescription" type="button" class="btn btn-outline-primary btn-flat"><i class="fas fa-pencil-alt"></i> Editar</button>                                
                                        <button v-if="showBtnSave" class="btn btn-outline-success btn-flat" @click="saveDataConfig('description')"><i class="fas fa-save"></i> Guardar</button>                                
                                        <button  v-if="showBtnCancel" @click="data_config.description.edit_mode = false;data_config.description.value = data_config.description.bk" class="btn btn-outline-secondary btn-flat"><i class="fas fa-ban"></i> Cancelar</button>    
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

        <input type="file" @change="cropperImageProfile" accept="image/png, image/jpeg" ref="fileElementImage" class="d-none">
        <!-- ::::::::::::::::::::::::::::::::::::::END CONTENT::::::::::::::::::::::::::::::::::::::-->
    </div>        

    <media-viewer @new-profileimg="onChageImage" 
    @setlike-perfil="onSelectLikePerfil"
    @delete="onDeleteProfileImg"
    ref="mediaviewer"></media-viewer>    


    <control-trim
        ref="acVmCompCropper"
        @base64-generated="filterModalCropper"
        :aspect-ratio="trim_buffer.aspec_ratio">    
</main>
@endsection

@Push('customScript')
    <link href="{{ asset('css/cropper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/cropper.min.js') }}"></script>
    <script src="{{ mix('js/front/app-profile.js') }}"></script>
@endpush
