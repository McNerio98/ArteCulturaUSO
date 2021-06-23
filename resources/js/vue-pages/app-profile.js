
const {getTags} = require("../api/api.service");
Vue.component('post-event', require('../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../components/post/ModalVideo.vue').default);

Vue.component('media-viewer', require('../components/media/ViewMediaComponent.vue').default);
Vue.component('modal-trim-img', require('../components/trim/TrimComponent.vue').default);
Vue.component('post-general',require('../components/post/PostGeneralComponent.vue').default);


const appProfile = new Vue({
    el: "#appProfile",
    data: function(){
        return{
            is_creating_event : false,
            is_creating_post : false,
            edit_mode_desc: false,
            description_insert: "",
            current_user_id: 0,
            items_posts: [],
            items_events: [],
            items_events_server: [],
            items_post_server: [],
            paths: {
                media_profiles: "../files/profiles/",
                files_docs: "../files/pdfs/",
                files_images: "../files/images/",                    
            },
            modal_cropper: "DEFAULT",
            content_desc: "",
            desc_empty: false,
            isEditStatus: false,
            user: {},
            current_profile_media: {},
            media_profile: [],
            list_tags: undefined,
            is_edit_tags: false,
            rubro_to_insert: 0,
            rubros: [],
            is_mdprofiles: false, // is media profiles 
            media_view: {
                owner: 0,
                target: 0,
                items: []
            }
        }
    },
    mounted: function(){
        this.loadData();
    },
    created: function(){
        this.current_user_id = $("#current_user_id_request").val();        
    },
    methods: {
        showListTags: function(){
            if(this.list_tags !== undefined){
                this.is_edit_tags = true;
            }else{
                getTags().then((tags)=>{
                    this.list_tags = tags.data;
                    this.is_edit_tags = true;
                }).catch((ex)=>{
                    console.error(ex);
                })
            }
        },
        deleteTagUser: function(id_tag,index){
            console.log("Eliminar con este id " + id_tag);
            axios.delete(`/api/profile/deltag/${this.current_user_id}/${id_tag}`).then(result=>{
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
            let params = {
                tag_id: this.rubro_to_insert
            };
            axios.put(`/api/profile/tags/${this.current_user_id}`,params).then((result)=>{
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
                this.is_edit_tags = false;
                this.rubro_to_insert = 0;
            });

        },
        loadPosts: function(){
            const data = {
                type_post: "post",
                user_id: this.current_user_id
            }
            //el componente de la mini general preview aun falta          
        },
        loadEvents: function(){
            const data = {
                type_post: "post",
                user_id: this.current_user_id
            }
            //el componente de la mini general preview aun falta                       
        },
        storeUserDescription: function(){
            let size_campo1 = this.description_insert.length;
            if(size_campo1 < 1  || size_campo1 > 1000){
                StatusHandler.ValidationMsg("El tamaño de la descripción no es valida");
                return;
            };

            const meta = {
                user_id: this.current_user_id,//id de usuario logeado 
                meta_key: "user_profile_description",
                meta_value: this.description_insert,
            }; 

            axios.post(`/api/usermeta`,meta).then((result)=>{
                let response = result.data;
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }; 
                this.content_desc = response.data.value;
                this.description_insert = this.content_desc;
            }).catch((ex)=>{
                StatusHandler.Exception("Registrar el metadato del usuario",ex);
            }).finally(()=>{
                this.edit_mode_desc = false;
            });
            
        },
        PostEventCreated: function(e){
            console.log("Se emitio el evento");

            var post = {
                post: {
                    id: e.id,
                    title: e.title,
                    description: e.content,
                    type: e.type_post,
                    creator_id: e.creator_id,
                    is_popular: false,
                    status: 'review',
                    created_at: e.created_at,
                    name: this.user.name,
                    artistic_name: this.user.artistic_name == undefined ? '(No Especificado)' : this.user.artistic_name,
                    img_owner: this.current_profile_media.path_file
                },
                media: e.media,
                meta: []                        
            }
            if(e.type_post == "post"){
                this.items_posts.unshift(post);
                this.is_creating_post = false;
            }

            if(e.type_post == "event"){
                this.items_events.unshift(post);
                this.is_creating_event = false;                
            }            

        },
        loadData: function(){
            axios(`/api/profile/${this.current_user_id}`).then((result)=>{
                let response = result.data;                
                if(response.code == 0){
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                };

                this.user = response.data.user;
                var aux_desc = response.data.metas.find(e => e.key === 'user_profile_description')?.value;
                this.content_desc   = aux_desc == undefined ? "" : aux_desc;
                this.media_profile = response.data.media_profile;
                this.rubros = response.data.tags;
                this.items_events_server = response.data.items_events;
                this.items_post_server = response.data.items_post;

                //extra data processing
                this.description_insert = this.content_desc;
                this.media_view.owner = this.user.id;
                let aux_media  = this.media_profile.filter(e => e.id === this.user.img_profile_id);
                this.current_profile_media = aux_media.length > 0 ? aux_media[0]: {};

                //procesando eventos 
                this.items_events = this.items_events_server.map(e =>{
                    return {
                        post: {
                            id: e.id,
                            title: e.title,
                            description: e.content,
                            type: e.type_post,
                            creator_id: e.creator_id,
                            is_popular: e.is_popular,
                            status: e.status,
                            created_at: e.created_at,
                            name: this.user.name,
                            artistic_name: this.user.artistic_name == undefined ? '(No Especificado)' : this.user.artistic_name,
                            img_owner: this.current_profile_media.path_file
                        },
                        media: e.media,
                        meta: []                        
                    }
                });


                //procesando post 
                this.items_posts = this.items_post_server.map(e =>{
                    return {
                        post: {
                            id: e.id,
                            title: e.title,
                            description: e.content,
                            type: e.type_post,
                            creator_id: e.creator_id,
                            is_popular: e.is_popular,
                            status: e.status,
                            created_at: e.created_at,
                            name: this.user.name,
                            artistic_name: this.user.artistic_name == undefined ? '(No Especificado)' : this.user.artistic_name,
                            img_owner: this.current_profile_media.path_file
                        },
                        media: e.media,
                        meta: []                        
                    }
                });                

            }).catch((ex)=>{
                console.error("UN ERROR",ex);
                closeLoadingAC();
                showAlertMsgAC("Error al recuperar la infomacion",operacion.DEFAULT,operacionStatus.SUCCESS);
            });
        },
        onClickEdit: function(){
            this.edit_mode_desc = true;
        },
        showProfilesMedia: function(target_media){
            //estableciendo como carga el panel 
            //asignando archivos 
            //mostrando 
            this.is_mdprofiles = true;
            this.media_view.items = this.media_profile;
            this.media_view.target = target_media;
            $('#modaPreviewMedia').modal('show');
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
