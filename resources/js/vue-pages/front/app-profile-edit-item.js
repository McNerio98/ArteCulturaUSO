
Vue.component('media-viewer', require('../../components/media/ViewMediaComponent.vue').default);
Vue.component('modal-trim-img', require('../../components/trim/TrimComponent.vue').default);


Vue.component('spinner1',require('../../components/spinners/Spinner1Component.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);

//Para el registro de componentes locales 
import ProfileSummary from '../../components/profile/GeneralInfoComponent.vue';
import ProfileAbout from '../../components/profile/AboutComponent.vue';
import {getUserProfileInformation,getPostEvent} from '../../service';
import {formatter87,formatter88} from '../../formatters';
import PostEventCreateComponent from '../../components/post/PostEventCreateComponent.vue';
import PostEventShowComponent from '../../components/post/PostEventShowComponent.vue';

const appProfileItemEditVue = new Vue({
    el: "#appProfileItemEdit",
    components: {
        "postevent-create": PostEventCreateComponent,
        "postevent-show": PostEventShowComponent,
        "profile-summary": ProfileSummary,
        "profile-about": ProfileAbout},    
    data:{
        acAppData: {},
        modelo: [],
        profileSummary: [],
        profileAbout: [],


        is_mdprofiles: false, // is media profiles 
        type_media: "", // PROFILE_MEDIAS        
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

        //Cargar toda la informacion y distribuirla a los subcomponentes 
        getUserProfileInformation(this.current_user_id).then(result => {
            let response = result.data;
            if(response.code == 0){
                StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                return;
            }; 

            this.profileSummary.push({
                current_mediaprofile: response.data.user.profile_img || {},
                nickname: response.data.user.artistic_name,
                fullname: response.data.user.name,
                media_profile: response.data.media_profile || [],
                tags: response.data.tags,
                cout_postevents: response.data.user.count_posts + response.data.user.count_events
            });

            this.profileAbout.push({
                email: response.data.user.email,
                username: response.data.user.username,
                phone: response.data.user.telephone,
                owner_account: response.data.user.name,
                address:  response.data.metas.find(e => e.key === 'user_profile_address')?.value,
                notes: response.data.metas.find(e => e.key === 'user_profile_notes')?.value
            });

            //Data for PostEventComponent 
            this.current_user = formatter87(response.data.user,this.acAppData.storage_url);
        }).catch(ex => {
            let target_process = "Guardar la informacion";
            StatusHandler.Exception(target_process,ex);  
        });        
    },
    methods: {
        loadData: function(){
            this.spinners.S1 = true;

            getPostEvent(this.target_id).then(result =>{
                let response = result.data;
                if(response.code == 0){ //sino existe lo detiene aqui 
                    StatusHandler.ShowStatus(response.msg,StatusHandler.OPERATION.DEFAULT,StatusHandler.STATUS.FAIL);
                    return;
                }  
                this.modelo.push(formatter88(response.data));
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
        onSources: function(sources){
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