
/*Registro de componentes globales */
Vue.component('content-create', require('../../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../../components/post/ModalVideo.vue').default);

Vue.component('media-viewer', require('../../components/media/ViewMediaComponent.vue').default);
Vue.component('control-trim', require('../../components/trim/TrimComponentv2.vue').default);
Vue.component('post-general',require('../../components/post/PostGeneralComponent.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);

Vue.component('pagination-component',require('../../components/pagination/PaginationComponent.vue').default);



//Registro de componentes locales 
import Component1 from '../../components/profile/GeneralInfoComponent.vue';
import Component2 from '../../components/profile/AboutComponent.vue';

const appProfileVue = new Vue({
    el: "#appProfile",
    components: {
        //Registro de componentes locales 
        "general-info-profile": Component1,
        "about-profile": Component2
    },
    data: function(){
        return{
            flags: {
                show_pg1: true, //Show pagination 1, profiles 
            },
            items_postevents: [],
            acAppData: {},
            flag_create: {
                type: "post",
                creating: false
            },
            current_user_id: 0,
            modal_cropper: "DEFAULT",
            content_desc: "",
            desc_empty: false,
            isEditStatus: false,
            current_user: {},            
            type_media: "", // PROFILE_MEDIAS
            media_view: {
                owner: 0,
                target: {},
                items: []
            },
            data_config: {
                description:  {value: undefined, bk: undefined, edit_mode: false},
            },
            //Buffer para componente recorte 
            trim_buffer: {
                aspec_ratio: 1/1,
                target: "" //element that open cropper 
            },
            //End Buffer componente recorte             
        }
    },
    mounted: function(){
        
    },
    created: function(){
        this.current_user_id = $("#current_user_id_request").val();        
        this.acAppData = window.obj_ac_app;
    },
    methods: {
        itemLoaded: function(fulldata){
            this.items_postevents = fulldata.map(e=>{
                return {
                    post: {
                        id: e.id,
                        title: e.title,
                        description: e.content,
                        type: e.type_post,
                        is_popular: e.is_popular,
                        status: e.status,
                        created_at: e.created_at,
                    },
                    dtl_event: {
                        event_date: e.event_date,
                        has_cost: e.has_cost,
                        cost: e.cost,
                        frequency: e.frequency
                    },                    
                    creator: {
                        id: e.creator_id,
                        name: e.creator_name,
                        nickname: e.creator_nickname,
                        profile_img:  this.acAppData.storage_url + "/files/profiles/" + e.creator_profile, 
                    },
                    media: e.media.map(ng => {//el formato para esto se filtra en el otro compnente
                        switch(ng.type_file){
                            case "image": {ng.url = this.acAppData.storage_url +"/files/images/"  + ng.name;break;}
                            case "docfile": {ng.url = this.acAppData.storage_url + "/files/docs/pe" + e.id + "/" + ng.name;break;}
                            case "video": {ng.url = this.acAppData.storage_url + "/images/youtube_item.jpg";break;}
                        }
                        ng.owner = e.creator_id;
                        return ng;
                    }),
                    meta: []                      
                }
            });

            this.flags.show_pg1 = this.items_postevents.length == 0 ? false: true;
        },
        loadInfoUser: function(e){//From emmited event node 
            //console.log("FULLDARA CARGADA");
            this.current_user = {
                id                          :e.user.id,
                nickname            :e.user.artistic_name,
                fullname              :e.user.name,
                profile_path         :this.acAppData.storage_url + "/files/profiles/" + e.user.profile_img.path_file
            };
            
            this.data_config.description = e.metas.description;
        },
        saveDataConfig: function(key){
            const data_info = {
                user_id: this.current_user_id,//id de usuario logeado 
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
        onPhotosProfiles: function(object_media){
            this.media_view = object_media; //Ya trae el target e items 
            this.type_media = 'PROFILE_MEDIAS'; //para imagenes de perfiles
            $('#modaPreviewMedia').modal('show');            
        },
        onSources: function(sources){
            //Formateando segun el formato esperado por el preview 
            var aux = sources.map((e)=>{
                return {
                    id: e.id,
                    type: e.type_file,
                    name: e.name,
                    url: e.url,
                    owner: {
                        id: e.owner
                    }
                }
            });

            this.media_view.items = aux;
            this.media_view.target = aux[0];
            this.type_media = 'POST_EVENTS';
            $('#modaPreviewMedia').modal('show');            
        },
        PostEventCreated: function(e){
            this.$refs.vmInfoGeneral.setCounts(e.creator.count_posts,e.creator.count_events);
            var post = {
                post: {
                    id: e.post.id,
                    title: e.post.title,
                    description: e.post.content,
                    type: e.post.type_post,
                    is_popular: e.post.is_popular,
                    status: e.post.status,
                    created_at: e.post.created_at,
                },
                dtl_event: {
                    event_date: e.dtl_event.event_date,
                    has_cost: e.dtl_event.has_cost,
                    cost: e.dtl_event.cost,
                    frequency: e.dtl_event.frequency,
                },                
                creator: {
                    id: e.creator.id,
                    name: e.creator.name,
                    nickname: e.creator.nickname,
                    profile_img: e.creator.profile_img != undefined ? this.acAppData.base_url + "/files/profiles/" + e.creator.profile_img.path_file : null, 
                },
                media: e.post.media.map(ng => {//el formato para esto se filtra en el otro compnente
                    switch(ng.type_file){
                        case "image": {ng.url = this.acAppData.base_url +"/files/images/"  + ng.name;break;}
                        case "docfile": {ng.url = this.acAppData.base_url + "/files/docs/pe" + e.post.id + "/" + ng.name;break;}
                        case "video": {ng.url = this.acAppData.base_url + "/images/youtube_item.jpg";break;}
                    }
                    return ng;
                }),
                meta: []                        
            }
            this.items_postevents.unshift(post);
            this.flag_create.creating = false;        
        },
        onItemEdit: function(id){
            window.location.href = this.acAppData.base_url + '/perfil/' + this.current_user_id + '/post/edit/' + id;
        },
        onDelete: function(index){
            this.items_postevents.splice(index,1);
        },
        onChageImage: function(){
            this.$refs.fileElementImage.click();
        },
        cropperImageProfile: function(event){
            $('#modaPreviewMedia').modal('hide');
            this.$refs.acVmCompCropper.openTrim( event.target.files[0]);
            this.trim_buffer.target = "IMG_MEDIA_PROFILE"; 
        },
        filterModalCropper: function(base64){
            switch(this.trim_buffer.target){
                case "IMG_MEDIA_PROFILE": {
                    this.SendImgProfile(base64);
                    break;
                }
            }
            //Se debe dejar vacio, dado que el evento ya ocurrio y se espera otro nuevo
            this.trim_buffer.target = '';
        },
        SendImgProfile: function(base64){
            let data = {
                user_id: this.current_user_id,
                img_profile_upload: base64
            };

            axios.post(`/user/uploadImgProfile`,data).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }
                
                var image_url = this.acAppData.storage_url + "/files/profiles/" +response.data.path_file;
                this.current_user.profile_path = image_url;
                this.$refs.vmInfoGeneral.setProfileImg(image_url);
                this.items_postevents.map(e=>{
                    e.creator.profile_img = image_url;
                });
            }).catch((ex)=>{
                StatusHandler.Exception("Establecer la nueva imagen",ex);
            });
        }                       
    }
});
