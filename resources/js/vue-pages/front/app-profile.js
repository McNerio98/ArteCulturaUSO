

Vue.component('content-create', require('../../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../../components/post/ModalVideo.vue').default);

Vue.component('media-viewer', require('../../components/media/ViewMediaComponent.vue').default);
Vue.component('modal-trim-img', require('../../components/trim/TrimComponent.vue').default);
Vue.component('post-general',require('../../components/post/PostGeneralComponent.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);

Vue.component('pagination-component',require('../../components/pagination/PaginationComponent.vue').default);


Vue.component('general-info-profile',require('../../components/profile/GeneralInfoComponent.vue').default);
Vue.component('about-profile',require('../../components/profile/AboutComponent.vue').default);

const appProfileVue = new Vue({
    el: "#appProfile",
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
            is_mdprofiles: false, // is media profiles 
            media_view: {
                owner: 0,
                target: {},
                items: []
            },
            data_config: {
                description:  {value: undefined, bk: undefined, edit_mode: false},
            }
        }
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
                        is_popular: false,
                        status: 'review',
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
            this.media_view = object_media;
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
            $('#modaPreviewMedia').modal('show');            
        },
        PostEventCreated: function(e){
            var post = {
                post: {
                    id: e.post.id,
                    title: e.post.title,
                    description: e.post.content,
                    type: e.post.type_post,
                    is_popular: false,
                    status: 'review',
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
        openTrim: function(){
            this.modal_cropper = "IMG_MEDIA_PROFILE";
            $('#modaPreviewMedia').modal('hide');
            $("#hiddenImgFileTrim").trigger("click");            
        },
        filterModalCropper: function(base64){
            if(this.modal_cropper === "DEFAULT"){console.error("Llamada inconsiste de modal cropper");return;};
            switch(this.modal_cropper){
                case "IMG_MEDIA_PROFILE": {
                    this.SendImgProfile(base64);
                }
            }
        },
        SendImgProfile: function(base64){
            let prev_path_img = this.current_profile_media.path_file;

            let data = {
                user_id: this.current_user_id,
                img_profile_upload: base64
            };

            axios.post(`/api/user/uploadImgProfile`,data).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    this.current_profile_media.path_file = prev_path_img;
                    return;
                }
                this.current_profile_media = response.data; //nueva imagen 
                this.media_profile.push(response.data);


            }).catch((ex)=>{
                StatusHandler.Exception("Establecer la nueva imagen",ex);
                this.current_profile_media.path_file = prev_path_img;
            }).finally(()=>{
                
            });
        }                       
    }
});
