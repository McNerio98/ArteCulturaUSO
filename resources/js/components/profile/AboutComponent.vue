<template>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Acerca de</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <strong><i class="far fa-envelope"></i> Correo Contacto</strong>
            <p v-if="data_config.email.edit_mode == false" class="text-muted val-about">{{data_config.email.value}} 
                    <i  v-if="authId === targetId" @click="data_config.email.edit_mode = true;data_config.email.bk = data_config.email.value" class="fas fa-pen ac-edit-about"></i>
            </p>

            <input class="form-control form-control-sm" v-model="data_config.email.value" type="text" placeholder="example@example.com" v-if="data_config.email.edit_mode == true">
            <div class="btn-group w-100" v-if="data_config.email.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('email')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="data_config.email.edit_mode = false; data_config.email.value = data_config.email.bk">
                    <i class="fas fa-times"></i> <span>Cancelar</span>
                </button>
            </div>                          

            <hr>            
            <strong><i class="fas fa-phone-alt"></i> Numero Contacto</strong>
            <p v-if="data_config.phone.edit_mode == false" class="text-muted val-about">{{data_config.phone.value}} 
                <i v-if="authId === targetId" @click="data_config.phone.edit_mode = true; data_config.phone.bk = data_config.phone.value;" class="fas fa-pen ac-edit-about"></i>
            </p>
            <input class="form-control form-control-sm" type="text" v-model="data_config.phone.value" placeholder="#" v-if="data_config.phone.edit_mode == true">
            <div class="btn-group w-100" v-if="data_config.phone.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('phone')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="data_config.phone.edit_mode = false; data_config.phone.value = data_config.phone.bk;">
                    <i class="fas fa-times"></i> <span>Cancelar</span>
                </button>
            </div>   

            <hr>                
            <strong><i class="far fa-address-book"></i> Otros nombres</strong>
            <p v-if="data_config.other_name.edit_mode == false" class="text-muted val-about">{{data_config.other_name.value}} 
                <i v-if="targetId === authId" @click="data_config.other_name.edit_mode = true; data_config.other_name.bk = data_config.other_name.value;" class="fas fa-pen ac-edit-about"></i>
            </p>
            <input class="form-control form-control-sm" type="text" v-model="data_config.other_name.value" placeholder="#" v-if="data_config.other_name.edit_mode == true">
            <div class="btn-group w-100" v-if="data_config.other_name.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('other_name')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="data_config.other_name.edit_mode = false; data_config.other_name.value = data_config.other_name.bk">
                    <i class="fas fa-times"></i> <span>Cancelar</span>
                </button>
            </div>  

            <hr>
            <!--METADATOS-->
            <!--Se podrian agregar mas-->
            <strong><i class="fas fa-map-marker-alt"></i> Dirección</strong>
            <p v-if="data_config.address.edit_mode == false" class="text-muted val-about">{{data_config.address.value == undefined || data_config.address.value.length == 0 ? 'No especificado' : data_config.address.value}} 
                <i v-if="authId === targetId" @click="data_config.address.edit_mode = true" class="fas fa-pen ac-edit-about"></i>                        
            </p>
            <input class="form-control form-control-sm" type="text" v-model="data_config.address.value" placeholder="" v-if="data_config.address.edit_mode == true">
            <div class="btn-group w-100" v-if="data_config.address.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('address')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="data_config.address.edit_mode = false">
                    <i class="fas fa-times"></i> <span>Cancelar</span>
                </button>
            </div>  

            <hr>
            <strong><i class="far fa-file-alt mr-1"></i> Notas</strong>
            <p v-if="data_config.notes.edit_mode == false" class="text-muted val-about">{{data_config.notes.value == undefined || data_config.notes.value.length == 0 ? 'No especificado' : data_config.notes.value}} 
                    <i v-if="authId === targetId" @click="data_config.notes.edit_mode = true" class="fas fa-pen ac-edit-about"></i>                        
            </p>
            <input class="form-control form-control-sm" type="text" v-model="data_config.notes.value" placeholder="" v-if="data_config.notes.edit_mode == true">
            <div class="btn-group w-100" v-if="data_config.notes.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('notes')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="data_config.notes.edit_mode = false">
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
            targetId: {type: Number, default: -1},
            authId: {type: Number, default :0},
            itemConfig: {type: Object, default: function(){
                return undefined;
            }},
        },        
        data: function(){
            return {
                data_config: {
                    email: {value: undefined, bk: undefined, edit_mode: false},
                    phone: {value: undefined, bk: undefined, edit_mode: false},
                    other_name: {value: undefined, bk: undefined, edit_mode: false},
                    address: {value: undefined, bk: undefined, edit_mode: false},
                    notes: {value: undefined, bk: undefined, edit_mode: false},
                    description: {value: undefined, bk: undefined, edit_mode: false},
                }
            }
        },
        created: function(){
            if(this.itemConfig === undefined){
                this.loadData();
            }else{
                this.data_config = this.itemConfig;
            }
        },
        methods: {
            loadData: function(){
                //# get user model and metadata
                axios(`/api/profile/aboutUser/${this.targetId}`).then(result=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }
                this.data_config.email.value = response.data.user.email;
                this.data_config.phone.value = response.data.user.telephone;
                this.data_config.other_name.value = response.data.user.name;
                this.data_config.address.value = response.data.metas.find(e => e.key === 'user_profile_address')?.value;
                this.data_config.notes.value = response.data.metas.find(e => e.key === 'user_profile_notes')?.value;                  
                this.data_config.description.value = response.data.metas.find(e => e.key === 'user_profile_description')?.value;  

                var data_temp = {
                    user: response.data.user,
                    metas: this.data_config
                };
                //# Emitir la informacion del usuario y el modelo usuario como tal 
                this.$emit('info-user',data_temp); 

                }).catch(ex=>{
                    let target_process = "Recuperar informacion";
                    StatusHandler.Exception(target_process,ex);                    
                })
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
