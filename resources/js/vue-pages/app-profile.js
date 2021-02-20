
//Aqui van los componentes que usara esta pagina

import {operacion,showLoadingAC,closeLoadingAC,operacionStatus,showAlertMsgAC} from '../sw-handler'

Vue.component('post-event', require('../components/post/PostComponent.vue').default);
Vue.component('post-form-component', require('../components/post/Formulario.vue').default);
Vue.component('post-media-component', require('../components/post/Media.vue').default);
Vue.component('post-modal-component', require('../components/post/ModalVideo.vue').default);




const appProfile = new Vue({
    el: "#appProfile",
    data: function(){
        return{
            edit_mode_desc: false,
            description_insert: "",
            current_user_id: 0,
            items_post: [],
            items_events: [],

            artistic_name: null,
            count_posts: null,
            count_events: null,
            content_desc: null,
            desc_empty: false,
            isEditStatus: false
            
        }
    },
    mounted: function(){
        //De esta forma se debe de hacer en todas, el token se pone en la template 
        let token_access =  $("#current_save_token_generate").val();
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${token_access}`; //todas las solicitudes lo llevaran 
        console.log("TODAS LAS PETICIONES SE ENVIARAN CON ESTE TOKEN");
        console.log(window.axios.defaults.headers.common['Authorization']);
        console.log(token_access);

        this.current_user_id = $("#current_id_user_log").val();
        console.log("El id del usuario logeado es " + this.current_user_id);
    },
    created: function(){
        //this.loadData();
    },
    methods: {
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
            if(size_campo1 < 1  || size_campo1 > 500){
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
                console.log("Este es el resultado")               ;
                console.log(response);
            }).catch((ex)=>{
                StatusHandler.Exception("Registrar el metadato del usuario",ex);
            });
            
        },
        loadData: function(){
            showLoadingAC();

            let token = globalTokenApi;
            console.log("MI TOKEM", token)
            axios(`/api/profile?api_token=${token}`).then((result)=>{
                closeLoadingAC();
                console.log(result)
                var resDat = result.data;
                console.log("Error con la peticion,", resDat)
                if(resDat.codeStatus === 1){
                    console.log(result.data);

                    this.artistic_name  = resDat.objectData.profile.artistic_name;
                    this.count_posts    = resDat.objectData.profile.count_posts;
                    this.count_events   = resDat.objectData.profile.count_evebts;
                    this.content_desc   = resDat.objectData.profile.content_desc;
                    if(this.content_desc === null || this.content_desc.trim().length == 0){
                        this.content_desc = "";
                        this.desc_empty = true;
                    }
                }else{
                    showAlertMsgAC(result.data.msg,operacion.DEFAULT,operacionStatus.FAIL);
                }
            }).catch((ex)=>{
                console.error("UN ERROR",ex);
                closeLoadingAC();
                showAlertMsgAC("Error al recuperar la infomacion",operacion.DEFAULT,operacionStatus.SUCCESS);
            });
        },
        onClickEdit: function(){
            this.edit_mode_desc = true;
        }
    }
});
