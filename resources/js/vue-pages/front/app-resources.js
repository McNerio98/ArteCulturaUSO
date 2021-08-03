
Vue.component('building-page',require('../../components/BuildingPageComponent.vue').default);

const appResourcesClient = new Vue({
    el: "#app_resources_client",
    data: {
        saludo: "Hola mundo"
    }
});