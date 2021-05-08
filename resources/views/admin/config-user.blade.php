@extends('layouts.admin-template')

@section('content')
    <div class="container-fluid" id="appConfigUser">
        <input type="hidden" value="{{$id_user_cur}}" id="current_user_id_request" />
         <div class="row" v-if="loading_page">
            <div class="col-12 text-center">
                <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>         
            </div>
         </div>
        <div class="row" v-else>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12 col-lg-4" style="border-right: 2px solid #b5b5b5;">
                                <h4 class="text-primary text-center">Credenciales de usuario </h4>
                                <div class="alert alert-warning p-2" v-if="!is_accepted_request">
                                    <h5><i class="icon fas fa-exclamation-triangle"></i> Credenciales requeridas!</h5>
                                    Brinde las credenciales de acceso de este nuevo usuario.
                                </div>
                                <form novalidate ref="frmUpdateCredentials">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nombre de usuario</label>
                                        <input type="text" class="form-control" id="raUserName" v-model="credentials.username"
                                            placeholder="Ingrese nombre de usuario" :disabled="!is_edit_credential" required>
                                        <div class="invalid-feedback">
                                            Nombre de usuario obligatorio
                                        </div>
                                        <div v-if="username_exist" style="width: 100%;margin-top: 0.25rem;font-size: 80%;color: #dc3545;font-weight: bold;">
                                            El nombre de usuario ya existe 
                                        </div>                                              
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Correo electrónico</label>
                                        <input type="email" class="form-control" id="raUserEmail" v-model="credentials.email"
                                            placeholder="Ingrese un correo" :disabled="!is_edit_credential" required>
                                        <div class="invalid-feedback">
                                            Correo electronico obligatorio
                                        </div>
                                        <div v-if="email_exist" style="width: 100%;margin-top: 0.25rem;font-size: 80%;color: #dc3545;font-weight: bold;">
                                            El correo electronico ya existe
                                        </div>                                             
                                    </div>
                                    @can('configurar-usuario')                                    
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Contraseña</label>
                                        <input type="text" class="form-control" id="raPass" v-model="credentials.pass"
                                            placeholder="Ingrese contraseña" :disabled="!is_edit_credential">
                                    </div>
                                    @endcan

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Rol de usuario</label>
                                        <select class="custom-select" id="raUserRole" :disabled="!is_edit_credential" v-model="role_selected">
                                            <option value="0" selected disabled>-- SELECCIONAR ROL --</option>
                                            @foreach($all_roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1">Notificar por correo</label>
                                    </div>

                                    <div class="text-center">
                                        @can('configurar-usuario')
                                        <button type="submit" class="btn btn-warning" v-if="!is_accepted_request && !is_edit_credential" @click.prevent="is_accept_account = true ; editCredentials(true);">Aceptar solicitud</button>
                                        <button type="submit" class="btn btn-warning" v-if="is_accepted_request && !is_edit_credential" @click.prevent="editCredentials(true);">Editar credenciales</button>                                        
                                        @endcan
                                        <div class="btn-group w-100" v-if="is_edit_credential">
                                            <button class="btn btn-success col fileinput-button dz-clickable" :disabled="send_credentials" @click.prevent="updateCredentials">
                                                <i class="fas fa-save"></i>
                                                <span>Guardar</span>
                                            </button>
                                            <button class="btn btn-primary col start" @click="editCredentials(false);">
                                                <i class="fas fa-times-circle"></i>
                                                <span>Cancelar</span>
                                            </button>
                                        </div> 
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-12 col-lg-8">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="text-center mb-lg-4">Información del usuario</h4>
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="user-block" style="margin-bottom: 15px; ">
                                                    <img class="img-circle img-bordered-sm" src="../img/índice.jpg"
                                                        alt="user image">
                                                    <span class="username">
                                                        <a href="#">Jonathan Burke Jr.</a>
                                                    </span>
                                                    <span class="description">Rubros: </span>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                @can('configurar-usuario')
                                                <div class="text-center">
                                                    <button v-if="user.status == 'disabled' " @click.prevent="changeStatus($event,'enable-user')"  class="btn btn-sm btn-primary">Activar cuenta</button>
                                                    <button v-if="user.status == 'enabled' "  @click.prevent="changeStatus($event,'disable-user')" class="btn btn-sm btn-warning">Desactivar cuenta</button>
                                                    <button v-if="user.active == 1" @click.prevent="deleteAccount"class="btn btn-sm btn-danger">Eliminar cuenta</button>
                                                </div>
                                                @endcan
                                            </div>
                                        </div>
                                        <p style="color: #666;" v-if="!is_edit_desc">
                                            @{{user_description}}

                                            <span class="edit-pencial" @click="is_edit_desc = true"><i class="fas fa-pencil-alt"></i></span>
                                        </p>
                                        <div class="form-group" v-else>
                                            <label for="exampleFormControlTextarea1">Nueva descripción</label>
                                            <textarea class="form-control" id="raNuevaDesc" rows="3" v-model="user_description" maxlength="5000"></textarea>
                                         </div>
                                        <div class="btn-group w-100" v-if="is_edit_desc">
                                            <span class="btn btn-success col fileinput-button dz-clickable" @click="saveDescription">
                                                <i class="fas fa-save"></i>
                                                <span>Guardar</span>
                                            </span>
                                            <button type="submit" class="btn btn-primary col start" @click="is_edit_desc = false">
                                                <i class="fas fa-times-circle"></i>
                                                <span>Cancelar</span>
                                            </button>
                                        </div>

                                        <table class="table table-sm mt-lg-4">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 50%;">Fecha de creación</td>
                                                    <td style="width: 50%;">
                                                        @{{user.created_at | DateFormatES1}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Correo electrónico</td>
                                                    <td>
                                                    @{{user.email}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Contacto telefónico</td>
                                                    <td>
                                                    @{{user.telephone}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Publicaciones</td>
                                                    <td>
                                                    @{{user.count_posts}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Proximos eventos</td>
                                                    <td>
                                                    @{{user.count_events}}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@Push('customScript')
    <script src="{{ asset('js/app-config-user.js') }}"></script>
@endpush