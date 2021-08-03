
Vue.component('building-page',require('../../components/BuildingPageComponent.vue').default);

const appBiographies = new Vue({
    el: "#app_bios_client",
    data: {
        saludo: "Hello Word"
    }
});