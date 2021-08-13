
Vue.component('building-page',require('../../components/BuildingPageComponent.vue').default);

//Para el administrador se maneja una sola vista, para homenajes y biografias 
const appMemoriesVue = new Vue({
    el: "#appMemories",
    data: {
        acAppData: {}
    }
});