
//Aqui van los componentes que usara esta pagina

import {operacion,showLoadingAC,closeLoadingAC,operacionStatus,showAlertMsgAC} from '../sw-handler'

Vue.component('post-component', require('../components/PostComponent.vue').default);
Vue.component('postFormulario-component', require('../components/post/Formulario.vue').default);
Vue.component('postMedia-component', require('../components/post/media.vue').default);
Vue.component('postModal-component', require('../components/post/modal.vue').default);




const appProfile = new Vue({
    el: "#appProfile",
    data: function(){
        return{
            artistic_name: null,
            count_posts: null,
            count_events: null,
            content_desc: null,
            desc_empty: false,
            isEditStatus: false
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
                        this.desc_empty = true;
                        console.log("Estoy vacio y me tengo que quitrar");
                    }
                }else{
                    showAlertMsgAC(result.data.msg,operacion.DEFAULT,operacionStatus.FAIL);
                }
            }).catch((ex)=>{
                console.error("UN ERROR",ex);
                closeLoadingAC();
                showAlertMsgAC("Error al recuperar la infomacion",operacion.DEFAULT,operacionStatus.SUCCESS);
            });
        }
    }
});
