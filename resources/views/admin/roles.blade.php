
@extends('layouts.admin-template')
@section('title', 'Privilegios')
@section('windowName', 'ROLES Y PERMISOS')

@section('PanelTitle', 'SEGURIDAD')
@section('PanelSubtitle', 'ROLES Y PERMISOS')

@section('content')
    <div class="container" id="appRoles">
        <section class="content">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="max-width: 1200px; margin: auto;">
                        <div class="col-md-5">
                            <!--Segun la documentacion de vue usar for in en la clausula v-for-->
                            <div v-for="e of roles" v-if="e.role_name != 'Invitado'" :class="{ 'bg-gray' : (e.role_id == role_selected.role_id) }" class="border border-dark py-2 px-3 mt-4 _acHoverA" @click="setRoleSelected(e)">
                                <h4 class="mb-0">
                                    <i class="fas fa-cogs"></i>
                                    @{{e.role_name}}
                                </h4>

                                <div class="progress progress-xs progress-striped active">
                                    <div class="progress-bar bg-success" :style="{width: parseInt( (   e.count_caps  / count_caps_global ) * 100 )+'%' }"></div>
                                </div>
                                <span class="badge bg-warning">@{{ parseInt( (  e.count_caps  / count_caps_global ) * 100 )}}% permisos concedidos</span>
                            </div>

                            <!-- <div class="mt-4">
                                <div class="btn btn-primary btn-lg btn-flat">
                                <i class="fas fa-plus"></i>
                                    Crear Rol
                                </div>
                            </div> -->

                        </div>
                        <div class="col-md-7">

                            <table class="table table-bordered">
                                <thead>
                                    <th style="width: 40%;">Permiso</th>
                                    <th>Descripcion</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(cap,index) in caps">
                                        <td class="p-1">
                                            <div class="form-group clearfix m-0">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox" :id=" `checkboxPrimary${index}` " :disabled="!has_cap('asignar-permisos')" :checked="capsInCurrentRole(cap.id)" @click="switchStateCap($event,cap.id)">
                                                    <label :for=" `checkboxPrimary${index}` ">
                                                    </label>
                                                </div>
                                                @{{cap.name}}
                                            </div>
                                        </td>
                                        <td class="p-1">
                                            @{{cap.description}}
                                        </td>                                    
                                    </tr>    
                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>
        </section>        
    </div>
<!--/. container-fluid -->  
@endsection


@Push('customScript')
    <script src="{{ asset('js/admin/app-roles.js') }}"></script>
@endpush
