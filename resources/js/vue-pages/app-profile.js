
//Aqui van los componentes que usara esta pagina 

import {operacion,showLoadingAC} from '../sw-handler'

Vue.component('post-component', require('../components/PostComponent.vue').default);
Vue.component('postFormulario-component', require('../components/post/Formulario.vue').default);
Vue.component('postMedia-component', require('../components/post/media.vue').default);


const appProfile = new Vue({
    el: "#appProfile",
    created: function(){
        showLoadingAC();
        console.log("Me estoy creando en perfiles");
    }
});
