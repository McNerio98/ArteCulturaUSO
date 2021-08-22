
Vue.component('building-page',require('../../components/BuildingPageComponent.vue').default);

Vue.component('memory-create',require('../../components/memories/MemoryCreateComponent').default);
Vue.component('memory-summary',require('../../components/memories/MemoryMiniViewComponent').default);
Vue.component('memory-item',require('../../components/memories/MemoryPreviewComponent.vue').default);

//Para el administrador se maneja una sola vista, para homenajes y biografias 
const appMemoriesVue = new Vue({
    el: "#appMemories", 
    data: {
        acAppData: {},
        text: ""
    }
});