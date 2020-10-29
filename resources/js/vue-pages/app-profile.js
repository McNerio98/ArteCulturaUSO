
//Aqui van los componentes que usara esta pagina 

import {operacion,showLoadingAC,closeLoadingAC,operacionStatus,showAlertMsgAC} from '../sw-handler'

Vue.component('post-component', require('../components/PostComponent.vue').default);
Vue.component('postFormulario-component', require('../components/post/Formulario.vue').default);
Vue.component('postMedia-component', require('../components/post/media.vue').default);


const appProfile = new Vue({
    el: "#appProfile",
    created: function(){
        console.log("Me estoy creando en perfiles");
        this.loadData();
    },
    methods: {
        loadData: function(){
            showLoadingAC();
            console.log("Voy a mandar esto en la header: " + globalTokenApi);
            let token = globalTokenApi;

            axios(`/api/profile?api_token=${token}`).then((result)=>{
                closeLoadingAC();
                console.log(result.data);
            }).catch((ex)=>{
                closeLoadingAC();
                showAlertMsgAC("Error al recuperar la infomacion",operacion.DEFAULT,operacionStatus.SUCCESS); 
            });   
        }
    }
});
