
Vue.component('media-viewer', require('../../components/media/ViewMediaComponent.vue').default);
Vue.component('modal-trim-img', require('../../components/trim/TrimComponent.vue').default);

Vue.component('general-info-profile',require('../../components/profile/GeneralInfoComponent.vue').default);
Vue.component('about-profile',require('../../components/profile/AboutComponent.vue').default);

Vue.component('content-create', require('../../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../../components/post/ModalVideo.vue').default);

Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);
Vue.component('post-general',require('../../components/post/PostGeneralComponent.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);

const appProfileItemEditVue = new Vue({
    el: "#appProfileItemEdit",
    data:{
        acAppData: {},
        is_mdprofiles: false, // is media profiles 
        media_view: {
            owner: 0,
            target: {},
            items: []
        },        
        current_user_id: 0,
        modal_cropper: "DEFAULT",   
        user: {},
        data_config: {
            description:  {value: undefined, bk: undefined, edit_mode: false},
        },
        
        spinners: {
            S1:false //for loading info post
        },
        flags: {
            show_edited: false,
        },
        target_id: 0,
        pe_items: [], //post and events 
        buffer: { //buffer para edicion
            edit_mode: false, 
            source: {}
        }        
    },
    created: function(){
        this.acAppData = window.obj_ac_app;
    },
    mounted: function(){
        this.target_id = parseInt(document.getElementById("temp_iden_edit").value);
        this.current_user_id = parseInt(document.getElementById("current_user_id_request").value);
        
        if(!isNaN(this.target_id)){
            this.loadData();
        }
    },
    methods: {
        loadData: function(){
            this.spinners.S1 = true;
            axios(`/api/post/${this.target_id}`).then(result=>{
                let response = result.data;
                if(response.code == 0){ //sino existe lo detiene aqui 
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }  
                
                var e = response.data;
                this.buffer.source =  {
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
                        switch(ng.type_file){//IMPORTANT FOR EDIT MODE PASS URL FOR ALL ITEMS 
                            case "image": {ng.url = this.acAppData.storage_url +"/files/images/"  + ng.name;break;}
                            case "docfile": {ng.url = this.acAppData.storage_url + "/files/docs/pe" + e.id + "/" + ng.name;break;}
                            case "video": {ng.url = this.acAppData.storage_url + "/images/youtube_item.jpg";break;}
                        }
                        return ng;
                    })
                }                

                //Dejar esto asi ya que esto ayuda a sincronizar los datos del componente 
                //con los pasados como parametros 
                //Es decirt, se debe colocar una flag en el componente para indicar que se mostrara si esta en modo edicion,
                //pero antes de mostrar la flag se pasan los daots 
                this.buffer.edit_mode = true;

            }).catch(ex=>{
                let target_process = "Recuperar elemento";
                StatusHandler.Exception(target_process,ex);                
            }).finally(e=>{
                this.spinners.S1 = false;
            });
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
                    profile_img: e.creator.profile_img != undefined ?  this.acAppData.storage_url + "/files/profiles/" + e.creator.profile_img.path_file : null, 
                },
                media: e.post.media.map(ng => {//el formato para esto se filtra en el otro compnente
                    switch(ng.type_file){
                        case "image": {ng.url = this.acAppData.storage_url+"/files/images/"  + ng.name;break;}
                        case "docfile": {ng.url =  this.acAppData.storage_url + "/files/docs/pe" + e.post.id + "/" + ng.name;break;}
                        case "video": {ng.url =  this.acAppData.storage_url + "/images/youtube_item.jpg";break;}
                    }
                    return ng;
                }),
                meta: []                        
            }

            this.pe_items.push(post);
            this.flags.show_edited = true;
        },
        onSources: function(){

        },        
        onPhotosProfiles: function(object_media){
            this.media_view = object_media;
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