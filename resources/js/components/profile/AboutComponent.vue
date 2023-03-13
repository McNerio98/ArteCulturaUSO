<template>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Acerca de</h3>
        </div>
        <!-- /.card-header -->
         <div class="card-body">
            <strong><i class="far fa-envelope"></i> Correo electrónico</strong>
            <p class="text-muted val-about">{{data_config.email.value}} </p>
            <hr>        
            
            <strong><i class="far fa-envelope"></i> Nombre de usuario</strong>
            <p class="text-muted val-about">{{data_config.username.value}} </p>
            <hr>

            <strong><i class="fas fa-phone-alt"></i> Numero Contacto</strong>
            <p v-if="data_config.phone.edit_mode == false" class="text-muted val-about">{{data_config.phone.value}} 
                <i v-if="authId === targetId" @click="onEditMode('phone')" class="fas fa-pen ac-edit-about"></i>
            </p>
            <input class="form-control form-control-sm" type="text" v-model="data_config.phone.value" placeholder="#" v-if="data_config.phone.edit_mode == true">
            <div class="btn-group w-100" v-if="data_config.phone.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('phone')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="onCancelEdit('phone')">
                    <i class="fas fa-times"></i> <span>Cancelar</span>
                </button>
            </div>  

            <hr>                
            <strong><i class="far fa-address-book"></i> Propietario de cuenta</strong>
            <p v-if="data_config.owner_account.edit_mode == false" class="text-muted val-about">{{data_config.owner_account.value}} 
                <i v-if="targetId === authId" @click="onEditMode('owner_account')" class="fas fa-pen ac-edit-about"></i>
            </p>
            <input class="form-control form-control-sm" type="text" v-model="data_config.owner_account.value" placeholder="#" v-if="data_config.owner_account.edit_mode == true">
            <div class="btn-group w-100" v-if="data_config.owner_account.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('owner_account')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="onCancelEdit('owner_account')">
                    <i class="fas fa-times"></i> <span>Cancelar</span>
                </button>
            </div>  
            <hr>            
            <!--METADATOS-->
            <!--Se podrian agregar mas-->
            <strong><i class="fas fa-map-marker-alt"></i> Dirección</strong>
            <p v-if="data_config.address.edit_mode == false" class="text-muted val-about">{{data_config.address.value == undefined || data_config.address.value.length == 0 ? 'No especificado' : data_config.address.value}} 
                <i v-if="authId === targetId" @click="onEditMode('address')" class="fas fa-pen ac-edit-about"></i>                        
            </p>
            <input class="form-control form-control-sm" type="text" v-model="data_config.address.value" placeholder="" v-if="data_config.address.edit_mode == true">
            <div class="btn-group w-100" v-if="data_config.address.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('address')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="onCancelEdit('address')">
                    <i class="fas fa-times"></i> <span>Cancelar</span>
                </button>
            </div>  

            <hr>
            <strong><i class="far fa-file-alt mr-1"></i> Notas</strong>
            <p v-if="data_config.notes.edit_mode == false" class="text-muted val-about">{{data_config.notes.value == undefined || data_config.notes.value.length == 0 ? 'No especificado' : data_config.notes.value}} 
                    <i v-if="authId === targetId" @click="onEditMode('notes')" class="fas fa-pen ac-edit-about"></i>                        
            </p>
            <input class="form-control form-control-sm" type="text" v-model="data_config.notes.value" placeholder="" v-if="data_config.notes.edit_mode == true">
            <div class="btn-group w-100" v-if="data_config.notes.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('notes')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="onCancelEdit('notes')">
                    <i class="fas fa-times"></i> <span>Cancelar</span>
                </button>
            </div>             
        </div>
        <!-- /.card-body -->
    </div>
</template>

<script>
    export default {
        props: {
            pdata: {type: Object,required:true},
            targetId: {type: Number, default: -1},
            authId: {type: Number, default :0},
        },        
        data: function(){
            return {
                itemData: JSON.parse(JSON.stringify(this.pdata)),
                data_config: {
                    email: {
                        value: null, 
                        bk: undefined, 
                        edit_mode: false
                    },
                    username: {
                        value: null, 
                        bk: undefined, 
                        edit_mode: false
                    },
                    phone: {
                        value: null, 
                        bk: undefined, 
                        edit_mode: false
                    },
                    owner_account: {
                        value: null, 
                        bk: undefined, 
                        edit_mode: false
                    },
                    address: {
                        value: null, 
                        bk: undefined, 
                        edit_mode: false
                    },
                    notes: {
                        value: null, 
                        bk: undefined, 
                        edit_mode: false
                    }
                }
            }
        },
        created: function(){
            this.loadLocalValues();
        },
        methods: {
            loadLocalValues: function(){
                this.data_config.email.value= this.itemData.email;
                this.data_config.username.value= this.itemData.username;
                this.data_config.phone.value= this.itemData.phone;
                this.data_config.owner_account.value= this.itemData.owner_account;
                this.data_config.address.value= this.itemData.address;
                this.data_config.notes.value= this.itemData.notes;
            },
            onEditMode: function(key){
                this.data_config[key].edit_mode = true; 
                this.data_config[key].bk = this.data_config[key].value
            },
            onCancelEdit: function(key){
                this.data_config[key].edit_mode = false; 
                this.data_config[key].value = this.data_config[key].bk
            },
            saveDataConfig: function(key){
                const data_info = {
                    user_id: this.authId,//id de usuario logeado 
                    info_key: key,
                    info_value: this.data_config[key].value,
                };             

                var path_uri = "";
                if(key.trim() == 'address' || key.trim() == 'notes' || key.trim() == 'description'){
                    path_uri = "/usersmeta";
                    data_info.info_key = "user_profile_" + key; 
                }else{
                    path_uri = "/users"; //solo retrocede una ruta atras por defecto  
                    data_info.info_key = "user_" + key; 
                }

                axios.post(path_uri,data_info).then(result=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }; 
                    this.data_config[key].edit_mode = false;
                }).catch(ex =>{
                    this.data_config[key].value = this.data_config[key].bk;
                    let target_process = "Guardar la informacion";
                    StatusHandler.Exception(target_process,ex);                    
                });                
            }
        }
    }
</script>
