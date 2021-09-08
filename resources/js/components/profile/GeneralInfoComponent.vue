<template>
    <div class="card card-primary card-outline">
        <div class="card-body box-profile">
            <div class="text-center">
                <div v-if="current_profile_media.url != undefined" v-bind:style="{ 'background-image': 'url(' + current_profile_media.url + ')' }"
                    class="profile-pic profile-user-img img-fluid img-circle" @click="showProfilesMedia(current_profile_media)">
                    <i class="fas fa-camera"></i>
                </div>
            </div>

            <h3 v-if="data_config.nickname.edit_mode == false" class="profile-username text-center">
                <a :href="acAppData.base_url + '/perfil/' + targetId">{{data_config.nickname.value == undefined || data_config.nickname.value.trim().length ==0 ? '(No especificado)' : data_config.nickname.value}}</a>
                    <i  v-if="authId === targetId" @click="data_config.nickname.edit_mode = true;data_config.nickname.bk = data_config.nickname.value;" class="fas fa-pen ac-edit-about"></i>
            </h3>

           

            <input class="form-control" type="text" v-model="data_config.nickname.value" placeholder="Nombre artístico o entidad" v-if="data_config.nickname.edit_mode">
            <div class="btn-group w-100" v-if="data_config.nickname.edit_mode == true">
                <button class="btn btn-success col btn-xs" @click="saveDataConfig('nickname')">
                    <i class="fas fa-plus"></i> <span>Guardar</span>
                </button>
                <button class="btn btn-warning col btn-xs" @click="data_config.nickname.edit_mode = false;data_config.nickname.value = data_config.nickname.bk;">
                    <i class="fas fa-times"></i> <span>Cancelar</span>
                </button>
            </div>  
 
            <p class="text-muted text-center">
                <span v-for="(e,index) of rubros" v-bind:key="index">
                    <span class="usTagProfile">
                    {{e.name}}
                        <span  vif="authId === targetId" class="iconDel" @click="deleteTagUser(e.id,index)"><i class="fas fa-times"></i></span>
                    </span>
                <template v-if="index != (rubros.length - 1)">,</template>
                </span>
            </p>

            <div v-if="targetId === authId">
                <button type="button" @click="showListTags" class="btn btn-block btn-default btn-xs mb-3" v-if="!flags.edit_tags">+ Agregar rubro</button>
                <select required v-model="rubro_to_insert" class="custom-select" v-if="flags.edit_tags">
                    <option value="0" disabled selected>Elegir</option>
                    <optgroup  v-for="(main, key) in list_tags" v-bind:key="key" :label="key">
                    <option  v-for="(item, i) in main" v-bind:key="i" :value="item.id">{{item.tag}}</option>
                    </optgroup>
                </select>     

                <div class="btn-group w-100" v-if="flags.edit_tags">
                    <button class="btn btn-success col btn-xs" @click="addTagUser">
                        <i class="fas fa-plus"></i><span>Guardar</span>
                    </button>
                    <button class="btn btn-warning col btn-xs" @click="flags.edit_tags = false;">
                        <i class="fas fa-times"></i><span>Cancelar</span>
                    </button>
                </div>
            </div>

            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                    <b>Publicaciones</b> <a class="float-right">{{user.count_posts}}</a>
                </li>
                <li class="list-group-item">
                    <b>Proximos Eventos</b> <a class="float-right">{{user.count_events}}</a>
                </li>
            </ul>

        <!-- /.card-body -->
        </div>
    </div>
</template>

<script>
    const {getTags} = require("../../api/api.service");
    export default {
        props: {
            targetId: {type: Number, default: -1},
            authId: {type: Number, default :0},
            itemConfig: {type: Object, default: function(){
                return undefined;
            }}
        },
        data: function(){
            return {
                acAppData: {},
                current_profile_media: {},
                data_config: {
                    nickname:  {value: undefined, bk: undefined, edit_mode: false},
                },
                user: {},
                media_profile: [],
                rubros: [],
                flags: {
                    edit_tags: false
                },
                list_tags: undefined,
                rubro_to_insert: 0,
                media_view: {
                    owner: 0,
                    target: {},
                    items: []
                }
            }
        } ,
        created: function(){
            if(this.itemConfig === undefined){
                this.loadData();
            }else{
                this.data_config = this.itemConfig;
            }
            this.acAppData = window.obj_ac_app;        
        },
        methods: {
            setCounts: function(posts,events){
                this.user.count_posts = posts;
                this.user.count_events = events;
            },
            setProfileImg: function(url){
                this.current_profile_media.url = url;
            },
            loadData: function(){
                //# get User model and tags relacionated 
                axios(`/api/profile/gnInfo/${this.targetId}`).then(result=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }
                    this.user =  response.data.user;
                    this.rubros = response.data.tags;

                    this.media_profile =  response.data.media_profile.map(e=>{
                        return {
                            id: e.id,
                            type: 'image',//only image for profiles images 
                            name: e.path_file,
                            url: this.acAppData.storage_url + "/files/profiles/" + e.path_file,
                            owner: {
                                id: e.user_id
                            }
                        }
                    });

                    let aux_media  = this.media_profile.filter(e => e.id === this.user.img_profile_id);//BUSCAR UNA OPCION OPTIMA 
                    this.current_profile_media = aux_media.length > 0 ? aux_media[0]: {};                    
                    this.data_config.nickname.value = this.user.artistic_name;
                }).catch(ex=>{
                    let target_process = "Recuperar informacion general";
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

            },
            showProfilesMedia: function(target_media){
                this.media_view.items = this.media_profile;
                this.media_view.target = target_media;
                this.$emit("medias-view",this.media_view);
            },
            deleteTagUser: function(id_tag,index){
                axios.delete(`/profile/deltag/${this.authId}/${id_tag}`).then(result=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    };
                    this.rubros.splice(index,1);
                }).catch(ex =>{
                    StatusHandler.Exception("Eliminar rubro del usuario",ex);
                });
            },
            addTagUser: function(){
                if(this.rubro_to_insert == 0 || this.rubro_to_insert == undefined){
                    return;
                }

                let params = {
                    tag_id: this.rubro_to_insert
                };
                axios.put(`/profile/tags/${this.authId}`,params).then((result)=>{
                    let response = result.data;
                    if(response.code == 0){
                        StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                        return;
                    }; 
                    if(response.code == 409){
                        return; //ya existe 
                    }

                    this.rubros.push({id: this.rubro_to_insert,name:response.data});            
                }).catch(ex=>{
                    StatusHandler.Exception("Guardar el nuevo rubro",ex);
                }).finally(e =>{
                    this.flags.edit_tags = false;
                    this.rubro_to_insert = 0;
                });

            },
            showListTags: function(){
                if(this.list_tags !== undefined){
                    this.flags.edit_tags = true;
                }else{
                    getTags().then((tags)=>{
                        this.list_tags = tags.data;
                        this.flags.edit_tags = true
                    }).catch((ex)=>{
                        console.error(ex);
                    })
                }
            }
        }
    }
</script>
