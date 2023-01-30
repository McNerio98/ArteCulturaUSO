@extends('layouts.admin-template')
@section('title', 'Rubros')
@section('windowName', 'CATEGORÍAS Y RUBROS')

@section('PanelTitle', 'RUBROS')
@section('PanelSubtitle', 'CONFIGURACIÓN')

@section('content')
<div class="container" id="appRubros">
    <!--Tag hidden Token-->
    <input type="hidden" value="{{Auth::user()->api_token}}" id="current_save_token_generate" />
    <!--End Tag Hidden Token-->
    <div class="row">
        <div class="col-md-6">
            <div style="max-height: 400px;overflow-y: auto;">
                <category-row 
                v-for="e of categories" 
                :key="e.id"
                :pdata="e" 
                :selected="ref_cat_selected"
                @deleted="onDeletedCategory"
                @select-item="selectCategory"></category-row>                
            </div>

            <div class="input-group" v-if="creating_category">
                <input type="text" name="message" 
                v-model="category_insert"
                placeholder="Nombre de nueva categoría" 
                :disabled="isSavingCat"
                maxlength="50" minlength="1" class="form-control">
                <span class="input-group-append">
                    <button type="button" @click="storeCategory" :disabled="isSavingCat" class="btn btn-primary">Agregar</button>
                </span>
                <span class="input-group-append">
                    <button type="button" class="btn btn-danger" :disabled="isSavingCat" @click="creating_category = false">Cancelar</button>
                </span>
            </div>

            <button type="button" v-if="!creating_category && has_cap('crear-rubros')" 
            @click="creating_category = true" 
            class="btn btn-block btn-outline-success btn-flat">+ Agregar Categoria</button>

        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-center" v-if="pnl_wait1">
                <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <h3 class="text-center mcgeneric">Imagen de presentación</h3>
            <div class="preview-img text-center"
                style="width: 100%; max-width: 300px; margin: auto;" v-if="!pnl_wait1">
                <div class="p-2 border">
                    <img id="imgCurrentPresentation" class="d-block" :src="ref_cat_selected.img_presentation" alt="Photo"
                        style="width: 100%; border-radius: 50%; border: 3px solid #343a40;">
                </div>

                <button @click="openModalTrim" class="btn btn-block btn-dark" id="btn_update_img_presentation">Subir Imagen</button>
            </div>

            <h5 class="mb-2 mt-3 text-center">Etiquetas/Rubros <small><i>(Los nuevos usuarios podrán seleccionar cualquiera de estas etiquetas)</i></small></h5>
            <div class="tags mt-2 text-center">


                <span v-if="!creating_tag && ref_cat_selected !=null && has_cap('crear-rubros')" class="ac-tag">
                    <a href="javascript:void(0);" v-on:click="creating_tag = true">+ Nuevo</a>
                </span>
                <span class="ac-tag" v-if="creating_tag && ref_cat_selected != null && has_cap('crear-rubros')">
                    <input type="text" maxlength="25" minlength="4" v-model="tag_insert">
                    <i v-on:click="storeTag" class="fas fa-save icon"></i>
                    <i v-on:click="tag_insert = '';creating_tag = false;" class="fas fa-times icon"></i>
                </span>     

                <tag-rubro v-for="(e,index) of tags" :key="e.id" @delete="deleteTag(index)" :ptag="e"></tag-rubro>
            </div>
        </div>
    </div>
    
    <modal-trim-img @base64-generated="filterModalCropper"></modal-trim-img>

</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="{{ asset('js/cropper.min.js') }}"></script>
<link href="{{ asset('css/cropper.min.css') }}" rel="stylesheet">

<script src="{{ mix('js/admin/app-rubros.js') }}"></script>
@endpush
