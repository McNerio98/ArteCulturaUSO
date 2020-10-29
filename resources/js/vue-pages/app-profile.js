
//Aqui van los componentes que usara esta pagina 

import {operacion,showLoadingAC,closeLoadingAC,operacionStatus,showAlertMsgAC} from '../sw-handler'

Vue.component('post-component', require('../components/PostComponent.vue').default);
Vue.component('postFormulario-component', require('../components/post/Formulario.vue').default);
Vue.component('postMedia-component', require('../components/post/media.vue').default);


const appProfile = new Vue({
    el: "#appProfile",
    created: function(){
        this.loadData();
    },
    methods: {
        loadData: function(){
            showLoadingAC();

            let token = globalTokenApi;
            axios(`/api/profile?api_token=${token}`).then((result)=>{
                closeLoadingAC();
                if(result.data.codeStatus === 1){
                    console.log(result.data);
                }else{
                    showAlertMsgAC(result.data.msg,operacion.DEFAULT,operacionStatus.FAIL);
                }
            }).catch((ex)=>{
                closeLoadingAC();
                showAlertMsgAC("Error al recuperar la infomacion",operacion.DEFAULT,operacionStatus.SUCCESS); 
            });   
        }
    }
});
