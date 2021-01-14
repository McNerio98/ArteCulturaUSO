
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
            artistic_name: null,
            count_posts: null,
            count_events: null,
            content_desc: null,
            desc_empty: false,
            isEditStatus: false,
            edit_mode_desc: false
        }
    },
    created: function(){
        this.loadData();
    },
    methods: {
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
