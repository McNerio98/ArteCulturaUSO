@extends('layouts.admin-template')
@section('title', 'Configuración de parametros')
@section('windowName', 'CONFIGURACIÓN PARAMETROS')

@section('PanelTitle', 'SISTEMA')
@section('PanelSubtitle', 'PARAMETROS')

@section('content')
    <div class="container-fluid" id="appParams">
         <div class="row" v-if="isLoadPage">
            <div class="col-12 text-center">
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>         
            </div>
         </div>
         <div class="container" v-else>
            <div class="row">
                <div class="col-12">
                    <div class="row border-bottom mb-3 mb-lg-4" v-for="(e,index) in params" :key="e.id">
                        <div class="col-12 col-md-6 col-lg-8">
                            <h4>@{{e.option_name}}</h4>
                            <p>@{{e.description}}</p>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 d-flex flex-column justify-content-center align-items-center">
                            <template v-if="e.option_type == 'LONGTEXT'">
                                <textarea class="form-control" name="" id="" cols="15" rows="2" :disabled="!e.edit_mode" v-model="e.option_value"></textarea>
                                <div class="btn-group w-100">
                                    <span class="btn btn-success col fileinput-button dz-clickable" v-if="e.edit_mode" @click="onSave(index)">
                                        <i class="fas fa-plus"></i>
                                        <span>Guardar</span>
                                    </span>
                                    <button type="submit" class="btn btn-primary col start" v-if="!e.edit_mode" @click="e.edit_mode = true">
                                        <i class="fas fa-upload"></i>
                                        <span>Editar</span>
                                    </button>
                                    <button type="reset" class="btn btn-warning col cancel" v-if="e.edit_mode" @click="onCancel(index)">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Cancelar</span>
                                    </button>
                                </div>                                
                            </template>

                            <template v-if="e.option_type == 'TEXT'">
                                <input class="form-control" name="" :disabled="!e.edit_mode" v-model="e.option_value"/>
                                <div class="btn-group w-100">
                                    <span class="btn btn-success col fileinput-button dz-clickable" v-if="e.edit_mode" @click="onSave(index)">
                                        <i class="fas fa-plus"></i>
                                        <span>Guardar</span>
                                    </span>
                                    <button type="submit" class="btn btn-primary col start" v-if="!e.edit_mode" @click="e.edit_mode = true">
                                        <i class="fas fa-upload"></i>
                                        <span>Editar</span>
                                    </button>
                                    <button type="reset" class="btn btn-warning col cancel" v-if="e.edit_mode" @click="onCancel(index)">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Cancelar</span>
                                    </button>
                                </div>                                
                            </template>                            
                            
                            <template v-if="e.option_type == 'FLAG'">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch3" v-model="e.option_value" @change="onSave(index)">
                                    <label class="custom-control-label" for="customSwitch3">@{{e.option_value ? 'Activado' : 'Desactivado'}}</label>
                                </div>                                
                            </template>
                        </div>
                    </div>
                </div>
            </div>    
            
            <template>
                           
                            </template>

        </div>

    </div>
@endsection

@Push('customScript')
    <script src="{{ asset('js/admin/app-params.js') }}"></script>
@endpush