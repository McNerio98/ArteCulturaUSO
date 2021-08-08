@extends('layouts.users-template')
@section('title', 'Inicio')
@Push('styles')

@endpush


@section('content')
<input type="hidden" value="{{$id_user_cur}}" id="current_user_id_request" />
<div class="row" id="appProfile">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">

                <div class="text-center">

                    <div v-if="current_profile_media.path_file != undefined" v-bind:style="{ 'background-image': 'url(' + obj_ac_app.base_url + '/' + paths.media_profiles + current_profile_media.path_file + ')' }"
                        class="profile-pic profile-user-img img-fluid img-circle" @click="showProfilesMedia(current_profile_media)">
                        <i class="fas fa-camera"></i>
                    </div>

                </div>

                <h3 v-if="data_config.nickname.edit_mode == false" class="profile-username text-center">@{{data_config.nickname.value == undefined || data_config.nickname.value.trim().length ==0 ? '(No especificado)' : data_config.nickname.value}}
                    @auth 
                        @if(Auth::user()->id == $id_user_cur)
                        <i @click="data_config.nickname.edit_mode = true" class="fas fa-pen ac-edit-about"></i>
                        @endif
                    @endauth                      
                </h3>

                <input class="form-control" type="text" v-model="data_config.nickname.value" placeholder="#" v-if="data_config.nickname.edit_mode == true">
                <div class="btn-group w-100" v-if="data_config.nickname.edit_mode == true">
                    <button class="btn btn-success col btn-xs" @click="persist_data_config('nickname')">
                        <i class="fas fa-plus"></i> <span>Guardar</span>
                    </button>
                    <button class="btn btn-warning col btn-xs" @click="data_config.nickname.edit_mode = false">
                        <i class="fas fa-times"></i> <span>Cancelar</span>
                    </button>
                </div>  

                <p class="text-muted text-center">
                    <span v-for="(e,index) of rubros" >
                        <span class="usTagProfile">
                        @{{e.name}}
                        @auth
                            @if(Auth::user()->id == $id_user_cur)
                            <span class="iconDel" @click="deleteTagUser(e.id,index)"><i class="fas fa-times"></i></span>
                            @endif
                        @endauth
                        </span>
                    <template v-if="index != (rubros.length - 1)">,</template>
                    </span>
                </p>
                @auth
                    @if(Auth::user()->id == $id_user_cur)
                    <button type="button" @click="showListTags" class="btn btn-block btn-default btn-xs mb-3" v-if="!is_edit_tags">+ Agregar rubro</button>
                    <select required v-model="rubro_to_insert" class="custom-select" v-if="is_edit_tags">
                          <option value="0" disabled selected>Elegir</option>
                        <optgroup  v-for="(main, key) in list_tags" v-bind:key="key" :label="key">
                          <option  v-for="(item, i) in main" v-bind:key="i" :value="item.id">@{{item.tag}}</option>
                        </optgroup>
                      </select>                    
                    <div class="btn-group w-100" v-if="is_edit_tags">
                      <button class="btn btn-success col btn-xs" @click="addTagUser">
                        <i class="fas fa-plus"></i><span>Guardar</span>
                      </button>
                      <button class="btn btn-warning col btn-xs" @click="is_edit_tags = false;">
                        <i class="fas fa-times"></i><span>Cancelar</span>
                      </button>
                    </div>                                        
                    @endif
                @endauth
                

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Publicaciones</b> <a class="float-right">@{{user.count_posts}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Proximos Eventos</b> <a class="float-right">@{{user.count_events}}</a>
                    </li>
                </ul>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- About Me Box -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Acerca de</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="far fa-envelope"></i> Correo Contacto</strong>
                <p v-if="data_config.email.edit_mode == false" class="text-muted val-about">@{{data_config.email.value}} 
                    @auth 
                        @if(Auth::user()->id == $id_user_cur)
                        <i @click="data_config.email.edit_mode = true" class="fas fa-pen ac-edit-about"></i>
                        @endif
                    @endauth
                </p>

                <input class="form-control form-control-sm" v-model="data_config.email.value" type="text" placeholder="example@example.com" v-if="data_config.email.edit_mode == true">
                <div class="btn-group w-100" v-if="data_config.email.edit_mode == true">
                    <button class="btn btn-success col btn-xs" @click="persist_data_config('email')">
                        <i class="fas fa-plus"></i> <span>Guardar</span>
                    </button>
                    <button class="btn btn-warning col btn-xs" @click="data_config.email.edit_mode = false">
                        <i class="fas fa-times"></i> <span>Cancelar</span>
                    </button>
                </div>                          

                <hr>            
                <strong><i class="fas fa-phone-alt"></i> Numero Contacto</strong>
                <p v-if="data_config.phone.edit_mode == false" class="text-muted val-about">@{{data_config.phone.value}} 
                    @auth 
                        @if(Auth::user()->id == $id_user_cur)
                        <i @click="data_config.phone.edit_mode = true" class="fas fa-pen ac-edit-about"></i>
                        @endif
                    @endauth                    
                </p>
                <input class="form-control form-control-sm" type="text" v-model="data_config.phone.value" placeholder="#" v-if="data_config.phone.edit_mode == true">
                <div class="btn-group w-100" v-if="data_config.phone.edit_mode == true">
                    <button class="btn btn-success col btn-xs" @click="persist_data_config('phone')">
                        <i class="fas fa-plus"></i> <span>Guardar</span>
                    </button>
                    <button class="btn btn-warning col btn-xs" @click="data_config.phone.edit_mode = false">
                        <i class="fas fa-times"></i> <span>Cancelar</span>
                    </button>
                </div>   

                <hr>                
                <strong><i class="far fa-address-book"></i> Otros nombres</strong>
                <p v-if="data_config.other_name.edit_mode == false" class="text-muted val-about">@{{data_config.other_name.value}} 
                    @auth 
                        @if(Auth::user()->id == $id_user_cur)
                        <i @click="data_config.other_name.edit_mode = true" class="fas fa-pen ac-edit-about"></i>
                        @endif
                    @endauth                        
                </p>
                <input class="form-control form-control-sm" type="text" v-model="data_config.other_name.value" placeholder="#" v-if="data_config.other_name.edit_mode == true">
                <div class="btn-group w-100" v-if="data_config.other_name.edit_mode == true">
                    <button class="btn btn-success col btn-xs" @click="persist_data_config('other_name')">
                        <i class="fas fa-plus"></i> <span>Guardar</span>
                    </button>
                    <button class="btn btn-warning col btn-xs" @click="data_config.other_name.edit_mode = false">
                        <i class="fas fa-times"></i> <span>Cancelar</span>
                    </button>
                </div>  

                <hr>
                <!--METADATOS-->
                <!--Se podrian agregar mas-->
                <strong><i class="fas fa-map-marker-alt"></i> Dirección</strong>
                <p v-if="data_config.address.edit_mode == false" class="text-muted val-about">@{{data_config.address.value == undefined || data_config.address.value.length == 0 ? 'No especificado' : data_config.address.value}} 
                    @auth 
                        @if(Auth::user()->id == $id_user_cur)
                        <i @click="data_config.address.edit_mode = true" class="fas fa-pen ac-edit-about"></i>                        
                        @endif
                    @endauth                      
                </p>
                <input class="form-control form-control-sm" type="text" v-model="data_config.address.value" placeholder="" v-if="data_config.address.edit_mode == true">
                <div class="btn-group w-100" v-if="data_config.address.edit_mode == true">
                    <button class="btn btn-success col btn-xs" @click="persist_data_config('address')">
                        <i class="fas fa-plus"></i> <span>Guardar</span>
                    </button>
                    <button class="btn btn-warning col btn-xs" @click="data_config.address.edit_mode = false">
                        <i class="fas fa-times"></i> <span>Cancelar</span>
                    </button>
                </div>  

                <hr>
                <strong><i class="far fa-file-alt mr-1"></i> Notas</strong>
                <p v-if="data_config.notes.edit_mode == false" class="text-muted val-about">@{{data_config.notes.value == undefined || data_config.notes.value.length == 0 ? 'No especificado' : data_config.notes.value}} 
                    @auth 
                        @if(Auth::user()->id == $id_user_cur)
                        <i @click="data_config.notes.edit_mode = true" class="fas fa-pen ac-edit-about"></i>                        
                        @endif
                    @endauth                       
                </p>
                <input class="form-control form-control-sm" type="text" v-model="data_config.notes.value" placeholder="" v-if="data_config.notes.edit_mode == true">
                <div class="btn-group w-100" v-if="data_config.notes.edit_mode == true">
                    <button class="btn btn-success col btn-xs" @click="persist_data_config('notes')">
                        <i class="fas fa-plus"></i> <span>Guardar</span>
                    </button>
                    <button class="btn btn-warning col btn-xs" @click="data_config.notes.edit_mode = false">
                        <i class="fas fa-times"></i> <span>Cancelar</span>
                    </button>
                </div>                  
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active rd-none" href="#biografia"
                            data-toggle="tab">Contenido</a></li>
                    <li class="nav-item"><a class="nav-link rd-none" href="#timeline" data-toggle="tab">Eventos</a>
                    </li>
                    <li class="nav-item"><a class="nav-link rd-none" href="#settings"
                            data-toggle="tab">Publicaciones</a>
                    </li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body" style="background-color: #ebeff3 !important;">
                <div class="tab-content">
                    <div class="active tab-pane" id="biografia">
                        <div class="post">
                            <div class='text-right mb-1'>
                                <button v-if="!edit_mode_desc" v-on:click="onClickEdit" type="button" class="btn btn-outline-primary btn-flat"><i class="fas fa-pencil-alt"></i> Editar</button>                                
                                <button v-else class="btn btn-outline-success btn-flat" @click="storeUserDescription"><i class="fas fa-save"></i> Guardar</button>                                
                                <button  v-if="edit_mode_desc" @click="edit_mode_desc = false" class="btn btn-outline-secondary btn-flat"><i class="fas fa-ban"></i> Cancelar</button>    
                            </div>


                            <div class='p-2 text-center' v-if="(data_config.description.value == undefined || data_config.description.value.trim().length == 0) && ! data_config.description.edit_mode">
                                <i class="fas fa-book-open" style='font-size: 3rem;'></i>
                                    <br>
                                    <span>DESCRIPCION VACIA</span>
                            </div>

                            <div v-if="!desc_empty && !edit_mode_desc">
                                <p>@{{data_config.description.value}}</p>
                            </div>

                            <div v-if="edit_mode_desc">
                                <textarea placeholder="Introduce una descripcion..." v-model="data_config.description.value" rows="7" class="form-control" style="resize: none;"></textarea>                            
                            </div>
                        </div>
                        <!-- Post -->
                        <div class="post">
                            <h4 class="text-primary mb-4">Fotografias destacadas</h4>
                        </div>
                        <!-- /.post -->
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">
                        <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;background-color: #fff;padding: 10px;border-left: 1px solid #d8d5d5;border-right: 1px solid #d8d5d5;">
                            <!--SOLO SI EL USUARIO ESTA LOGEADO-->
                            @auth
                                @if(Auth::user()->id == $id_user_cur)
                                    <div class="row">
                                        <div class="col-6">
                                            <button @click="flag_create.type = 'event'; flag_create.creating = true;" class="makePosting"> <img class="makeItemPosting" src="{{asset('images/create_event.svg')}}" alt=""> CREAR EVENTO</button>
                                        </div>
                                        <div class="col-6">
                                            <button @click="flag_create.type = 'post'; flag_create.creating = true;"   class="makePosting"><img class="makeItemPosting" src="{{asset('images/create_post.svg')}}" alt=""> CREAR POST</button>
                                        </div>                                    
                                    </div>
                                    <post-event @post-created="PostEventCreated" v-if="flag_create.creating == true" :user-info="{username: user.name, profile_path: obj_ac_app.base_url + '/' + paths.media_profiles + current_profile_media.path_file}" :post-type="flag_create.type"></post-event>
                                @endif
                            @endauth

                            <post-general @source-files="onSources" v-for="e of items_postevents"  :model="e" @change-popular=""></post-general>
                            
                            
                            <pagination-component @source-items="itemLoaded" route="/postsevents/3" :per_page="4"></pagination-component>

                        </div>
                    </div>

                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="settings">
                        <div id="post-cp" style="width: 100%;max-width: 600px;margin: auto;">
                            aqui no hay nada
                        </div>
                    </div>
                    <!-- /.tab-content -->

                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    
    <media-viewer 
    :media-profile="is_mdprofiles"  
    :target="media_view.target"
    :logged.number='{{Auth::user() == null ? 0 : Auth::user()->id}}'
    :owner="media_view.owner"
    @new-profile-media="openTrim"
     :items="media_view.items">
    </media-viewer>    

    <modal-trim-img @base64-generated="filterModalCropper"></modal-trim-img>
</div>


@endsection


@Push('customScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.js" integrity="sha512-9pGiHYK23sqK5Zm0oF45sNBAX/JqbZEP7bSDHyt+nT3GddF+VFIcYNqREt0GDpmFVZI3LZ17Zu9nMMc9iktkCw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.css" integrity="sha512-w+u2vZqMNUVngx+0GVZYM21Qm093kAexjueWOv9e9nIeYJb1iEfiHC7Y+VvmP/tviQyA5IR32mwN/5hTEJx6Ng==" crossorigin="anonymous" />

<script src="{{ mix('js/app-profile.js') }}"></script>
@endpush
