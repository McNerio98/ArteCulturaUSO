
//Aqui van los componentes que usara esta pagina 

import {operacion,showLoadingAC} from '../sw-handler'

const appProfile = new Vue({
    el: "#appProfile",
    created: function(){
        showLoadingAC();
        console.log("Me estoy creando en perfiles");
    }
});
