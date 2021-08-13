
Vue.component('building-page',require('../../components/BuildingPageComponent.vue').default);

const appResourcesVue = new Vue({
    el: "#appResources",
    data: {
        acAppData: {}
    }
});