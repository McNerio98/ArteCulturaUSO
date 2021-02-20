@extends('layouts.admin-template')

@section('content')
<div class="container-fluid" id="appRubros">
    <!--Tag hidden Token-->
    <input type="hidden" value="{{Auth::user()->api_token}}" id="current_save_token_generate" />
    <!--End Tag Hidden Token-->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div style="max-height: 400px;overflow-y: auto;">
                                    <table class="table table-striped table-valign-middle">
                                        <tbody>
                                            <tr v-for="e of categories">
                                                <td>
                                                    <label class="form-check-label"
                                                    v-bind:class="[ref_cat_selected.id == e.id?'active':'','actable-item']"
                                                    @click="selectCategory(e)">
                                                    @{{e.name}}
                                                    </label>
                                                </td>
                                                <td>
                                                    <span> <i class="fas fa-pencil-alt icon"></i></span>
                                                </td>
                                                <td><span><i class="fas fa-trash-alt icon"></i></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="input-group" v-if="creating_category">
                                    <input type="text" name="message" 
                                    v-model="category_insert"
                                    placeholder="Nombre de nueva categoría" 
                                    maxlength="50" minlength="1" class="form-control">
                                    <span class="input-group-append">
                                        <button type="button" @click="storeCategory" class="btn btn-primary">Agregar</button>
                                    </span>
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-danger" @click="creating_category = false">Cancelar</button>
                                    </span>
                                </div>

                                <button type="button" v-if="!creating_category" @click="creating_category = true" class="btn btn-block btn-outline-success btn-flat">+ Agregar Categoria</button>
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
                                    <span v-if="!creating_tag && ref_cat_selected !=null " class="ac-tag">
                                        <a href="javascript:void(0);" v-on:click="creating_tag = true">+ Nuevo</a>
                                    </span>
                                    <span class="ac-tag" v-else>
                                        <input type="text" maxlength="25" minlength="4" v-model="tag_insert">
                                        <i v-on:click="storeTag" class="fas fa-save icon"></i>
                                        <i v-on:click="tag_insert = '';creating_tag = false;" class="fas fa-times icon"></i>
                                    </span>                                
                                    <tag-rubro v-for="(e,index) of tags" :key="e.id" @delete="deleteTag(index)" :tag="e"></tag-rubro>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <modal-trim-img @base64-generated="filterModalCropper"></modal-trim-img>

</div>
<!--/. container-fluid -->               
@endsection


@Push('customScript')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.js" integrity="sha512-9pGiHYK23sqK5Zm0oF45sNBAX/JqbZEP7bSDHyt+nT3GddF+VFIcYNqREt0GDpmFVZI3LZ17Zu9nMMc9iktkCw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.css" integrity="sha512-w+u2vZqMNUVngx+0GVZYM21Qm093kAexjueWOv9e9nIeYJb1iEfiHC7Y+VvmP/tviQyA5IR32mwN/5hTEJx6Ng==" crossorigin="anonymous" />

<script src="{{ asset('js/app-rubros.js') }}"></script>
@endpush
