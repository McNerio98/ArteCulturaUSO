
Vue.component('media-viewer', require('../../components/media/ViewMediaComponent.vue').default);
Vue.component('control-trim', require('../../components/trim/TrimComponentv2.vue').default);
Vue.component('preview-media',require('../../components/media/PreviewMediaComponent.vue').default);

Vue.component('pagination-component',require('../../components/pagination/PaginationComponent.vue').default);



//Registro de componentes locales 
import GeneralInfoComponent from '../../components/profile/GeneralInfoComponent.vue';
import AboutComponent from '../../components/profile/AboutComponent.vue';
import {getUserProfileInformation} from '../../service';
import {getModel88,formatter88} from '../../formatters';
import PostEventCreateComponent from '../../components/post/PostEventCreateComponent.vue';
import PostEventShowComponent from '../../components/post/PostEventShowComponent.vue';

const appProfileVue = new Vue({
    el: "#appProfile",
    components: {
        //Registro de componentes locales 
        "profile-summary": GeneralInfoComponent,
        "profile-about": AboutComponent,
        "postevent-create": PostEventCreateComponent,
        "postevent": PostEventShowComponent
    },
    data: function(){
        return{
            isCreating: false,
            modelo_create: [],
            profileSummary: [],
            profileAbout: [],
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

        }).catch(ex => {
            let target_process = "Guardar la informacion";
            StatusHandler.Exception(target_process,ex);  
        });
        
    },
    created: function(){
        this.current_user_id = $("#currentUserIdRequest").val();        
        this.acAppData = window.obj_ac_app;
    },
    methods: {
        onCreate: function(tipo){
            alert("Entrando con este tipo " + tipo);
            this.isCreating= true;
            this.modelo_create.splice(0);

            var nuevo = getModel88();
            nuevo.type_post = tipo;
            if(this.modelo_create.length > 0){//Esto lo esta haciendo disparar cada que cambia
                
                this.$set(this.modelo_create.array, 0, formatter88(nuevo,this.acAppData.storage_url));
            }else{
                this.modelo_create.push(formatter88(nuevo,this.acAppData.storage_url));
            }
        },        
        itemLoaded: function(fulldata){
            this.items_postevents = fulldata.map(e=>{
                return formatter88(e,this.acAppData.storage_url);
            });

            this.flags.show_pg1 = this.items_postevents.length == 0 ? false: true;

            if(fulldata.length != 0){
                this.onCreate('event');
            }            
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
            this.profileSummary[0].cout_postevents = parseInt(e.owner.count_posts + e.owner.count_events);
            this.items_postevents.unshift(formatter88(e,this.acAppData.storage_url));
        },
        onUpdatePostEvent: function(id){
            window.location.href = this.acAppData.base_url + '/perfil/' + this.current_user_id + '/post/edit/' + id;
        },
        onDeletePostEvent: function(index){
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
