@extends('layouts.users-template')
@section('title', 'Inicio')
@Push('styles')
<link href="{{ asset('css/post/post.css') }}" rel="stylesheet">
<link href="{{ asset('css/post/media.css') }}" rel="stylesheet">
@endpush


@section('content')
<input type="hidden" value="{{$id_user_cur}}" id="current_user_id_request" />
<div class="row" id="appProfile">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">

                <div class="text-center">

                    <div v-if="current_profile_media.path_file != undefined" v-bind:style="{ 'background-image': 'url(' + paths.media_profiles + current_profile_media.path_file + ')' }"
                        class="profile-pic profile-user-img img-fluid img-circle" @click="showProfilesMedia(current_profile_media.id)">
                        <i class="fas fa-camera"></i>
                    </div>

                </div>

                <h3 class="profile-username text-center">@{{user.artistic_name == null ? '(No especificado)' : user.artistic_name}}</h3>
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
                        <i class="fas fa-plus"></i>
                        <span>Guardar</span>
                      </button>
                      <button class="btn btn-warning col btn-xs" @click="is_edit_tags = false;">
                        <i class="fas fa-upload"></i>
                        <span>Cancelar</span>
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
                <strong><i class="fas fa-book mr-1"></i> Otros Nombres</strong> <i class="fas fa-pen ac-edit-about"
                    data-toggle="tooltip" data-placement="top" title="Editar"></i>
                <p class="text-muted">
                    Sinsoteca Band
                </p>
                <hr>
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong> <i
                    class="fas fa-pen ac-edit-about"></i>
                <p class="text-muted">Col 14, Sonsonate.</p>
                <hr>
                <strong><i class="fas fa-pencil-alt mr-1"></i> Correo Contacto</strong> <i
                    class="fas fa-pen ac-edit-about"></i>
                <p class="text-muted">sinsoteca.band50@music.sv</p>
                <hr>
                <strong><i class="fas fa-pencil-alt mr-1"></i> Numero Contacto</strong> <i
                    class="fas fa-pen ac-edit-about"></i>
                <p class="text-muted">+ 503 7058-7814 / +503 2450-4789</p>
                <hr>
                <strong><i class="far fa-file-alt mr-1"></i> Notas</strong> <i class="fas fa-pen ac-edit-about"></i>
                <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                    fermentum enim neque.</p>
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

                            <div class='p-2 text-center' v-if="content_desc.length < 2 && !edit_mode_desc">
                                <i class="fas fa-book-open" style='font-size: 3rem;'></i>
                                    <br>
                                    <span>DESCRIPCION VACIA</span>
                            </div>

                            <div v-if="!desc_empty && !edit_mode_desc">
                                <p>@{{content_desc}}</p>
                            </div>

                            <div v-if="edit_mode_desc">
                                <textarea placeholder="Introduce una descripcion..." v-model="description_insert" rows="7" class="form-control" style="resize: none;"></textarea>                            
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
                        <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;">
                            <!--SOLO SI EL USUARIO ESTA LOGEADO-->
                            @auth
                                @if(Auth::user()->id == $id_user_cur)
                                    <a class="mb-2" v-if="!is_creating_event" href="javascript:void(0);" @click.prevent="is_creating_event = true" style="display: flex;flex-direction: column; padding: 10px; align-items: center; background-color: #f6f6f6; margin-bottom: 10px;">
                                        <i class="fas fa-plus"></i>
                                        <u>+ Nuevo Evento</u>
                                    </a>                                    
                                    <post-event @post-created="PostEventCreated" v-if="is_creating_event" :user-info="{username: user.name, profile_path: paths.media_profiles + current_profile_media.path_file}" post-type="event"></post-event>
                                @endif
                            @endauth
                            <post-general v-for="e of items_events"  :model="e" @change-popular=""></post-general>                        
                        </div>

                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="settings">
                        <div id="post-cp">
                            <!--SOLO SI EL USUARIO ESTA LOGEADO-->
                            @auth
                                @if(Auth::user()->id == $id_user_cur)
                                    <a class="mb-2" v-if=" ! is_creating_post" href="javascript:void(0);" @click.prevent="is_creating_post = true" style="display: flex;flex-direction: column; padding: 10px; align-items: center; background-color: #f6f6f6; margin-bottom: 10px;">
                                        <i class="fas fa-plus"></i>
                                        <u>+ Nuevo Publicacion</u>
                                    </a>                                                                    
                                @endif
                            @endauth
                            <post-event f-if="is_creating_post" post-type="post"></post-event>
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
    :paths="paths" 
    :target-id="media_view.target"
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
<script src="{{asset('js/app-profile.js')}}"></script>
@endpush
