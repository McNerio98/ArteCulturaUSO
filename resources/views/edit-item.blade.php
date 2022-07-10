@extends('layouts.general-template')
@section('title', 'Perfil')


@section('content')
<main role="main" class="flex-shrink-0" id="appProfileItemEdit">
    <input type="hidden" value="{{$id_user_cur}}" id="current_user_id_request" />
    <input type="hidden" value="{{$id_elem_edit}}"  id="temp_iden_edit">

    <div class="container bg-white">
        <!--::::::::::::::::::::::::::::::::::::::START CONTENT::::::::::::::::::::::::::::::::::::::-->
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <profile-summary
                v-for="(s,index) of profileSummary"
                :pdata="s"
                @medias-view="onPhotosProfiles"
                :auth-id="{{Auth::user() == null ? 0 : Auth::user()->id}}" 
                :target-id="{{$id_user_cur}}">
                </profile-summary>

                <!-- /.card -->
                <!-- About Me Box -->
                <profile-about
                v-for="(p,index) of profileAbout"
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
                            <li class="nav-item"><a class="nav-link active rd-none" href="#content" data-toggle="tab">Editando elemento</a></li>
                        </ul>
                    </div>
                    <div class="card-body" style="background-color: #ebeff3 !important;">
                        <div class="tab-content">
                            <div class="active tab-pane" id="content">
                                <!--START CONTENT, EVENTS AND POST-->
                                <div id="event-cp" style="width: 100%;max-width: 600px;margin: auto;background-color: #fff;padding: 10px;border-left: 1px solid #d8d5d5;border-right: 1px solid #d8d5d5;">
                                    <spinner1 v-if="spinners.S1" label="Cargando elemento …"></spinner1>

                                    <postevent-create @post-created="PostEventCreated"  :edit-mode="true"  v-for="e of modelo" :source-edit="e"></postevent-create>
                                    
                                    <postevent-show  :auth-id="{{Auth::user() == null ? 0 : Auth::user()->id}}" v-if="flags.show_edited" @source-files="onSources" v-for="e of modelo"  :pdata="e"></postevent-show>
                                </div>
                                <!--END START CONTENT, EVENTS AND POST-->
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

<script src="{{ mix('js/front/app-profile-edit-item.js') }}"></script>
@endpush
